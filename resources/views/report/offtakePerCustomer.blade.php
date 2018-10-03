@extends('layouts.app')
@section('content')

    @if($customer_detail != null)
    <script type="text/javascript">
        var tableToExcel = (function() {
            var uri = 'data:application/vnd.ms-excel;base64,'
                , template =
                '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' +
                    '<head></head>' +
                    '<body>' +
                        '<table>' +
                            '<tr>' +
                                '<td>Customer Code : </td>' +
                                '<td>{{ $customer_detail->customer_code }}`</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Customer Name and Branch : </td>' +
                                '<td>{{ $customer_detail->name . ' - ' . $customer_detail->branch }}</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Address : </td>' +
                                '<td>{{ $customer_detail->address }}</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Chain : </td>' +
                                '<td>{{ $customer_detail->chain_code }}`</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Customer Type : </td>' +
                                '<td>{{ $customer_detail->type_description }}</td>' +
                            '</tr>' +
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
    @endif

    <section class="content-header">
        <h1>
            Offtake Report
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ">
                        <h3 class="box-title">Schedule Record</h3>
                    </div>

                    <div class="box-body">

                        {!! Form::open(['url' => '/reports/offtakePerCustomer', 'method' => 'GET']) !!}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer: </label> <br>
                                    {!! Form::select('customer_code', $customers, null, ['class' => 'form-control select2', 'required']) !!}
                                </div>
                            </div>
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

                            <div class="col-md-2">
                                <div class="form-group pull-right">
                                    <label class="text-muted">&nbsp;</label><br>
                                    {!! Form::button('Export to Excel', ['class' => 'btn btn-primary', 'onclick' => 'tableToExcel(\'tblOfftake\', \'Off Take Report\')']) !!}
                                </div>
                            </div>


                        </div>
                        {!! Form::close() !!}

                        <div class="table-responsive mailbox-messages">
                            <div class="table table-responsive">
                                <table id="tblOfftake" class="table table-bordered table-striped" style="white-space: nowrap; width: 100%">
                                    <thead>
                                        <tr>
                                            <th colspan="3" style="text-align: center">Product Information</th>

                                            @foreach($transactionOfftakes->unique('transaction_number') as $transactionOfftake)
                                                <th colspan="8" style="text-align: center">{{ Carbon::parse($transactionOfftake->created_at)->format('M d, Y (D)') }}</th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td>Material Code</td>
                                            <td>Material Description</td>
                                            <td>Base UOM</td>
                                            @foreach($transactionOfftakes->unique('transaction_number') as $transactionOfftake)
                                                <td>Beginning Balance</td>
                                                <td>Delivery</td>
                                                <td>Warehouse</td>
                                                <td>Shelves</td>
                                                <td>BO Area</td>
                                                <td>Return</td>
                                                <td>Ending Balance</td>
                                                <td>Off Take</td>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($transactionOfftakes->unique('material_code') as $material)
                                        <tr>
                                            <td>{{ $material->material_code }}</td>
                                            <td>{{ $material->material_description }}</td>
                                            <td>{{ $material->base_uom }}</td>

                                            @foreach($transactionOfftakes->where('material_code', $material->material_code)
                                                                         ->groupBy('transaction_number') as $transactionOfftake)
                                                @foreach($transactionOfftake as $transaction)
                                                    <td>{{ $transaction->beginning_balance }}</td>
                                                    <td>{{ $transaction->delivery }}</td>
                                                    <td>{{ $transaction->warehouse_area }}</td>
                                                    <td>{{ $transaction->shelves_area }}</td>
                                                    <td>{{ $transaction->bo_area }}</td>
                                                    <td>{{ $transaction->rtv }}</td>
                                                    <td>{{ $transaction->ending_balance }}</td>
                                                    <td>{{ $transaction->offtake }}</td>
                                                @endforeach
                                            @endforeach
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
    </section>


@endsection
