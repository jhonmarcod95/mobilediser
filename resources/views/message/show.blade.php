@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Messages
            {{--<small>13 new messages</small>--}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Messages</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Inbox</h3>

                        <div class="box-tools pull-right">
                            <div class="has-feedback">
                                <input type="text" class="form-control input-sm" placeholder="Search Message">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-controls">

                            <a href="{{ url('/message') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></a>
                            {{--<div class="pull-right">--}}
                                {{--1-50/200--}}
                                {{--<div class="btn-group">--}}
                                    {{--<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>--}}
                                    {{--<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>--}}
                                {{--</div>--}}
                                {{--<!-- /.btn-group -->--}}
                            {{--</div>--}}
                            <!-- /.pull-right -->
                        </div>

                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <th>Msg ID</th>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                @foreach($messages as $message)
                                <tr
                                        @if($message->seen_by_receiver == 'no')
                                        style="font-weight: bold"
                                        @endif
                                >
                                    <td>{{ $message->message_id }}</td>
                                    <td class="mailbox-name"><a href="#" onclick="document.getElementById('msgFrame').src = '{{ url('/message/chat/' . $message->message_id) }}'; document.getElementById('chat_title').innerHTML = '{{ $message->subject }}'" data-toggle="modal" data-target="#modal-default">{{ $message->first_name . ' ' . $message->last_name }}</a></td>
                                    <td class="mailbox-subject"><b>{{ $message->subject }}</b></td>
                                    <td class="mailbox-subject"><small class="label {{  $message->bootstrap_class }}">{{  $message->status }}</small></td>
                                    <td class="mailbox-date">{{ Carbon\Carbon::parse($message->created_at)->format('m/d/Y h:i a') }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <!-- /.table -->
                        </div>
                        <!-- /.mail-box-messages -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-padding">
                        <div class="mailbox-controls">
                            <!-- /.btn-group -->
                            <a href="{{ url('/message') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></a>
                            {{--<div class="pull-right">--}}
                                {{--1-50/200--}}
                                {{--<div class="btn-group">--}}
                                    {{--<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>--}}
                                    {{--<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>--}}
                                {{--</div>--}}
                                {{--<!-- /.btn-group -->--}}
                            {{--</div>--}}
                            <!-- /.pull-right -->
                        </div>
                    </div>
                </div>
                <!-- /. box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        @include('message.modal')
    </section>
    <!-- /.content -->

@endsection