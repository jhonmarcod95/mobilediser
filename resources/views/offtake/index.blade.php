@extends('layouts.app')
@section('content')

    <section class="content-header">
        <h1>
            Offtake Report
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
                    </div>
                    <div class="box-body">

                        {{-- First Filter --}}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Place : </label>
                                    {!! Form::select('place', $places, null, ['class' => 'form-control select2', 'id' => 'place']) !!}
                                </div>
                            </div>

                            <div id="div-place"></div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Customer Account : </label>
                                    {!! Form::select('customer_account', $customerAccounts, null, ['class' => 'form-control select2', 'id' => 'customer-account']) !!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Chain : </label>
                                    {!! Form::select('chain', $chains, null, ['class' => 'form-control select2', 'multiple', 'id' => 'chain']) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Customer : </label>
                                    {!! Form::select('customer', $customers, null, ['class' => 'form-control select2', 'multiple', 'id' => 'customer']) !!}
                                </div>
                            </div>
                        </div>

                        {{-- Second Filter --}}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Category : </label>
                                    {!! Form::select('place', $places, null, ['class' => 'form-control select2', 'multiple', 'id' => 'filter']) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">SKU : </label>
                                    {!! Form::select('place', $places, null, ['class' => 'form-control select2', 'multiple', 'id' => 'filter']) !!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date From</label>
                                    <input id="date-from" name="date_from" type="date" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date To</label>
                                    <input id="date-to" name="date_to" type="date" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">&nbsp;</label><br>
                                    <button class="btn btn-default"><i class="fa fa-filter"></i>Filter</button>
                                </div>
                            </div>

                        </div>

                    </div>
                    {{--<div class="box-body">--}}
                        {{--{!! Form::open(['url' => '/reports/inventoryLog', 'method' => 'GET']) !!}--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-2">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label class="text-muted">Date From</label>--}}
                                    {{--{!! Form::date('date_from', null, ['class' => 'form-control']) !!}--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-2">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label class="text-muted">Date To</label>--}}
                                    {{--{!! Form::date('date_to', null, ['class' => 'form-control']) !!}--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-2">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label class="text-muted">&nbsp;</label><br>--}}
                                    {{--{!! Form::Submit('Filter', ['class' => 'btn btn-primary']) !!}--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--{!! Form::close() !!}--}}

                        {{--<div class="table-responsive mailbox-messages">--}}
                            {{--<div class="table table-responsive">--}}
                                {{--<table id="dataTable" class="table table-bordered table-striped" style="white-space: nowrap; width: 100%">--}}
                                    {{--<thead>--}}
                                    {{--<th></th>--}}
                                    {{--<th>Transaction#</th>--}}
                                    {{--<th>Merchandiser</th>--}}
                                    {{--<th>Customer</th>--}}
                                    {{--<th>Remarks</th>--}}
                                    {{--<th>Date Submitted</th>--}}
                                    {{--</thead>--}}
                                    {{--<tbody>--}}
                                    {{--@foreach($inventory_logs as $inventory_log)--}}
                                        {{--<tr>--}}
                                            {{--<td>--}}
                                                {{--<a href="#" data-toggle="modal" onclick="--}}
                                                        {{--document.getElementById('inventoryFrame').src = '{{ url('/reports/inventoryLogTransaction/' . $inventory_log->transaction_number) }}';--}}
                                                        {{--document.getElementById('title').innerText = 'Transaction Number :  {{ $inventory_log->transaction_number }}';--}}
                                                        {{--" data-target="#modal-default">Image--}}
                                                {{--</a>--}}
                                            {{--</td>--}}
                                            {{--<td>{{ $inventory_log->transaction_number }}</td>--}}
                                            {{--<td>{{ $inventory_log->first_name . ' ' . $inventory_log->last_name }}</td>--}}
                                            {{--<td>{{ $inventory_log->customer_name }}</td>--}}
                                            {{--<td>{{ $inventory_log->remarks }}</td>--}}
                                            {{--<td>{{ Carbon::parse($inventory_log->created_at)->format('Y-m-d h:i a') }}</td>--}}
                                        {{--</tr>--}}
                                    {{--@endforeach--}}
                                    {{--</tbody>--}}
                                {{--</table>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                </div>
            </div>
        </div>

    </section>

@endsection

@section('script')
<script>
    $('#customer').val('%').trigger('change');
    $('#customer-account').val('%').trigger('change');
    $('#chain').val('%').trigger('change');

    fetchFilterOptions();

    function fetchFilterOptions(){
        $.ajax({
            type: 'GET',
            url: '/island-all',
            success: function(data)
            {

            }
        });
    }


    $('#place').change(function () {
        let place;
        switch (this.value){
            case '%':
                place = null;
                break;
            case 'island':
                place = '<div class="col-md-2">' +
                            '<div class="form-group">' +
                                '<label class="text-muted">Island : </label>' +
                                '{!! Form::select('island', $islands, null, ['class' => 'form-control select2', 'id' => 'island']) !!}' +
                            '</div>' +
                        '</div>';
                break;
            case 'region':
                place = '<div class="col-md-2">' +
                            '<div class="form-group">' +
                                '<label class="text-muted">Region : </label>' +
                                '{!! Form::select('region', $regions, null, ['class' => 'form-control select2', 'multiple', 'id' => 'region']) !!}' +
                                '</div>' +
                        '</div>';
                break;
            case 'province':
                place = '<div class="col-md-2">' +
                            '<div class="form-group">' +
                                '<label class="text-muted">Province : </label>' +
                                '{!! Form::select('province', $provinces, null, ['class' => 'form-control select2', 'multiple', 'id' => 'province']) !!}' +
                            '</div>' +
                        '</div>';
                break;
            case 'municipality':
                place = '<div class="col-md-2">' +
                            '<div class="form-group">' +
                                '<label class="text-muted">Municipality : </label>' +
                                '{!! Form::select('municipality', $municipalities, null, ['class' => 'form-control select2', 'multiple', 'id' => 'municipality']) !!}' +
                            '</div>' +
                        '</div>';
                break;
        }

        $('#div-place').html(place);

        if(place != null) setSelect2(this.value, '%');

    });





    // setSelect2('filter-customer', '%');


</script>
@endsection