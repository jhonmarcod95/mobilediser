@extends('layouts.app')
@section('content')

    <section class="content-header">
        <h1>
            Merchandiser Log Summary
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-bar-chart"></i> Report</a></li>
            <li class="active">Merchandiser Log Summary Report</li>
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
                                    <label>Search Merchandiser: </label>
                                    {!! Form::select('merchandiser_ids[]', $merchandisers, null, ['id' => 'merchandiser_ids', 'class' => 'form-control select2', 'multiple']) !!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date From</label>
                                    <input id="date-from" class="form-control" type="date">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date To</label>
                                    <input id="date-to" class="form-control" type="date">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">&nbsp;</label><br>
                                    <button id="btn-filter" class="btn btn-default btn-sm"><i class="fa fa-filter"></i>&nbsp;Filter</button>
                                </div>
                            </div>

                            <div class="col-md-2 pull-right">
                                <div class="form-group">
                                    <label class="text-muted">&nbsp;</label><br>
                                    <button class="btn btn-primary btn-sm" onclick="tableToExcel('table-log', 'Merchandiser Performance')"><i class="fa fa-file-excel-o"></i>&nbsp;Export</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mailbox-messages">
                            <div class="table table-responsive">
                                <table id="table-log" class="table table-bordered table-striped" style="white-space: nowrap; width: 100%">
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="loading-log"></div>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('script')
<script>

    /* filtering *************************************/
    let scheduleUrl = '/reports/merchandiser-performance-data';
    let merchandiser_ids = '';
    let startOfMonth = '';
    let endOfMonth = '';

    $('#btn-filter').click(function() {

        merchandiser_ids = $('#merchandiser_ids').val();
        startOfMonth = $('#date-from').val();
        endOfMonth = $('#date-to').val();

        fetchSchedules();
    });

    function fetchSchedules() {
        showLoading('loading-log', true);
        $.ajax({
            type: 'GET',
            url: scheduleUrl,
            dataType: 'json',
            contentType: 'application/json',
            data: {
                merchandiser_ids: merchandiser_ids,
                startOfMonth: startOfMonth,
                endOfMonth: endOfMonth
            },
            success: function (data) {
                let dates = data['dates'];
                let merchandisers = data['merchandisers'];
                let schedules = data['schedules'];

                //get merchandisers
                let merchandiserLog = '';
                let grandTotalLoginCount = 0;


                for (let merchandiser of merchandisers) {
                    let merchandiser_id = merchandiser.merchandiser_id;
                    let first_name = merchandiser.first_name;
                    let last_name = merchandiser.last_name;

                    //get dates and count login's of each dates
                    let dateKey = '';
                    let totalLoginCount = 0;
                    for (let date of dates) {
                        //count distinct due to chances to have a duplicate id's in other foreign tables
                        let loginCount = alasql("SELECT COUNT(DISTINCT id) AS login_count FROM ? WHERE merchandiser_id = " + merchandiser_id + " AND date = '" + date + "'", [schedules])[0].login_count;
                        //create json string values
                        dateKey += '"' + moment(date).format('D-MMM-YY') +  '": "' + loginCount + '",'; //ex: {"2018-11-17", "5"}
                        totalLoginCount += loginCount;
                    }
                    dateKey = removeLastComma(dateKey);

                    //create json string array
                    merchandiserLog += '{' +
                            '"Merchandiser": "' + first_name + ' ' + last_name + '", ' +
                            '' + dateKey + ',' +
                            '"Total":' + totalLoginCount +
                        '},';

                    grandTotalLoginCount += totalLoginCount;
                }
                merchandiserLog = removeLastComma(merchandiserLog);
                merchandiserLog = JSON.parse('[' + merchandiserLog + ']');

                let keyLength = countJsonKeys(merchandiserLog);

                let footer =
                    '<tfoot>' +
                        '<tr>' +
                            '<td colspan="' + keyLength + '">' +
                            '<td>Grand Total : ' + grandTotalLoginCount +
                        '</tr>' +
                    '</tfoot>';

                $('#table-log').html(populateJsonArrayTable(merchandiserLog, true, null, footer));
                showLoading('loading-log', false);
            }
        });
    }
    /* ***********************************************/




</script>

@endsection
