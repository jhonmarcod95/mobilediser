@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Messages
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Messages</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="has-feedback">
                                    <input id="search-text" type="text" class="form-control input-sm" placeholder="Search Message">
                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped">
                                <thead>
                                <th>Subject</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th></th>
                                </thead>
                                <tbody id="tbody-messages">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="page-nav" class="box-footer no-padding"></div>
                    <div id="loading-message"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal --}}
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg direct-chat direct-chat-warning">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="box-header">
                        <h3 id="chat_title" class="box-title">Direct Chat</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" onclick="document.getElementById('msgFrame').contentWindow.location.reload();"><i class="fa fa-refresh"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-dismiss="modal"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <iframe id="msgFrame" height="350px" width="100%" src="" frameBorder="0" scrolling="no"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>

    var timeoutId = 0;
    var searchText = '';
    var messageUrl = '/messages-all';

    //searching
    $('#search-text').keyup(function () {
        clearTimeout(timeoutId);
        searchText = $('#search-text').val();
        timeoutId = setTimeout(function() {
            getMessages(messageUrl);
        }, 750)
    });

    function getMessages(url){
        showLoading('loading-message', true);
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            contentType: 'application/json',
            data: {
                search: searchText
            },
            success: function(data){
                /*--------------------------- table content ---------------------------------*/
                var messages = data.data;
                var tbody = '';
                $.each(messages, function(key, val) {
                    var fontState = '';
                    if(val.seen_by_receiver === 'no') fontState = 'style="font-weight: bold"';
                    tbody +=
                        '<tr ' + fontState + '>' +
                            '<td><a href="#" onclick="document.getElementById(\'msgFrame\').src = \'' + 'http://' + window.location.hostname + '/message/chat/' + val.message_id + '\'" data-toggle="modal" data-target="#modal-default">' + val.subject +
                            '<td class="mailbox-name">' + val.first_name + ' ' + val.last_name +
                            '<td class="mailbox-subject"><small class="label ' + val.bootstrap_class + '">' + val.status +
                            '<td class="mailbox-date"><small class="text-muted"><i class="fa fa-clock-o"></i> ' + moment(val.created_at).format('MMM DD, YYYY hh:mm a'); +
                        '</tr>';
                });
                $('#tbody-messages').html(tbody);
                /*--------------------------------------------------------------------------*/

                /*------------------------------ paging ------------------------------------*/
                $('#page-nav').html(showPageNavigation(data));
                /*--------------------------------------------------------------------------*/
                showLoading('loading-message', false);
            }
        });
    }

    function onLoad(){
        getMessages(messageUrl);
    }

    onLoad();
</script>
@endsection