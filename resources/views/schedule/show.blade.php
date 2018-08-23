
@extends('layouts.app')



@section('content')

    <!-- Content Header (Page header) -->
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
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header ">
                        <h3 class="box-title">Schedule Record</h3>
                    </div>

                    {{-- Content --}}
                    <div class="box-body">
                        <div class="table-responsive">
                            <div class="table table-responsive">
                                <table class="table table-bordered" style="white-space: nowrap; width: 1%">
                                    <thead>
                                    <th>Merchandiser</th>
                                    <th>Customers</th>
                                    <th>Days</th>
                                    <th>Remarks</th>
                                    @foreach($dates as $date)
                                        <th>{{ Carbon\Carbon::parse($date)->format('M d, Y') }}</th>
                                    @endforeach
                                    </thead>
                                    <tbody>
                                    @foreach($merchandisers as $merchandiser)
                                        <tr>
                                            <td>{{ $merchandiser->fullname }}</td>
                                            <td id="customerTd{{ $merchandiser->merchandiser_id }}"></td>
                                            <td id="weekdayTd{{ $merchandiser->merchandiser_id }}"></td>
                                            <td id="remarksTd{{ $merchandiser->merchandiser_id }}"></td>
                                            @foreach($dates as $date)
                                                <td>
                                                    @foreach($schedules->where('merchandiser_id', $merchandiser->merchandiser_id)
                                                                       ->where('date', $date) as $schedule)
                                                        {!! Form::checkbox('chkSchedules[]', $schedule->id) !!}
                                                        {{ $schedule->customer_name }}<br>
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

                    <div class="box-footer">
                        <div class="form-group">
                            {!! Form::open(['url' => '/schedules/upload', 'method' => 'POST', 'files' => true]) !!}
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-default btn-file">
                                            <span>Import Excel</span>
                                            {!! Form::file('import_file') !!}
                                        </span>
                                <span class="fileinput-filename"></span><span class="fileinput-new"></span>
                                <button type="submit" class="btn btn-primary">Upload Excel</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
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
