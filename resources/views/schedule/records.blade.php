@extends('layouts.app')
@section('content')
    <section class="content-header">
        <h1>
            Schedules
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Schedules</li>
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
                        {!! Form::open(['url' => '/schedules/save', 'method' => 'POST']) !!}
                        {!! Form::hidden('merchandiser_id', $merchandiser_id) !!}
                        {!! Form::hidden('date', $date) !!}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Name</label>
                                    {!! Form::text('merchandiser', $merchandiser, ['class' => 'form-control', 'maxlength' => '255', 'disabled']) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Date</label>
                                    {!! Form::date('', $date, ['class' => 'form-control', 'maxlength' => '255', 'disabled', 'required']) !!}
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Customer</label>
                                    {!! Form::select('customer', $customers, null, ['class' => 'form-control select2', 'required']) !!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Start Time</label>
                                    {!! Form::time('startTime', null, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">End Time</label>
                                    {!! Form::time('endTime', null, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
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
                            {!! Form::submit('Add Schedule', ['class' => 'btn btn-primary']) !!}
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </section>

    @if(count($schedules))
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    {!! Form::open(['url' => '/schedules/delete', 'method' => 'POST']) !!}

                    <div class="box-header ">

                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <div class="table table-responsive">
                                <table class="table table-bordered" style="white-space: nowrap; width: 100%">
                                    <thead>
                                        <tr>
                                            <td></td>
                                            <td>Merchandiser</td>
                                            <td>Customer</td>
                                            <td>Date</td>
                                            <td>Start Time</td>
                                            <td>End Time</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($schedules as $schedule)
                                        <tr>
                                            <td>{!! Form::checkbox('schedule_ids[]', $schedule->id); !!}</td>
                                            <td>{{ $schedule->last_name . ' ' . $schedule->first_name }}</td>
                                            <td>{{ $schedule->customer_name }}</td>
                                            <td>{{ $schedule->date }}</td>
                                            <td>{{ $schedule->time_in }}</td>
                                            <td>{{ $schedule->time_out }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        {!! Form::submit('Delete Schedule', ['class' => 'btn btn-danger']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>


            </div>
        </div>

    </section>
    @endif
@endsection