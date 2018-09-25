@extends('layouts.app')
@section('content')


    <section class="content-header">
        <h1>
            Merchandiser Logs
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>


    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ">
                        <h3 class="box-title">Log Record</h3>
                    </div>

                    <div class="box-body">
                        {!! Form::open(['url' => '/reports/merchandiserLog', 'method' => 'GET']) !!}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date From</label>
                                    {!! Form::date('date_from', $merchandisers, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date To</label>
                                    {!! Form::date('date_to', $merchandisers, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">&nbsp;</label><br>
                                    {!! Form::Submit('Filter', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}

                        <div class="table-responsive mailbox-messages">
                            <div class="table table-responsive">
                                <table id="dataTable" class="table table-bordered table-striped" style="white-space: nowrap; width: 100%">
                                    <thead>
                                    <th></th>
                                    <th>Merchandiser</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    </thead>
                                    <tbody>
                                    @foreach($merchandiser_logs as $merchandiser_log)
                                        <tr>
                                            <td>
                                                <a href="#" data-toggle="modal" onclick="
                                                        document.getElementById('merchandiserImage').src = '{{ asset('storage/' . $merchandiser_log->image_path) }}';
                                                        document.getElementById('title').innerText = '{{ $merchandiser_log->first_name . ' ' . $merchandiser_log->last_name . ' - ' . $merchandiser_log->customer_name . ' - ' . $merchandiser_log->date . ' - ' .  Carbon::parse($merchandiser_log->time_in)->format('h:i a')}}';
                                                        " data-target="#modal-default">Image
                                                </a>
                                            </td>
                                            <td>{{ $merchandiser_log->first_name . ' ' . $merchandiser_log->last_name }}</td>
                                            <td>{{ $merchandiser_log->customer_name }}</td>
                                            <td>{{ $merchandiser_log->date }}</td>
                                            <td>{{ Carbon::parse($merchandiser_log->time_in)->format('h:i a') }}</td>
                                            <td>
                                                @if($merchandiser_log->time_out != null)
                                                    {{ Carbon::parse($merchandiser_log->time_out)->format('h:i a') }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('report.modal')
    </section>
@endsection
