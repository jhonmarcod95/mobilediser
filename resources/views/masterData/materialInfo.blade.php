{{--
    form inputs has @ to catch error of null values
--}}

@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if($isEdit)
                Material Info
            @else
                Material Registration
            @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/materials') }}"><i class="fa fa-database"></i>Material Master Data</a></li>
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
                        {!! Form::open(['url' => '/materials/' . $actionUrl, 'method' => 'POST']) !!}
                        {!! @Form::hidden('id', $material->id) !!}

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Material Code</label>
                                    {!! @Form::text('material_code', $material->material_code, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Description</label>
                                    {!! @Form::text('material_description', $material->material_description, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Base Unit</label>
                                    {!! @Form::text('base_unit', $material->base_unit, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
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


