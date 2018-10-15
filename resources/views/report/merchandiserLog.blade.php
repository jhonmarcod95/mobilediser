@extends('layouts.app')
@section('content')

    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTable').DataTable( {
                "paging":   false
            } );
        } );

        var tableToExcel = (function() {
            var uri = 'data:application/vnd.ms-excel;base64,'
                , template =
                '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' +
                    '<head></head>' +
                    '<body>' +
                        '<table>' +
                            '<tr>' +
                                '<td></td>' +
                                '<td></td>' +
                            '</tr>' +
                        '</table>' +
                        '<table border=\'1\'>{table}</table>' +
                    '</body>' +
                '</html>'
                , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
            return function(table, name) {
                if (!table.nodeType) table = document.getElementById(table)
                var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
                window.location.href = uri + base64(format(template, ctx))
            }
        })()
    </script>

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
                        <h3 class="box-title">Log Record</h3>
                    </div>

                    <div class="box-body">
                        {!! Form::open(['url' => '/reports/merchandiserLog', 'method' => 'GET']) !!}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date From</label>
                                    {!! Form::date('date_from', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date To</label>
                                    {!! Form::date('date_to', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">&nbsp;</label><br>
                                    {!! Form::Submit('Filter', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group pull-right">
                                    <label class="text-muted">&nbsp;</label><br>
                                    {!! Form::button('Export to Excel', ['class' => 'btn btn-primary', 'onclick' => 'tableToExcel(\'dataTable\', \'Off Take Report\')']) !!}
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}

                        <div class="table-responsive mailbox-messages">
                            <div class="table table-responsive">
                                <table id="dataTable" class="table table-bordered table-striped" style="white-space: nowrap; width: 100%">
                                    <thead>
                                    <th></th>
                                    <th>Merchandiser</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Inventory Status</th>
                                    </thead>
                                    <tbody>
                                    @foreach($merchandiser_logs as $merchandiser_log)
                                        <tr>
                                            <td>
                                                <a href="#" data-toggle="modal" onclick="
                                                        document.getElementById('merchandiserImage').src = '{{ asset('storage/' . $merchandiser_log->image_path) }}';
                                                        document.getElementById('title').innerText = '{{ $merchandiser_log->first_name . ' ' . $merchandiser_log->last_name . ' - ' . $merchandiser_log->customer_name . ' - ' . $merchandiser_log->date . ' - ' .  Carbon::parse($merchandiser_log->time_in)->format('h:i a')}}';
                                                        " data-target="#modal-default">Image
                                                </a>
                                            </td>
                                            <td>{{ $merchandiser_log->first_name . ' ' . $merchandiser_log->last_name }}</td>
                                            <td>{{ $merchandiser_log->customer_name }}</td>
                                            <td>{{ $merchandiser_log->date }}</td>
                                            <td>{{ Carbon::parse($merchandiser_log->time_in)->format('h:i a') }}</td>
                                            <td>
                                                @if($merchandiser_log->time_out != null)
                                                    {{ Carbon::parse($merchandiser_log->time_out)->format('h:i a') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($merchandiser_log->transaction_number != null)
                                                    <small class="label label-success">Submitted</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('report.merchandiserLogModal')
    </section>
@endsection
