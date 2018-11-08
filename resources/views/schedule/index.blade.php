@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Schedules
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Schedules</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            {{-- Schedule Table Box --}}
            <div class="col-md-9">
                <div class="box box-default">
                    <div class="box-header ">
                        <div class="row">
                            <div class="col-md-7">
                                <label>Search Merchandiser: </label>
                                {!! Form::select('merchandiser_ids[]', $merchandisers, null, ['id' => 'merchandiser_ids', 'class' => 'form-control select2', 'multiple']) !!}
                            </div>
                            <div class="col-md-3">
                                <label>Date: </label>
                                <input id="monthYear" name="monthYear" type="month" class="form-control" value="" required>
                            </div>
                            <div class="col-md-2">
                                <br>
                                <button id="search-button" class="btn btn-default" type="submit"><i class="fa fa-search"></i>&nbsp; Search
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="box-body">
                        <div class="table-responsive">
                            <div id="schedule-table" class="table table-responsive">
                            </div>
                        </div>
                    </div>
                    <div id="loading-schedules"></div>
                </div>
            </div>

            {{-- Upload Excel Box --}}
            <div class="col-md-3">
                <div class="box box-default">
                    <div class="box-header ">
                        <h3 class="box-title">Upload Excel File</h3>
                    </div>

                    {{-- Content --}}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Date: </label>
                                    <input id="upload-month-year" name="monthYear" type="month" class="form-control input-sm" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        {!! Form::file('import_file', ['id' => 'import-file']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button id="upload-button" type="button" class="btn btn-primary btn-sm">Upload</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div id="response-message" class="col-md-12">
                            </div>
                        </div>

                    </div>
                    <div id="loading-upload"></div>
                </div>
            </div>
        </div>

        <!-- /.row -->
        <div class="row">
            <div class="col-md-12">
                @include('layouts.errors')
            </div>
        </div>
    </section>

    {{-- Modal --}}
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg direct-chat direct-chat-warning">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="box-header">
                        <h3 id="chat_title" class="box-title">Schedule</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-dismiss="modal"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="box box-solid" style="border: none">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-muted">Store</label>
                                <div class="form-group">
                                    {!! Form::select('customers', $customers, null, ['id' => 'sel-customer', 'class' => 'form-control select2', 'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="text-muted">Week</label>
                                <div class="form-group">
                                    {!! Form::select('weeks', $weeks, '%', ['id' => 'sel-week', 'class' => 'form-control select2', 'multiple', 'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="text-muted">Days</label>
                                <div class="form-group">
                                    {!! Form::select('weekdays', $weekDays, null, ['id' => 'sel-weekday', 'class' => 'form-control select2', 'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="text-muted">Start Time</label>
                                <div class="form-group">
                                    {!! Form::time('startTime', null, ['id' => 'start-time', 'class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="text-muted">End Time</label>
                                <div class="form-group">
                                    {!! Form::time('endTime', null, ['id' => 'end-time','class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button id="btn-add" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</button>
                                    {{--<button id="btn-update" type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Update</button>--}}
                                    <button id="btn-delete" type="button" class="btn btn-danger btn-sm pull-right"><i class="fa fa-trash"></i> Delete</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="response-schedule" class="col-md-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th id="th-weekdays"></th>
                                            <th id="th-customer"></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th><input id="chk-schedule-all" type="checkbox"></th>
                                            <th>Date</th>
                                            <th>Store</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody-schedule">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <div id="loading-schedule-show"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        /* modal closing event ****************************/
        var hasChange = false; //used to reload schedule table if the has changes in db
        $('#modal-default').on('hidden.bs.modal', function () {
            if(hasChange) $("#search-button").click();
            hasChange = false;
        });
        /**************************************************/

        /* checkbox check all ******************************/
        $('#chk-schedule-all').click(function (event) {
            if (this.checked) {
                $('.chk-schedule').each(function () {
                    $(this).prop('checked', true);
                });
            } else {
                $('.chk-schedule').each(function () {
                    $(this).prop('checked', false);
                });
            }
        });
        /**************************************************/

        /* searching **************************************/
        var merchandiser_ids = '';
        var monthYear = '';
        var scheduleUrl = '/schedules-data';

        $('#search-button').click(function () {
            merchandiser_ids = $('#merchandiser_ids').val();
            monthYear = $('#monthYear').val();
            fetchSchedules(scheduleUrl);
        });
        /**************************************************/

        /* upload excel button ****************************/
        var uploadMonthYear = '';

        $('#upload-button').click(function () {
            uploadMonthYear = $('#upload-month-year').val();
            uploadSchedule();
        });
        /**************************************************/

        /* merchandiser schedule modal ********************/
        var schedules;
        var weekdays;
        var customers;
        var merchandiser_id;

        function showSchedule(id){
            $("#response-schedule").html("");
            $("#chk-schedule-all").prop('checked', false);

            showLoading('loading-schedule-show', true);
            $.ajax({
                type: 'GET',
                url: '/schedules/show/' + id,
                dataType: 'json',
                contentType: 'application/json',
                data: {
                    monthYear: monthYear
                },
                success: function(data){
                    customers = data.customers;
                    schedules = data.schedules;
                    weekdays = data.weekdays;
                    merchandiser_id = id;
                    // populateScheduleTable(schedules);

                    //populate th-customer
                    var option = '';
                    $.each(customers, function(key, customer) {
                        option += '<option value=' + key + '>' + customer + '</option>';
                    });
                    $('#th-customer').html('<select id="filter-customer" onchange="filterSchedule()">' + option + '</select>');

                    //populate th-weekday
                    var option = '';
                    $.each(weekdays, function(key, weekday) {
                        option += '<option value=' + key + '>' + weekday + '</option>'
                    });
                    option += '<option value="%">All</option>';
                    $('#th-weekdays').html('<select id="filter-weekday" onchange="filterSchedule()">' + option + '</select>');

                    //set default values
                    setSelect2('filter-customer', '%');
                    setSelect2('filter-weekday', '%');

                    showLoading('loading-schedule-show', false);
                },
            });
        }
        /**************************************************/

        /* merchandiser schedule filter *******************/
        function filterSchedule() {
            var weekday = $("#filter-weekday").val();
            var customer = $("#filter-customer").val();

            var days = filterDates(weekday);
            var customers = filterCustomers(customer);

            //filter schedule
            var filterSchedule = $.map(schedules, function(i) {
                if (whereIn(i.customer_code, customers) && whereIn(i.date, days)){
                   return i;
                }
            });
            populateScheduleTable(filterSchedule);
        }

        function filterCustomers(pCustomer) {
            var result = [];
            if(pCustomer == '%') { //all
                $.each(customers, function(key, customer) {
                    result.push(key);
                });
            }
            else{
                result.push(pCustomer);
            }
            return result;
        }
        
        function filterDates(pDay) {
            var days = [];
            try {
                if(pDay == '%'){ //all
                    $.each(weekdays, function(key, weekday) {
                        days = days.concat(getDaysOfMonth(monthYear, key));
                    });
                }
                else{
                    days = (getDaysOfMonth(monthYear, pDay));
                }
            }
            catch {
                //throw
            }
            return days;
        }
        /**************************************************/

        /* add schedule button ****************************/
        $('#btn-add').click(function () {
            showLoading('loading-schedule-show', true);

            var customer = $("#sel-customer").val();
            var start_time = $("#start-time").val();
            var end_time = $("#end-time").val();
            var weeks = getSelectMultipleValue('sel-week', '%');
            var day = $("#sel-weekday").val();
            var weekdays = getWeekDays(weeks, day);

            $.ajax({
                url: '/schedules/save',
                dataType: 'json',
                type: 'post',
                contentType: 'application/json',
                data: JSON.stringify({
                    merchandiser_id: merchandiser_id,
                    store: customer,
                    weekdays: weekdays,
                    start_time: start_time,
                    end_time: end_time,
                    _token: '{{ csrf_token() }}'
                }),
                success: function(data){
                    hasChange = true;

                    showSchedule(merchandiser_id);
                    $('#response-schedule').html(showSuccessAlert('Schedule has been added.'));
                },
                error: function(data){
                    showLoading('loading-schedule-show', false);
                    $("#response-schedule").html(showErrorAlert(data));
                },
            });

        });
        /**************************************************/

        /* update schedule button *************************/
        $('#btn-update').click(function () {
            // showLoading('loading-schedule-show', true);

            var schedule_ids = getCheckboxChecked('chk-schedule');
            var customer = $("#sel-customer").val();
            var start_time = $("#start-time").val();
            var end_time = $("#end-time").val();
            var weeks = getSelectMultipleValue('sel-week', '%');
            var day = $("#sel-weekday").val();
            var weekdays = getWeekDays(weeks, day);

            $.ajax({
                url: '/schedules/update',
                dataType: 'json',
                type: 'post',
                contentType: 'application/json',
                data: JSON.stringify({
                    checkbox: schedule_ids,
                    merchandiser_id: merchandiser_id,
                    store: customer,
                    weekdays: weekdays,
                    start_time: start_time,
                    end_time: end_time,
                    _token: '{{ csrf_token() }}'
                }),
                success: function(data){
                    // showSchedule(merchandiser_id);
                    //
                    // $('#response-schedule').html(showSuccessAlert('Schedule has been added.'));

                },
                error: function(data){
                    // showLoading('loading-schedule-show', false);
                    // $("#response-schedule").html(showErrorAlert(data));
                },
            });

        });
        /**************************************************/

        /* delete schedule button *************************/
        $('#btn-delete').click(function () {
            showLoading('loading-schedule-show', true);

            var schedule_ids = getCheckboxChecked('chk-schedule');

            $.ajax({
                url: '/schedules/delete',
                dataType: 'json',
                type: 'post',
                contentType: 'application/json',
                data: JSON.stringify({
                    checkbox: schedule_ids,
                    _token: '{{ csrf_token() }}'
                }),
                success: function(data){
                    hasChange = true;
                    showSchedule(merchandiser_id);

                    $('#response-schedule').html(showSuccessAlert('Schedule has been deleted.'));
                },
                error: function(data){
                    showLoading('loading-schedule-show', false);
                    $("#response-schedule").html(showErrorAlert(data));
                },
            });
        });
        /**************************************************/

        //get weekdays based on week & day selection
        function getWeekDays(weeks, day) {
            var weekdays = [];
            $.each(weeks, function(key, week) {
                weekdays.push(getDaysOfMonth(monthYear, day)[week - 1]);
            });
            return weekdays;
        }

        function fetchSchedules(url) {
            showLoading('loading-schedules', true);
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                contentType: 'application/json',
                data: {
                    merchandiser_ids: merchandiser_ids,
                    monthYear: monthYear
                },
                success: function (data) {
                    /*--------------------------- table content ---------------------------------*/
                    var dates = data['dates'];
                    var merchandisers = data['merchandisers'];
                    var schedules = data['schedules'];

                    //table headers
                    var thead = '';
                    $.each(dates, function(key, date) {
                        thead += '<th>' + moment(date).format('MMM. DD (ddd)') + '</th>';
                    });

                    var tbody = '';
                    //fetch merchandisers
                    $.each(merchandisers, function(key, merchandiser) {
                        //fetch dates
                        var columnStore = '';
                        $.each(dates, function(keyDates, date) {
                            //filter schedule
                            var stores = $.map(schedules, function(i) {
                                if (i.merchandiser_id == merchandiser.merchandiser_id && i.date == date){
                                    return i;
                                }
                            });
                            //display customer info
                            var labelStore = '';
                            $.each(stores, function(key, store) {
                                var status = '';
                                if(store.status == '001') status = '<i class="fa fa-check"></i>'
                                labelStore += store.customer_name + ' (' + toTimeString(store.time_in) + ' - ' + toTimeString(store.time_out) + ') ' + status + '<br>';
                            });
                            columnStore += '<td ondblclick="location.href = \'/schedules/records/' +  merchandiser.merchandiser_id + '/' + date + '\';">' + labelStore;
                        });
                        //table body
                        tbody +=
                            '<tr>' +
                                '<td><a href="#" data-toggle="modal" data-target="#modal-default" onclick="showSchedule(\'' + merchandiser.merchandiser_id + '\')">' + merchandiser.first_name + ' ' + merchandiser.last_name +
                                columnStore
                            '</tr>';
                    });
                    //table
                    var table =
                        '<table id=\'dataTable2\' class=\'table table-bordered\' style=\'white-space: nowrap; width: 1%\'>' +
                            '<thead>' +
                            '<th>Merchandiser</th>' +
                            thead +
                            '</thead>' +
                            '<tbody>' +
                            tbody +
                            '</tbody>' +
                        '</table>';
                    $('#schedule-table').html(table);
                    /*--------------------------------------------------------------------------*/

                    setDataTable(1);
                    showLoading('loading-schedules', false);
                }
            });
        }

        function populateScheduleTable(schedules){
            var tbody = '';
            $.each(schedules, function(key, schedule) {
                var checkbox = '';
                if (schedule.status != '001') checkbox = '<input id="chk-schedule" class="chk-schedule" type="checkbox" value=' + schedule.id + '>';

                tbody +=
                    '<tr>' +
                        '<td>' + checkbox +
                        '<td>' + moment(schedule.date).format('MM/DD/YYYY (ddd)') +
                        '<td>' + schedule.customer_name +
                        '<td>' + toTimeString(schedule.time_in) +
                        '<td>' + toTimeString(schedule.time_out) +
                        '<td>' + schedule.status_description +
                    '</tr>';
            });
            $('#tbody-schedule').html(tbody);
        }

        function uploadSchedule() {

            var formData = new FormData();
            var importFile = $("#import-file")[0].files[0];
            if(importFile == null) importFile = '';

            formData.append('monthYear', uploadMonthYear);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('import_file', importFile);

            showLoading('loading-upload', true);
            $.ajax({
                type: 'POST',
                url: '/schedules/upload',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data){
                    showLoading('loading-upload', false);
                    $('#response-message').html(showSuccessAlert('Schedule has been uploaded.'));
                },
                error: function(data){
                    showLoading('loading-upload', false);
                    $("#response-message").html(showErrorAlert(data));
                }
            });

        }

        function onLoad() {
            $('#monthYear').val(moment().format('YYYY-MM'));
            // fetchSchedules(scheduleUrl);
        }

        onLoad();
    </script>
@endsection