<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('layouts.head')
</head>


<body class="hold-transition skin-blue-light sidebar-mini" onload="document.getElementById('chat_message').scrollTop = document.getElementById('chat_message').scrollHeight; ">

    <div class="direct-chat direct-chat-warning">
        <!-- Conversations are loaded here -->
        <div id="chat_message" class="direct-chat-messages">

            @foreach($chats as $chat)
                <?php $dateTime = Carbon::parse($chat->created_at)->format('m/d/Y h:i a') ?>

                @if($chat->merchandiser_id == Auth::user()->merchandiser_id)
                    <!-- Message to the right -->
                    <div class="direct-chat-msg right">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">{{ $chat->first_name . ' ' . $chat->last_name }}</span>
                            <span class="direct-chat-timestamp pull-right">{{ $dateTime }}</span>
                        </div>
                        <!-- /.direct-chat-info -->
                        <img class="direct-chat-img" src="{{ asset('storage/' . $chat->image_path) }}" alt="message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                            {{ $chat->message }}
                        </div>
                        <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
                @else
                    <!-- Message. Default to the left -->
                    <div class="direct-chat-msg">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">{{ $chat->first_name . ' ' . $chat->last_name }}</span>
                            <span class="direct-chat-timestamp pull-right">{{ $dateTime }}</span>
                        </div>
                        <!-- /.direct-chat-info -->
                        <img class="direct-chat-img" src="{{ asset('storage/' . $chat->image_path) }}" alt="message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                            {{ $chat->message }}
                        </div>
                        <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
                @endif
            @endforeach

        </div>
        <!--/.direct-chat-messages-->

        <!-- Contacts are loaded here -->
        <div class="direct-chat-contacts">
            <ul class="contacts-list">
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="{{ asset('adminLTE/dist/img/face-0.jpg') }}" alt="User Image">

                        <div class="contacts-list-info">
                                    <span class="contacts-list-name">
                                      Count Dracula
                                      <small class="contacts-list-date pull-right">2/28/2015</small>
                                    </span>
                            <span class="contacts-list-msg">How have you been? I was...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                    </a>
                </li>
                <!-- End Contact Item -->
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="{{ asset('adminLTE/dist/img/face-0.jpg') }}" alt="User Image">

                        <div class="contacts-list-info">
                                    <span class="contacts-list-name">
                                      Sarah Doe
                                      <small class="contacts-list-date pull-right">2/23/2015</small>
                                    </span>
                            <span class="contacts-list-msg">I will be waiting for...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                    </a>
                </li>
                <!-- End Contact Item -->
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="{{ asset('adminLTE/dist/img/face-0.jpg') }}" alt="User Image">

                        <div class="contacts-list-info">
                                    <span class="contacts-list-name">
                                      Nadia Jolie
                                      <small class="contacts-list-date pull-right">2/20/2015</small>
                                    </span>
                            <span class="contacts-list-msg">I'll call you back at...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                    </a>
                </li>
                <!-- End Contact Item -->
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="{{ asset('adminLTE/dist/img/face-0.jpg') }}" alt="User Image">

                        <div class="contacts-list-info">
                                    <span class="contacts-list-name">
                                      Nora S. Vans
                                      <small class="contacts-list-date pull-right">2/10/2015</small>
                                    </span>
                            <span class="contacts-list-msg">Where is your new...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                    </a>
                </li>
                <!-- End Contact Item -->
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="{{ asset('adminLTE/dist/img/face-0.jpg') }}" alt="User Image">

                        <div class="contacts-list-info">
                                    <span class="contacts-list-name">
                                      John K.
                                      <small class="contacts-list-date pull-right">1/27/2015</small>
                                    </span>
                            <span class="contacts-list-msg">Can I take a look at...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                    </a>
                </li>
                <!-- End Contact Item -->
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="{{ asset('adminLTE/dist/img/face-0.jpg') }}" alt="User Image">

                        <div class="contacts-list-info">
                                    <span class="contacts-list-name">
                                      Kenneth M.
                                      <small class="contacts-list-date pull-right">1/4/2015</small>
                                    </span>
                            <span class="contacts-list-msg">Never mind I found...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                    </a>
                </li>
                <!-- End Contact Item -->
            </ul>
            <!-- /.contatcts-list -->
        </div>
        <!-- /.direct-chat-pane -->

        <div class="box-footer">
            {!! Form::open(['url' => '/message/addChat', 'method' => 'POST']) !!}
                <div class="input-group">
                    {!! Form::text('message', '', ['class' => 'form-control', 'placeholder' => 'Type Message ...', 'maxlength' => '255', 'required', 'autofocus']) !!}
                    <span class="input-group-btn">
                        {!! Form::submit('Send', ['class' => 'btn btn-warning btn-flat']) !!}
                    </span>

                </div>
            {!! Form::close() !!}

            <br>
            {!! Form::open(['url' => '/message/closeMessage', 'method' => 'POST', 'target' => '_top']) !!}
                <div class="input-group">
                    <span class="input-group-btn">
                        {!! Form::submit('Close Message', ['class' => 'btn btn-success btn-flat']) !!}
                    </span>
                </div>
            {!! Form::close() !!}
        </div>
    </div>

</body>