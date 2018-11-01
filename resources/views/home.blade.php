@extends('layouts.app')
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Today's Report</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            {{-- In Store --}}
            <div class="col-lg-3 col-xs-6">
                <div class="box box-solid small-box bg-aqua">
                    <div class="inner">
                        <h3 id="in-store-count">0</h3>
                        <p>In Store</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-people"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    <div id="loading-1"></div>
                </div>
            </div>

            {{-- Visited Store --}}
            <div class="col-lg-3 col-xs-6">
                <div class="box box-solid small-box bg-yellow">
                    <div class="inner">
                        <h3 id="visited-store-count">0</h3>
                        <p>Visited Store</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-list"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    <div id="loading-2"></div>
                </div>
            </div>

            {{-- Submitted Inventory --}}
            <div class="col-lg-3 col-xs-6">
                <div class="box box-solid small-box bg-green">
                    <div class="inner">
                        <h3 id="submitted-inventory-count">0</h3>
                        <p>Submitted Inventory</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-pricetags"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    <div id="loading-3"></div>
                </div>
            </div>

            {{-- Schedules --}}
            <div class="col-lg-3 col-xs-6">
                <div class="box box-solid small-box bg-red">
                    <div class="inner">
                        <h3 id="schedule-count">0</h3>
                        <p>Schedules</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-calendar"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    <div id="loading-4"></div>
                </div>
            </div>
        </div>

        <!-- Main row -->
        <div class="row">


            {{-- Announcement --}}
            <section class="col-lg-7 connectedSortable">
                <div class="box box-success">

                    <div class="box-header">

                        <h3 class="box-title">Announcements</h3>
                        {!! Form::open(['url' => '/announcement/post', 'method' => 'POST']) !!}
                        <div class="box-body">
                            <div class="input-group-btn">
                                <textarea name="message" rows="4" class="form-control" placeholder="What's on your mind?" required></textarea>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-default pull-right"><i class="fa fa-send"></i> &nbsp; Post</button>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    @if(count($announcements))
                    <div class="box-body chat" id="chat-box">
                        @foreach($announcements as $announcement)
                        <div class="item">
                            <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="user image">

                            <p class="message">
                                <a href="#" class="name">
                                    <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> {{ Carbon::parse($announcement->created_at)->format('h:i a') }}</small>
                                    {{ $announcement->first_name . ' ' . $announcement->last_name }}
                                </a>
                               <?php echo nl2br($announcement->message) ?>
                            </p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </section>

            {{-- Messages --}}
            <section class="col-lg-5 connectedSortable">
                @if(count($msgHeaders))
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Messages</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Name</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($msgHeaders as $msgHeader)
                                <tr>
                                    <td>{{ $msgHeader->subject }}</td>
                                    <td>{{ $msgHeader->first_name . ' ' . $msgHeader->last_name }}</td>
                                    <td><small class="text-muted"><i class="fa fa-clock-o"></i> {{ Carbon::parse($msgHeader->created_at)->format('h:i a') }}</small></td>
                                    <td><small class="label {{  $msgHeader->bootstrap_class }}">{{  $msgHeader->status }}</small></td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </section>

            {{-- Recently Login --}}
            <section class="col-lg-7 connectedSortable">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Recently Login</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Store</th>
                                    <th>Date & Time</th>
                                </tr>
                                </thead>
                                <tbody id="tbody-recently-login">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="box-footer">

                    </div>
                    <div id="loading-recent"></div>
                </div>
            </section>

            {{-- Schedule Summary --}}
            {{--<section class="col-lg-5 connectedSortable">--}}
                {{--<div class="box box-primary">--}}
                    {{--<div class="box-header">--}}
                        {{--<h3 class="box-title">Schedule Summary</h3>--}}
                    {{--</div>--}}

                    {{--<div class="box-body">--}}
                        {{--<div class="table-responsive">--}}
                            {{--<table class="table no-margin">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th>Name</th>--}}
                                    {{--<th>Store</th>--}}
                                    {{--<th>Date & Time</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody id="tbody-schedule-summary">--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="box-footer">--}}

                    {{--</div>--}}
                    {{--<div id="loading-schedule"></div>--}}
                {{--</div>--}}
            {{--</section>--}}


            {{-- Offtake --}}
            {{--<section class="col-lg-12 connectedSortable">--}}
                {{--<div class="box">--}}
                    {{--<div class="box-header with-border">--}}
                        {{--<h3 class="box-title">Monthly Recap Report</h3>--}}

                        {{--<div class="box-tools pull-right">--}}
                            {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                            {{--</button>--}}
                            {{--<div class="btn-group">--}}
                                {{--<button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">--}}
                                    {{--<i class="fa fa-wrench"></i></button>--}}
                                {{--<ul class="dropdown-menu" role="menu">--}}
                                    {{--<li><a href="#">Action</a></li>--}}
                                    {{--<li><a href="#">Another action</a></li>--}}
                                    {{--<li><a href="#">Something else here</a></li>--}}
                                    {{--<li class="divider"></li>--}}
                                    {{--<li><a href="#">Separated link</a></li>--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                            {{--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<!-- /.box-header -->--}}
                    {{--<div class="box-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-8">--}}
                                {{--<p class="text-center">--}}
                                    {{--<strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>--}}
                                {{--</p>--}}

                                {{--<div class="chart">--}}
                                    {{--<!-- Sales Chart Canvas -->--}}
                                    {{--<canvas id="salesChart" style="height: 180px; width: 816px;" width="1020" height="225"></canvas>--}}
                                {{--</div>--}}
                                {{--<!-- /.chart-responsive -->--}}
                            {{--</div>--}}
                            {{--<!-- /.col -->--}}
                            {{--<div class="col-md-4">--}}
                                {{--<p class="text-center">--}}
                                    {{--<strong>Goal Completion</strong>--}}
                                {{--</p>--}}

                                {{--<div class="progress-group">--}}
                                    {{--<span class="progress-text">Add Products to Cart</span>--}}
                                    {{--<span class="progress-number"><b>160</b>/200</span>--}}

                                    {{--<div class="progress sm">--}}
                                        {{--<div class="progress-bar progress-bar-aqua" style="width: 80%"></div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<!-- /.progress-group -->--}}
                                {{--<div class="progress-group">--}}
                                    {{--<span class="progress-text">Complete Purchase</span>--}}
                                    {{--<span class="progress-number"><b>310</b>/400</span>--}}

                                    {{--<div class="progress sm">--}}
                                        {{--<div class="progress-bar progress-bar-red" style="width: 80%"></div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<!-- /.progress-group -->--}}
                                {{--<div class="progress-group">--}}
                                    {{--<span class="progress-text">Visit Premium Page</span>--}}
                                    {{--<span class="progress-number"><b>480</b>/800</span>--}}

                                    {{--<div class="progress sm">--}}
                                        {{--<div class="progress-bar progress-bar-green" style="width: 80%"></div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<!-- /.progress-group -->--}}
                                {{--<div class="progress-group">--}}
                                    {{--<span class="progress-text">Send Inquiries</span>--}}
                                    {{--<span class="progress-number"><b>250</b>/500</span>--}}

                                    {{--<div class="progress sm">--}}
                                        {{--<div class="progress-bar progress-bar-yellow" style="width: 80%"></div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<!-- /.progress-group -->--}}
                            {{--</div>--}}
                            {{--<!-- /.col -->--}}
                        {{--</div>--}}
                        {{--<!-- /.row -->--}}
                    {{--</div>--}}
                    {{--<!-- ./box-body -->--}}
                    {{--<div class="box-footer">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-sm-3 col-xs-6">--}}
                                {{--<div class="description-block border-right">--}}
                                    {{--<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>--}}
                                    {{--<h5 class="description-header">$35,210.43</h5>--}}
                                    {{--<span class="description-text">TOTAL REVENUE</span>--}}
                                {{--</div>--}}
                                {{--<!-- /.description-block -->--}}
                            {{--</div>--}}
                            {{--<!-- /.col -->--}}
                            {{--<div class="col-sm-3 col-xs-6">--}}
                                {{--<div class="description-block border-right">--}}
                                    {{--<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>--}}
                                    {{--<h5 class="description-header">$10,390.90</h5>--}}
                                    {{--<span class="description-text">TOTAL COST</span>--}}
                                {{--</div>--}}
                                {{--<!-- /.description-block -->--}}
                            {{--</div>--}}
                            {{--<!-- /.col -->--}}
                            {{--<div class="col-sm-3 col-xs-6">--}}
                                {{--<div class="description-block border-right">--}}
                                    {{--<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>--}}
                                    {{--<h5 class="description-header">$24,813.53</h5>--}}
                                    {{--<span class="description-text">TOTAL PROFIT</span>--}}
                                {{--</div>--}}
                                {{--<!-- /.description-block -->--}}
                            {{--</div>--}}
                            {{--<!-- /.col -->--}}
                            {{--<div class="col-sm-3 col-xs-6">--}}
                                {{--<div class="description-block">--}}
                                    {{--<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>--}}
                                    {{--<h5 class="description-header">1200</h5>--}}
                                    {{--<span class="description-text">GOAL COMPLETIONS</span>--}}
                                {{--</div>--}}
                                {{--<!-- /.description-block -->--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<!-- /.row -->--}}
                    {{--</div>--}}
                    {{--<!-- /.box-footer -->--}}
                {{--</div>--}}
            {{--</section>--}}
        </div>
    </section>
@endsection


@section('script')<script>
    var loadingState = true;
    var refreshInterval = 60000; //1min

    function getInStore() {
        showLoading('loading-1', loadingState);
        $.ajax({
            type: 'GET',
            url: '/getInStore',
            success: function(data){
                $('#in-store-count').text(data.length);
                showLoading('loading-1', false);
            }
        });
    }

    function getVisitedStore(){
        showLoading('loading-2', loadingState);
        $.ajax({
            type: 'GET',
            url: '/getVisitedStore',
            success: function(data){
                $('#visited-store-count').text(data.length);
                showLoading('loading-2', false);
            }
        });
    }

    function getInventory(){
        showLoading('loading-3', loadingState);
        $.ajax({
            type: 'GET',
            url: '/getInventory',
            success: function(data){
                $('#submitted-inventory-count').text(data.length);
                showLoading('loading-3', false);
            }
        });
    }

    function getSchedule(){
        showLoading('loading-4', loadingState);
        $.ajax({
            type: 'GET',
            url: '/getSchedule',
            success: function(data){
                $('#schedule-count').text(data.length);
                showLoading('loading-4', false);
            }
        });
    }

    function getRecentlyLogin(){
        showLoading('loading-recent', loadingState);
        $.ajax({
            type: 'GET',
            url: '/getRecentlyLogin',
            success: function(data){
                //set table body
                var tbody = '';
                $.each(data, function(key, val) {
                    tbody +=
                    '<tr>' +
                        '<td>' + val.first_name + ' ' + val.last_name +
                        '<td>' + val.store + ' - ' + val.branch +
                        '<td><small class="text-muted"><i class="fa fa-clock-o"></i> ' + moment(val.created_at).format('MMM DD, YYYY hh:mm a'); + '</small></td>' +
                    '</tr>';
                });
                //display table body
                $('#tbody-recently-login').html(tbody);
                showLoading('loading-recent', false);
            }
        });
    }

    function getScheduleSummary(){
        showLoading('tbody-schedule-summary', loadingState);
        $.ajax({
            type: 'GET',
            url: '/getScheduleSummary',
            success: function(data){
                console.log(data);
                // $('#schedule-count').text(data.length);
                showLoading('tbody-schedule-summary', false);
            }
        });
    }

    function onLoad(){
        getInStore();
        getVisitedStore();
        getInventory();
        getSchedule();
        getRecentlyLogin();
        getScheduleSummary();
    }

    onLoad();

    setInterval(function() {
        loadingState = false; //to hide loading for auto-refresh
        onLoad();
    }, refreshInterval);

</script>@endsection