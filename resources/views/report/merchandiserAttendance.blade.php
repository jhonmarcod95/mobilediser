@extends('layouts.app')
@section('content')


    <section class="content-header">
        <h1>
            Merchandiser Attendance
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Reports</a></li>
            <li class="active">Merchandiser Attendance</li>
        </ol>
    </section>


    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ">
                        <h3 class="box-title">Attendance Record</h3>
                    </div>

                    <div class="box-body">
                        {!! Form::open(['url' => '/reports/merchandiserAttendance', 'method' => 'GET']) !!}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Merchandiser</label>
                                    {!! Form::select('merchandiser', $merchandisers->pluck('fullname', 'merchandiser_id'), null, ['class' => 'select2 form-control', 'multiple', 'data-placeholder' => 'Select a Merchandiser', 'style' => 'width: 300px']) !!}
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table table-responsive">
                            <table class="table table-bordered" style="white-space: nowrap; width: 1%">
                                <thead>
                                <th>Merchandiser</th>
                                @foreach($dates as $date)
                                    <th>{{ Carbon\Carbon::parse($date)->format('M d, Y') }}</th>
                                @endforeach
                                </thead>
                                <tbody>
                                @foreach($merchandisers as $merchandiser)
                                    <tr>
                                        <td>{{ $merchandiser->fullname }}</td>
                                        @foreach($dates as $date)
                                            <td>
                                                @foreach($schedules->where('merchandiser_id', $merchandiser->merchandiser_id)
                                                                   ->where('date', $date) as $schedule)

                                                    {{ $schedule->customer_name }} &nbsp;

                                                    <small class="label
                                                        @if($schedule->status == '001')
                                                            {{ 'label-success' }}
                                                        @elseif($schedule->status == '002')
                                                            {{ 'label-warning' }}
                                                        @else
                                                            {{ 'label-danger' }}
                                                        @endif">
                                                        {{ $schedule->status_description }}
                                                    </small>
                                                    <br>
                                                @endforeach
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
