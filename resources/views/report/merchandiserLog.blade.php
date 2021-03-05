
@extends('layouts.app')
@section('content')

    <section class="content-header">
        <h1>
            Merchandiser Logs
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-bar-chart"></i> Report</a></li>
            <li class="active">Merchandiser Logs</li>
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
                                    <input type="date" id="date-from" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date To</label>
                                    <input type="date" id="date-to" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">&nbsp;</label><br>
                                    <button id="btn-filter" class="btn btn-default btn-sm"><i class="fa fa-filter"></i>&nbsp;Filter</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mailbox-messages">
                            <div id="table-logs" class="table table-responsive"></div>
                        </div>
                    </div>
                    <div id="loading-logs"></div>
                </div>
            </div>
        </div>

        @include('report.merchandiserLogModal')
    </section>


@endsection


@section('script')
<script>
    /* filtering *************************************/
    let scheduleUrl = '/reports/merchandiser-log-data';
    let merchandiser_ids = '';
    let startOfMonth = '';
    let endOfMonth = '';

    $('#btn-filter').click(function() {

        merchandiser_ids = $('#merchandiser_ids').val();
        startOfMonth = $('#date-from').val();
        endOfMonth = $('#date-to').val();

        fetchLogs();
    });

    function fetchLogs() {
        showLoading('loading-logs', true);
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
                //note: there's  a chance to have a null values in image_path due to GROUP BY clause
                let logs = alasql("SELECT " +
                    "id, " +
                    "LAST(first_name) AS first_name, " +
                    "LAST(last_name) AS last_name, " +
                    "LAST(customer_code) AS customer_code, " +
                    "LAST(`store`) AS `store`, " +
                    "LAST(branch) AS branch, " +
                    "LAST(date) AS date, " +
                    "LAST(time_in) AS time_in, " +
                    "LAST(time_out) AS time_out, " +
                    "LAST(transaction_number) AS transaction_number," +
                    "LAST(image_path) AS image_path " +
                    "FROM ? " +
                    "GROUP BY id", [data]);

                //table-body
                let body = '';
                for (let log of logs){
                    let merchandiser = log.first_name + ' ' + log.last_name;

                    let inventoryStatus = '';
                    if(log.transaction_number != null){
                        inventoryStatus = '<small class="label label-success">Submitted</small>';
                    }

                    body +=
                    '<tr>' +
                        '<td>' +
                            '<a href="#" data-toggle="modal" data-target="#modal-default" onclick="' +
                                'document.getElementById(\'merchandiserImage\').src = \'../storage/' + log.image_path + '\';' +
                                'document.getElementById(\'title\').innerText = \'' + merchandiser + ' - ' + log.store + ' ' + log.date + ' - ' + toTimeString(log.time_in) + '\';">' + log.id +
                            '</a>' +
                        '<td>' + merchandiser +
                        '<td>' + log.customer_code +
                        '<td>' + log.store + ' - ' + log.branch +
                        '<td>' + log.date +
                        '<td>' + toTimeString(log.time_in) +
                        '<td>' + toTimeString(log.time_out) +
                        '<td>' + inventoryStatus +
                    '</tr>';
                }

                //table
                let table =
                    '<table id="dataTable2" class="table table-bordered table-striped" style="width: 100%">' +
                        '<thead>' +
                            '<th>Schedule Id' +
                            '<th>Merchandiser' +
                            '<th>Code' +
                            '<th>Store' +
                            '<th>Date' +
                            '<th>Time In' +
                            '<th>Time Out' +
                            '<th>Inventory Status' +
                        '</thead>' +
                        '<tbody>' + body + '</tbody>' +
                    '</table>';

                $('#table-logs').html(
                    table
                );

                setDataTable(0);
                showLoading('loading-logs', false);
            }
        });
    }
    /* ***********************************************/
</script>

@endsection
