{{--
    form inputs has @ to catch error of null values
--}}

@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Announcement Info
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/announcements') }}"><i class="fa fa-user"></i>Announcements</a></li>
            <li class="active">
                Info
            </li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-6">

                <div class="box">
                    <div class="box-header ">

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {!! Form::open(['url' => '/announcements/update/' . $announcement->id, 'method' => 'POST']) !!}

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-muted">Message</label>
                                    {!! Form::textarea('message', $announcement->message, ['class' => 'form-control', 'required', 'rows' => '15']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                @include('layouts.errors')
                            </div>
                        </div>

                        <div class="box-footer">
                            {!! Form::submit('Save Changes', ['class' => 'btn btn-primary', 'name' => 'submit']) !!} &nbsp;
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger', 'name' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}


                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>



@endsection


