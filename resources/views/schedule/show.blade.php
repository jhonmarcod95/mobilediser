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
            <div class="col-md-9">

                <div class="box box-default">

                    <div class="box-header ">
                        <div class="row">
                            {!! Form::open(['url' => '/schedules', 'method' => 'GET']) !!}
                            <div class="col-md-7">
                                <label>Search Merchandiser: </label>
                                {!! Form::select('merchandiser_ids[]', $merchandisers, null, ['class' => 'form-control select2', 'multiple', 'required']) !!}
                            </div>
                            <div class="col-md-3">
                                <label>Date: </label>
                                <input name="monthYear" type="month" class="form-control" value="{{ Request::get('monthYear') }}" required>
                            </div>
                            <div class="col-md-2">
                                <br>
                                <button class="btn btn-default" type="submit"><i class="fa fa-search"></i>&nbsp; Search</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="box-body">
                        <div class="table-responsive">
                            <div class="table table-responsive">
                                <table id="dataTable1" class="table table-bordered" style="white-space: nowrap; width: 1%">
                                    <thead>
                                    <th>Merchandiser</th>
                                    @foreach($dates as $date)
                                        <th>{{ Carbon::parse($date)->format('M d, Y (D)') }}</th>
                                    @endforeach
                                    </thead>
                                    <tbody>
                                    @foreach($schedules->unique('merchandiser_id') as $merchandiser)
                                        <tr>
                                            <td>{{ $merchandiser->first_name . ' ' . $merchandiser->last_name }}</td>
                                            @foreach($dates as $date)
                                                <td style="cursor: pointer;" ondblclick="location.href = '{{ url('/schedules/records/' . $merchandiser->merchandiser_id . '/' . Carbon::parse($date)->format('Y-m-d')) }}';">
                                                    @foreach($schedules->where('merchandiser_id', $merchandiser->merchandiser_id)
                                                        ->where('date', $date) as $schedule)
                                                        {{ $schedule->customer_name . ' (' . Carbon::parse($schedule->time_in)->format('h:i a') . '-' . Carbon::parse($schedule->time_out)->format('h:i a') . ')' }}

                                                        @if($schedule->status == '001') {{-- visited --}}
                                                            &nbsp;
                                                            <i class="fa fa-check"></i>
                                                        @endif
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
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-default">
                    <div class="box-header ">
                        <h3 class="box-title">Upload Excel File</h3>
                    </div>

                    {{-- Content --}}
                    <div class="box-body">
                        {!! Form::open(['url' => '/schedules/upload', 'method' => 'POST', 'files' => true]) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Date: </label>
                                    <input name="monthYear" type="month" class="form-control input-sm" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        {!! Form::file('import_file') !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                                </div>
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-md-12">
                @include('layouts.errors')
            </div>
        </div>

    </section>
@endsection
