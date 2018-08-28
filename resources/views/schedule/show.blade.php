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
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header ">
                        <h3 class="box-title">Schedule Record</h3>

                        <div class="row">
                            {!! Form::open(['url' => '/schedules', 'method' => 'GET']) !!}
                            <div class="col-md-12">

                                <div class="pull-right">
                                    <br>
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>&nbsp; Search</button>

                                </div>

                                <div class="pull-right">&nbsp;</div>

                                <div class="pull-right">
                                    <span>Date: </span> <br>
                                    <input name="monthYear" type="month" class="form-control" value="{{ old('month') }}" required>
                                </div>

                                <div class="pull-right">&nbsp;</div>

                                <div class="pull-right">
                                    <span>Merchandiser: </span> <br>
                                    {!! Form::select('merchandiser_ids[]', $merchandisers->pluck('fullname', 'merchandiser_id'), null, ['class' => 'form-control select2', 'multiple']) !!}
                                </div>


                            </div>
                            {!! Form::close() !!}
                        </div>



                    </div>

                    {{-- Content --}}
                    <div class="box-body">
                        <div class="table-responsive">
                            <div class="table table-responsive">
                                <table class="table table-bordered" style="white-space: nowrap; width: 1%">
                                    <thead>
                                    <th>Merchandiser</th>
                                    @foreach($dates as $date)
                                        <th>{{ Carbon\Carbon::parse($date)->format('M d, Y (D)') }}</th>
                                    @endforeach
                                    </thead>
                                    <tbody>
                                    @foreach($merchandisers as $merchandiser)
                                        <tr>
                                            <td>{{ $merchandiser->fullname }}</td>
                                            @foreach($dates as $date)
                                                <td style="cursor: pointer;" ondblclick="location.href = '{{ url('/schedules/records/' . $merchandiser->merchandiser_id . '/' . Carbon\Carbon::parse($date)->format('Y-m-d')) }}';">
                                                    @foreach($schedules->where('merchandiser_id', $merchandiser->merchandiser_id)
                                                                       ->where('date', $date) as $schedule)

                                                        {{ $schedule->customer_name . ' (' . $schedule->time_in . '-' . $schedule->time_out . ')' }}
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

                    <div class="box-footer">
                        <div class="row">
                            {!! Form::open(['url' => '/schedules/upload', 'method' => 'POST', 'files' => true]) !!}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Date: </label>
                                    <input name="monthYear" type="month" class="form-control" style="width: 200px" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp; </label>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <span class="btn btn-default btn-file">
                                        <span>Import Excel</span>
                                        {!! Form::file('import_file') !!}
                                    </span>
                                    <span class="fileinput-filename"></span><span class="fileinput-new"></span>
                                    <button type="submit" class="btn btn-primary">Upload Excel</button>
                                </div>
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
