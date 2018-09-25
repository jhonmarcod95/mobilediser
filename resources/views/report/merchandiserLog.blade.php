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
                        {!! Form::open(['url' => '/reports/merchandiserAttendance', 'method' => 'GET']) !!}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Merchandiser</label>
                                    {!! Form::select('merchandiser', $merchandisers, null, ['class' => 'select2 form-control', 'multiple', 'data-placeholder' => 'Select a Merchandiser', 'style' => 'width: 300px']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::close() !!}

                        <div class="table-responsive mailbox-messages">
                            <div class="table table-responsive">
                                <table class="table table-bordered table-striped" style="white-space: nowrap; width: 100%">
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
                                            <td><a href="#" data-toggle="modal" onclick="document.getElementById('merchandiserImage').src = '{{ asset('storage/' . $merchandiser_log->image_path) }}'" data-target="#modal-default">Image</a></td>
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

    <script>
        $(document).ready(function() {
            $('#tblGroup').DataTable({
                'rowsGroup': [0],
                'paging'      : false,
                'lengthChange': false,
                'searching'   : false,
                'ordering'    : false

            });
        });
    </script>
@endsection
