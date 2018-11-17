@extends('layouts.app')
@section('content')

    <section class="content-header">
        <h1>
            Inventory Logs
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-bar-chart"></i> Report</a></li>
            <li class="active">Inventory Logs</li>
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
                        {!! Form::open(['url' => '/reports/inventoryLog', 'method' => 'GET']) !!}
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
                        </div>
                        {!! Form::close() !!}

                        <div class="table-responsive mailbox-messages">
                            <div class="table table-responsive">
                                <table id="dataTable" class="table table-bordered table-striped" style="white-space: nowrap; width: 100%">
                                    <thead>
                                    <th></th>
                                    <th>Transaction#</th>
                                    <th>Merchandiser</th>
                                    <th>Customer</th>
                                    <th>Remarks</th>
                                    <th>Date Submitted</th>
                                    </thead>
                                    <tbody>
                                    @foreach($inventory_logs as $inventory_log)
                                        <tr>
                                            <td>
                                                <a href="#" data-toggle="modal" onclick="
                                                        document.getElementById('inventoryFrame').src = '{{ url('/reports/inventoryLogTransaction/' . $inventory_log->transaction_number) }}';
                                                        document.getElementById('title').innerText = 'Transaction Number :  {{ $inventory_log->transaction_number }}';
                                                        " data-target="#modal-default">Image
                                                </a>
                                            </td>
                                            <td>{{ $inventory_log->transaction_number }}</td>
                                            <td>{{ $inventory_log->first_name . ' ' . $inventory_log->last_name }}</td>
                                            <td>{{ $inventory_log->customer_name }}</td>
                                            <td>{{ $inventory_log->remarks }}</td>
                                            <td>{{ Carbon::parse($inventory_log->created_at)->format('Y-m-d h:i a') }}</td>
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

        @include('report.inventoryLogModal')
    </section>

@endsection
