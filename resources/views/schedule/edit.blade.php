@extends('layouts.app')
@section('content')
    <section class="content-header">
        <h1>
            Edit Schedule
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/schedules') }}"><i class="fa fa-calendar"></i> Schedules</a></li>
            <li><a href="{{ url('/schedules/records/' . $schedule->merchandiser_id . '/' . $schedule->date) }}"><i class="fa fa-file-text"></i>Record</a></li>
            <li class="active">Edit</li>
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
                        {!! Form::open(['url' => '/schedules/update', 'method' => 'POST']) !!}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Name</label>
                                    {!! Form::text('merchandiser', $schedule->first_name . ' ' . $schedule->last_name, ['class' => 'form-control', 'maxlength' => '255', 'disabled']) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Date</label>
                                    {!! Form::date('date', $schedule->date, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Customer</label>
                                    {!! Form::select('customer', $customers, $schedule->customer_code, ['class' => 'form-control select2', 'required']) !!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Start Time</label>
                                    {!! Form::time('startTime', $schedule->time_in, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">End Time</label>
                                    {!! Form::time('endTime', $schedule->time_out, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>
                            </div>
                        </div>


                        <div class="row">

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                @include('layouts.errors')
                            </div>
                        </div>

                        <div class="box-footer">
                            {!! Form::submit('Update Schedule', ['class' => 'btn btn-primary']) !!}
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </section>
.
@endsection