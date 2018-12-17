@extends('layouts.app')
@section('content')

    <section class="content-header">
        <h1>
            Merchandiser Attendance
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Reports</a></li>
            <li class="active">Merchandiser Attendance</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ">
                    </div>

                    <div class="box-body">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Merchandiser: </label>
                                    {!! Form::select('merchandiser_ids[]', $merchandisers, null, ['id' => 'merchandiser_ids', 'class' => 'form-control select2', 'multiple']) !!}
                                </div>

                                <div class="form-group">
                                    <label>Agency: </label>
                                    {!! Form::select('agency_ids[]', $agencies, null, ['id' => 'agency_ids', 'class' => 'form-control select2', 'multiple']) !!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date From</label>
                                    <input type="date" id="date-from" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date To</label>
                                    <input type="date" id="date-to" class="form-control">
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">&nbsp;</label>
                                    <p>
                                        <button id="btn-filter" class="btn btn-default btn-sm"><i class="fa fa-filter"></i>&nbsp;Filter</button>
                                        <button class="btn btn-primary btn-sm" onclick="tableToExcel('dataTable2', 'Schedule')"><i class="fa fa-file-excel-o"></i>&nbsp;Export</button>
                                        <button id="btn-pdf" style="display: none" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</button>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mailbox-messages">
                            <div id="table-attendance" class="table table-responsive"></div>
                        </div>
                    </div>

                    <div id="loading-attendance"></div>

                </div>
            </div>
        </div>
    </section>
@endsection


@section('script')
    <script>
        //used to store table values that will pass into pdf
        let attendanceMerchandiser;

        /* filtering *************************************/
        let scheduleUrl = '/reports/merchandiser-attendance-data';
        let merchandiser_ids = '';
        let agency_ids = '';
        let dateFrom = '';
        let dateTo = '';

        $('#btn-filter').click(function() {

            merchandiser_ids = $('#merchandiser_ids').val();
            agency_ids = $('#agency_ids').val();
            dateFrom = $('#date-from').val();
            dateTo = $('#date-to').val();

            $('#btn-pdf').show();
            fetchAttendance();
        });

        $('#btn-pdf').click(function() {

            formSubmit('/reports/merchandiser-attendance-pdf', {
                attendances: JSON.stringify(attendanceMerchandiser),
                _token: '{{ csrf_token() }}'
            });
        });


        function fetchAttendance() {
            attendanceMerchandiser = JSON.parse('[]');

            showLoading('loading-attendance', true);
            $.ajax({
                type: 'GET',
                url: scheduleUrl,
                dataType: 'json',
                contentType: 'application/json',
                data: {
                    merchandiser_ids: merchandiser_ids,
                    agency_ids: agency_ids,
                    date_from: dateFrom,
                    date_to: dateTo
                },
                success: function (data) {
                    let dates = data.dates;
                    let attendances = data.attendances;
                    let colspan = '6';
                    let body = '';

                    //loop diser ids
                    for (let [id, logs] of Object.entries(attendances)) {

                        let dateFrom =  $('#date-from').val();
                        let dateTo =  $('#date-to').val();

                        let merchandiser  = alasql("SELECT " +
                            "merchandiser_id, " +
                            "(first_name + ' ' + last_name) AS merchandiser_name, " +
                            "agency.name AS agency " +
                            "FROM ? AS merchandiser JOIN ? AS agency ON merchandiser.agency_code = agency.agency_code " +
                            "WHERE merchandiser_id = " + id + "", [data.merchandisers, data.agencies])[0];


                        let totalWorkingHours = 0;
                        let totalRendered = 0;
                        let totalOvertime = 0;

                        /* content ***********************************************/
                        let content = '';

                        //note: there's  a chance to have a null values in attendance table to fix that, we need to use group by clause
                        let uniqueLogs = alasql("SELECT " +
                            "id, " +
                            "LAST(date) AS date, " +
                            "LAST(`store`) AS `store`, " +
                            "LAST(branch) AS branch, " +
                            "LAST(start_time) AS start_time, " +
                            "LAST(end_time) AS end_time, " +
                            "LAST(time_in) AS time_in, " +
                            "LAST(time_out) AS time_out " +
                            "FROM ? " +
                            "GROUP BY id", [logs]);

                        /* count working days, absents, presents ************/
                        //working days
                        let workingDays = alasql("SELECT COUNT(DISTINCT date) AS days FROM ?", [uniqueLogs])[0].days;
                        //days present
                        let datesPresent = alasql("SELECT DISTINCT date FROM ? WHERE time_in IS NOT NULL", [uniqueLogs]);
                        let daysPresent = datesPresent.length;
                        //days absent
                        let daysAbsent = alasql("SELECT COUNT(DISTINCT date) AS days FROM ? WHERE time_in IS NULL AND date NOT IN(" + objectPluck(datesPresent, 'date').join() + ")", [uniqueLogs])[0].days;
                        /* **************************************************/

                        //this query will allow to show all dates even without a schedule on a particular date
                        let dateLogs = alasql("SELECT * FROM ? AS dates LEFT JOIN ? AS uniqueLog ON dates.date = uniqueLog.date ORDER BY date", [dates, uniqueLogs]);

                        //used to store logs that will pass into pdf
                        let attendanceLogs = JSON.parse('[]');

                        for(let log of dateLogs){

                            let date = moment(log.date).format('YYYY/MM/DD');
                            let outDateTime = date + ' ' + log.time_out;
                            let inDateTime = date + ' ' + log.time_in;
                            let startDateTime = date + ' ' + log.start_time;
                            let endDateTime = date + ' ' + log.end_time;

                            /* compute working hrs ****/
                            let workingHrs = dateTimeDifference(startDateTime, endDateTime);
                            /* ************************/

                            /* compute rendered *******/
                            let timeRendered = dateTimeDifference(inDateTime, outDateTime);
                            /* ***********************/

                            /* log details ***********/
                            date = moment(log.date).format('DD-MMM-YY (ddd)');
                            let store = toBlankText(log.store) + ' - ' + toBlankText(log.branch);
                            let schedule = toTimeString(log.start_time) + ' - ' + toTimeString(log.end_time);
                            //time-in and out
                            let timeIn = toTimeString(log.time_in);
                            let timeOut = toTimeString(log.time_out);
                            if(timeOut == '' && timeIn != '') timeOut = 'No Out';
                            let timeInOut = timeIn + ' - ' + timeOut;
                            let timeRenderedText = toTimeRenderedText(timeRendered);
                            /* ***********************/

                            /* compute ot ************/
                            let overtime = '-';
                            //get difference between time out and end time
                            let overtimeTimeStamp = dateTimeDifference(endDateTime, outDateTime);
                            //convert time stamp to readable number in hrs
                            let overtimeHrs = getTimeStampHrs(overtimeTimeStamp);
                            //ot rule
                            if (overtimeHrs >= 1){
                                overtime =  toTimeRenderedText(overtimeTimeStamp);
                                totalOvertime += overtimeTimeStamp;
                            }
                            /* ***********************/


                            content +=
                                '<tr>' +
                                    '<td>' + date +
                                    '<td>' + store +
                                    '<td>' + schedule +
                                    '<td>' + timeInOut +
                                    '<td>' + toTimeRenderedText(workingHrs) +
                                    '<td>' + timeRenderedText +
                                    '<td>' + overtime +
                                '</tr>';

                            totalRendered += timeRendered;
                            totalWorkingHours += workingHrs;

                            //store attendance logs that will be used in pdf
                            attendanceLogs.push({
                                "date": date,
                                "store": store,
                                "schedule": schedule,
                                "timeInOut": timeInOut,
                                "workingHrs": toTimeRenderedText(workingHrs),
                                "timeRenderedText": timeRenderedText,
                                "overtime": overtime
                            });
                        }

                        //spacing
                        content += '<tr><td colspan="' + colspan + '">&nbsp;</tr>'
                        /* *******************************************************/

                        /* heading ***********************************************/
                        let mobilediserId = merchandiser.merchandiser_id;
                        let merchandiserName = merchandiser.merchandiser_name;
                        let period = moment(dateFrom).format('DD-MMM-YY') + ' to ' +  moment(dateTo).format('DD-MMM-YY');
                        let agency = merchandiser.agency;
                        totalRendered = toTimeRenderedText(totalRendered);
                        totalOvertime = toTimeRenderedText(totalOvertime);
                        totalWorkingHours = toTimeRenderedText(totalWorkingHours);

                        body +=
                        '<tr>' +
                            '<td colspan="6"><b>MobileDiser Id: </b>' +  mobilediserId +
                            '<td><b>Period: </b>' + period +
                        '</tr>' +
                        '<tr>' +
                            '<td colspan="6"><b>Merchandiser: </b>' +  merchandiserName +
                            '<td><b>Total Rendered: </b>' + totalRendered +
                        '</tr>' +
                        '<tr>' +
                            '<td colspan="6"><b>Agency: </b>' + agency +
                            '<td><b>Total Overtime: </b>' + totalOvertime +
                        '</tr>' +
                        '<tr>' +
                            '<td colspan="6"><b>Working Days: </b>' + workingDays +
                            '<td><b>Days Present: </b>' + daysPresent +
                        '</tr>' +
                        '<tr>' +
                            '<td colspan="6"><b>Total Working Hrs: </b>' + totalWorkingHours +
                            '<td><b>Days Absent: </b>' + daysAbsent +
                        '</tr>' +
                        '<tr><td colspan="' + colspan + '">&nbsp;</tr>' +
                        '<tr>' +
                            '<td><b>Date' +
                            '<td><b>Store' +
                            '<td><b>Schedule' +
                            '<td><b>In/Out' +
                            '<td><b>Working Hrs' +
                            '<td><b>Rendered' +
                            '<td><b>OT' +
                        '</tr>' + content;

                        //store attendance that will be used in pdf
                        attendanceMerchandiser.push({
                            "mobilediserId": mobilediserId,
                            "period": period,
                            "merchandiserName": merchandiserName,
                            "totalRendered": totalRendered,
                            "agency": agency,
                            "totalOvertime": totalOvertime,
                            "workingDays": workingDays,
                            "daysPresent": daysPresent,
                            "totalWorkingHours": totalWorkingHours,
                            "daysAbsent": daysAbsent,
                            "logs": attendanceLogs
                        });
                    }
                    /* ********************************************************/

                    let table =
                        '<table id="dataTable2" class="table table-bordered" style="width: 100%">' +
                        '<thead>' +
                        '</thead>' +
                        '<tbody>' + body + '</tbody>' +
                        '</table>';

                    $('#table-attendance').html(table);

                    showLoading('loading-attendance', false);
                }
            });
        }
        /* ***********************************************/

        function toTimeRenderedText(timeStamp) {
            if(timeStamp <= 0) return '-';
            return getTimeStampHrs(timeStamp) + ' hrs ' + getTimeStampMins(timeStamp) + ' min ';
        }
    </script>

@endsection

