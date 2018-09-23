{{--
    form inputs has @ to catch error of null values
--}}

@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if($isEdit)
                Agency Info
            @else
                Agency Registration
            @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/agencies') }}"><i class="fa fa-database"></i>Agency Master Data</a></li>
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
            <div class="col-xs-8">

                <div class="box">
                    <div class="box-header ">

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {!! Form::open(['url' => '/agencies/' . $actionUrl, 'method' => 'POST']) !!}
                        {!! @Form::hidden('agency_code', $agency->agency_code) !!}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Name</label>
                                    {!! @Form::text('name', $agency->name, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="text-muted">Address</label>
                                    {!! @Form::textarea('address', $agency->address, ['class' => 'form-control', 'maxlength' => '255', 'rows' => '5', 'required']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Contact#</label>
                                    {!! @Form::text('contact_number', $agency->contact_number, ['class' => 'form-control', 'maxlength' => '11', 'required']) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Contact Person</label>
                                    {!! @Form::text('contact_person', $agency->contact_person, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
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


