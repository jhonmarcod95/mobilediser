{{--
    form inputs has @ to catch error of null values
--}}

@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if($isEdit)
                Customer Info
            @else
                Customer Registration
            @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/customers') }}"><i class="fa fa-user"></i>Customer Master Data</a></li>
            <li class="active">
                @if($isEdit)
                    Info
                @else
                    Registration
                @endif
            </li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header ">

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {!! Form::open(['url' => '/customers/' . $actionUrl, 'method' => 'POST']) !!}
                        {!! @Form::hidden('customer_id', $customer->customer_id) !!}

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Customer Code</label>
                                    {!! @Form::text('customer_code', $customer->customer_code, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label class="text-muted">Customer Description</label>
                                    {!! @Form::text('name', $customer->name, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-muted">Address</label>
                                    {!! @Form::textarea('address', $customer->address, ['class' => 'form-control', 'maxlength' => '255', 'rows' => '5', 'required']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Customer Type</label>
                                    {!! @Form::select('customer_type_code', $customerTypes, $customer->customer_type_code, ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Customer Municipality</label>
                                    {!! @Form::select('municipality_code', $customerMunicipalities, $customer->municipality_code, ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                @include('layouts.errors')
                            </div>

                        </div>

                        <div class="box-footer">
                            @if($isEdit)
                                {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!}
                            @else
                                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            @endif
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </section>



@endsection


