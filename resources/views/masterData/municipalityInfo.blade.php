{{--
    form inputs has @ to catch error of null values
--}}

@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if($isEdit)
                Municipality Info
            @else
                Municipality Registration
            @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/municipalities') }}"><i class="fa fa-user"></i>Municipality Master Data</a></li>
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
                        {!! Form::open(['url' => '/municipalities/' . $actionUrl, 'method' => 'POST']) !!}
                        {!! @Form::hidden('id', $municipality->id) !!}

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Municipality Code</label>
                                    {!! @Form::text('municipality_code', $municipality->municipality_code, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Description</label>
                                    {!! @Form::text('description', $municipality->description, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Province</label>
                                    {!! @Form::select('provincial_code', $provinces, $municipality->provincial_code, ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
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


