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

                    <div id="loading-message"></div>
                </div>
            </div>

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
@endsection

@section('script')
    <script>
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

        function fetchSchedules(url) {
            showLoading('loading-message', true);
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
                            '<td>' + merchandiser.first_name + ' ' + merchandiser.last_name +
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
                    showLoading('loading-message', false);
                }
            });
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
                    $("#response-message").html('<div class="alert alert-success small">Schedule has been uploaded.</div>');
                },
                error: function(data){
                    showLoading('loading-upload', false);
                    var errors = $.parseJSON(data.responseText);
                    var errList = '';
                    $.each(errors.errors, function (key, val) {
                        errList += '<li>' + val + '</li>';
                    });
                    $("#response-message").html('<div class="alert alert-warning small"><ul>' + errList + '</ul></div>');
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