@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Announcements
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-user"></i>Announcements</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header">
                        <i class="ion ion-speakerphone"></i>
                        <h3 class="box-title">Write an Announcement</h3>

                        {!! Form::open(['url' => '/announcement/add', 'method' => 'POST']) !!}
                        <div class="box-body">
                            <div class="input-group-btn">
                                <textarea name="message" rows="4" class="form-control" placeholder="Type message..." required></textarea>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-default pull-right"><i class="fa fa-send"></i> &nbsp; Post</button>
                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>

                <div class="box">
                    <div class="box-header ">
                        <h3 class="box-title">Announcements List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="table-responsive mailbox-messages">
                            <table id="dataTable" class="table table-hover table-striped" style="width: 100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Message</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($announcements as $announcement)
                                    <tr>
                                        <td>
                                            <div class="btn-group ">
                                                <a href="{{ url('/announcements/edit/' . $announcement->id) }}" ><li class="fa fa-pencil"></li></a>
                                            </div>
                                        </td>
                                        <td style="width: 800px">{{ $announcement->message }}</td>
                                        <td>{{ $announcement->last_name . ' ' . $announcement->first_name }}</td>
                                        <td>{{ Carbon\Carbon::parse($announcement->created_at)->format('m/d/Y h:i a') }}</td>
                                    </tr>
                                @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection

