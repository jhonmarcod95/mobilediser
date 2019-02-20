@extends('layouts.app')
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small id="dashboard-date">Today's Report</small>
            <input class="small" id="date-entry" type="date" style="display:none;">
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            {{-- Schedules --}}
            <div class="col-lg-3 col-xs-6">
                <div class="box box-solid small-box bg-aqua">
                    <div class="inner">
                        <h3 id="schedule-count">0</h3>
                        <p>Schedules</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-calendar"></i>
                    </div>
                    <a href="#" data-toggle="modal" data-target="#modal-schedule" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    <div id="loading-4"></div>
                </div>
            </div>

            {{-- Inventory --}}
            <div class="col-lg-3 col-xs-6">
                <div class="box box-solid small-box bg-yellow">
                    <div class="inner">
                        <h3 id="inventory-count">0</h3>
                        <p>Submitted Inventory</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-list"></i>
                    </div>
                    <a href="#" data-toggle="modal" data-target="#modal-inventory" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    <div id="loading-2"></div>
                </div>
            </div>

            {{-- Offtake --}}
            {{--@role(['admin', 'manager'])--}}
            <div class="col-lg-3 col-xs-6">
                <div class="box box-solid small-box bg-green">
                    <div class="inner">
                        <h3 id="offtake-count">0</h3>
                        <p>Offtake</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-pricetags"></i>
                    </div>
                    <a href="#" data-toggle="modal" data-target="#modal-offtake" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    <div id="loading-3"></div>
                </div>
            </div>
            {{--@endrole--}}

            {{-- Near Expiry --}}
            {{--@role(['admin', 'manager'])--}}
            <div class="col-lg-3 col-xs-6">
                <div class="box box-solid small-box bg-red">
                    <div class="inner">
                        <h3 id="near-expiry-count">0</h3>
                        <p>Near Expiry</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-clock-outline"></i>
                    </div>
                    <a href="#" data-toggle="modal" data-target="#modal-near-expiry" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    <div id="loading-1"></div>
                </div>
            </div>
            {{--@endrole--}}
        </div>

        <!-- Main row -->
        <div class="row">

            {{-- Announcement --}}
            @role(['admin', 'manager'])
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
            @endrole

            {{-- Messages --}}
            @role(['admin', 'manager'])
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
            @endrole

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

    {{-- Modal Schedule --}}
    <div class="modal fade" id="modal-schedule">
        <div class="modal-dialog modal-xl direct-chat direct-chat-warning">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="box-header">
                        <h3 id="chat_title" class="box-title">Schedule Dashboard</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-dismiss="modal"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-body" >
                    <div class="box-default">
                        <div class="row">
                            <div class="col-md-5">
                                <div id="schedule-tree"></div>
                            </div>
                            <div class="col-md-7">
                                <div class="table-responsive" style="height: 500px">
                                    <table id="table-schedule" class="table table-bordered no-margin"></table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::button('Export to Excel', ['class' => 'btn btn-primary btn-sm', 'onclick' => 'tableToExcel(\'table-schedule\', \'Schedule\')']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Offtake --}}
    <div class="modal fade" id="modal-offtake">
        <div class="modal-dialog modal-xl direct-chat direct-chat-warning">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="box-header">
                        <h3 id="chat_title" class="box-title">Offtake Dashboard</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-dismiss="modal"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="box-default">
                        <div class="row">
                            <div class="col-md-5">
                                <div id="offtake-tree"></div>
                            </div>
                            <div class="col-md-7">
                                <div class="table-responsive" style="height: 500px">
                                    <table id="table-offtake" style="white-space: nowrap; width: 100%" class="table table-bordered no-margin"></table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::button('Export to Excel', ['class' => 'btn btn-primary btn-sm', 'onclick' => 'tableToExcel(\'table-offtake\', \'Offtake\')']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Inventory --}}
    <div class="modal fade" id="modal-inventory">
        <div class="modal-dialog modal-xl direct-chat direct-chat-warning">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="box-header">
                        <h3 id="chat_title" class="box-title">Submit Inventory Dashboard</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-dismiss="modal"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="box-default">
                        <div class="row">
                            <div class="col-md-5">
                                <div id="inventory-tree"></div>
                            </div>
                            <div class="col-md-7">
                                <div class="table-responsive" style="height: 500px">
                                    <table id="table-inventory" style="white-space: nowrap; width: 100%" class="table table-bordered no-margin"></table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::button('Export to Excel', ['class' => 'btn btn-primary btn-sm', 'onclick' => 'tableToExcel(\'table-inventory\', \'Inventory\')']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Near Expiry --}}
    <div class="modal fade" id="modal-near-expiry">
        <div class="modal-dialog modal-xl direct-chat direct-chat-warning">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="box-header">
                        <h3 id="chat_title" class="box-title">Near Expiry Dashboard</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-dismiss="modal"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="box-default">
                        <div class="row">
                            <div class="col-md-5">
                                <div id="near-expiry-tree"></div>
                            </div>
                            <div class="col-md-7">
                                <div class="table-responsive" style="height: 500px">
                                    <table id="table-near-expiry" style="white-space: nowrap; width: 100%" class="table table-bordered no-margin"></table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::button('Export to Excel', ['class' => 'btn btn-primary btn-sm', 'onclick' => 'tableToExcel(\'table-near-expiry\', \'Near Expiry\')']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')<script>

    var loadingState = true; //use to show & hide loading
    var realTimeState = true; //use to enable & disable real-time
    var refreshInterval = 60000; //1min
    var dateToday = moment().format('Y-M-DD');
    var dateSelected = dateToday;

    // $( "#modal-default" ).on('shown', function(){
    //     alert("I want this to appear after the modal has opened!");
    // });

    /* dashboard filtering ******************************/
    $('#dashboard-date').click(function() {
        $('#dashboard-date').css('display', 'none');
        $('#date-entry')
            .val($('#dashboard-date').text())
            .css('display', '')
            .focus();
    });

    $('#date-entry').change(function() {
        $('#date-entry').css('display', 'none');
        $('#dashboard-date').text($('#date-entry').val()).css('display', '');

        if($('#dashboard-date').text() == dateToday){
            dateSelected =  $('#dashboard-date').text();
            $('#dashboard-date').text('Today\'s Report');
            realTimeState = true; //activate realtime for date today filter
            loadingState = true; //show loading to know if filtering works
        }
        else{
            realTimeState = false; //disable realtime if current day is not today
            dateSelected =  $('#dashboard-date').text();
        }

        onLoad();
    });
    /****************************************************/

    /* inventory dashboard **********************************/
    function getInventory(){
        showLoading('loading-2', loadingState);
        $.ajax({
            type: 'GET',
            url: '/getInventory/' + dateSelected,
            success: function(data){

                let inventories = data;

                let agencies = alasql("SELECT DISTINCT agency_code, agency FROM ?", [inventories]);
                let columns = "`store` + ' - ' + `branch` AS `Store`," +
                    "first_name + ' ' + last_name AS `Merchandiser`," +
                    "created_at AS `Date Submitted`";

                let inventoryAgencyNodes = JSON.parse('[]');
                let inventoryAll = [];
                let remainingAll = [];

                //agency node
                $.each(agencies, function(key, agency) {
                    let agency_code = agency.agency_code;
                    let agency_name = agency.agency;
                    let inventoryAgency = alasql("SELECT " + columns + " FROM ? WHERE agency_code = " + agency_code + " AND transaction_number IS NOT NULL", [inventories]);

                    /* status node *********/
                    let statusNodes = JSON.parse('[]');
                    let remainingAgency =  alasql("SELECT " + columns + " FROM ? WHERE agency_code = " + agency_code + " AND transaction_number IS NULL", [inventories]);
                    statusNodes.push({
                        "text": "Remaining: " + remainingAgency.length,
                        "values": remainingAgency
                    });
                    remainingAll.push.apply(remainingAll, remainingAgency);
                    /* ********************/

                    inventoryAgencyNodes.push({
                        "text": agency_name + ": " + inventoryAgency.length,
                        "nodes": statusNodes,
                        "values": inventoryAgency
                    });
                    inventoryAll.push.apply(inventoryAll, inventoryAgency);
                });

                //total remaining
                inventoryAgencyNodes.push({
                    "text": "Total Remaining: " + remainingAll.length,
                    "values": remainingAll
                });

                //set tree
                var inventoryTree = [{
                    "text": "Total Submitted: " + inventoryAll.length,
                    "nodes": inventoryAgencyNodes,
                    "values": inventoryAll
                }];

                //display schedule treeview
                $('#inventory-tree').treeview({
                    data: inventoryTree,
                    enableLinks: true,

                    //treeview event
                    onNodeSelected: function(event, data) {
                        let filteredInventory = data.values;
                        populateTable('table-inventory', filteredInventory);
                    }
                });

                $('#inventory-count').text(inventoryAll.length);
                $('#inventory-tree').treeview('collapseAll', { silent: true });
                populateTable('table-inventory', inventoryAll);
                showLoading('loading-2', false);
            }
        });
    }
    /********************************************************/

    /* schedule dashboard ***********************************/
    function getSchedule(){
        showLoading('loading-4', loadingState);
        $.ajax({
            type: 'GET',
            url: '/getSchedule/' + dateSelected,
            success: function(data){
                showLoading('loading-4', false);

                var schedules = data;


                // var agencies = alasql("SELECT * FROM ? GROUP BY agency_code", [schedules]);
                var agencies = alasql("SELECT DISTINCT agency_code, agency FROM ?", [schedules]);
                var scheduleAgencyNodes = JSON.parse('[]');
                var userAgencyNodes = JSON.parse('[]');

                // per schedule
                var scheduleAll = [];
                var inStoreAll = [];
                var visitedAll = [];
                var remainingAll = [];
                var schedulesAgency = [];
                var scheduleInStore = [];
                var visitedAgency = [];
                var remainingAgency = [];
                var scheduleColumns = "`merchandiser_id`, `first_name` + ' ' + `last_name` AS `Name`, `store` + ' - ' + `branch` AS `Store`";

                // per user
                var userAll = [];
                var loginAll = [];
                var notLoginAll = [];
                var usersAgency = [];
                var loginAgency = [];
                var notLoginAgency = [];
                var userColumns = "`merchandiser_id`, `first_name` + ' ' + `last_name` AS `Name`";

                // agency node
                $.each(agencies, function(key, agency) {
                    var agency_code = agency.agency_code;
                    var agency_name = agency.agency;

                    /* Per Schedule ******************************************/
                    // filters
                    schedulesAgency = alasql("SELECT " + scheduleColumns + " FROM ? WHERE agency_code = " + agency_code, [schedules]);
                    scheduleInStore =  alasql("SELECT " + scheduleColumns + " FROM ? WHERE agency_code = " + agency_code + " AND (time_in IS NOT NULL AND time_out IS NULL)", [schedules]);
                    visitedAgency =  alasql("SELECT " + scheduleColumns + " FROM ? WHERE agency_code = " + agency_code + " AND status = '001'", [schedules]);
                    remainingAgency = alasql("SELECT " + scheduleColumns + " FROM ? WHERE agency_code = " + agency_code + " AND (time_in IS NULL AND time_out IS NULL)", [schedules]);

                    // console.log(uniqueJsonArray(schedulesAgency, 'Name'));

                    // merge json arrays during push to get totals
                    scheduleAll.push.apply(scheduleAll, schedulesAgency);
                    inStoreAll.push.apply(inStoreAll, scheduleInStore);
                    visitedAll.push.apply(visitedAll, visitedAgency);
                    remainingAll.push.apply(remainingAll, remainingAgency);

                    /* ------- status node -------- */
                    var scheduleStatusNodes = JSON.parse('[]');

                    // instore
                    scheduleStatusNodes.push({
                        "text": "In-Store: " + scheduleInStore.length,
                        "values": scheduleInStore
                    });

                    // visited
                    scheduleStatusNodes.push({
                        "text": "Visited: " + visitedAgency.length,
                        "values": visitedAgency
                    });

                    // remaining
                    scheduleStatusNodes.push({
                        "text": "Remaining: " + remainingAgency.length,
                        "values": remainingAgency
                    });
                    /* --------------------------- */

                    scheduleAgencyNodes.push({
                        "text": agency_name + ": " + schedulesAgency.length,
                        "nodes": scheduleStatusNodes,
                        "values": schedulesAgency
                    });
                    /*********************************************************/

                    /* Per User **********************************************/
                    // filter users per agency
                    // note : use this example below to display a specific column with GROUP BY clause.
                    // note : group by returns undefined or null values (include IS NOT NULL condition to avoid the return value ex: loginAgency)
                    usersAgency = alasql("SELECT " + userColumns + " FROM ? WHERE agency_code = " + agency_code , [schedules]);
                    usersAgency = uniqueJsonArray(usersAgency, 'merchandiser_id');
                    //get all logins
                    let logins = alasql("SELECT " + userColumns + " FROM ? WHERE agency_code = " + agency_code + " AND time_in IS NOT NULL", [schedules]);
                    logins = uniqueJsonArray(logins, 'merchandiser_id');
                    loginAgency = alasql("SELECT * FROM ?", [logins]);
                    //get all user without logout
                    let loginIds = alasql("SELECT merchandiser_id FROM ?", [logins]);
                    notLoginAgency = alasql("SELECT " + userColumns + " FROM ? WHERE agency_code = " + agency_code + " AND merchandiser_id NOT IN (" + objectPluck(loginIds, 'merchandiser_id').join() + ")", [schedules]);
                    notLoginAgency = uniqueJsonArray(notLoginAgency, 'merchandiser_id');

                    // merge json arrays during push to get totals
                    userAll.push.apply(userAll, usersAgency);
                    loginAll.push.apply(loginAll, loginAgency);
                    notLoginAll.push.apply(notLoginAll, notLoginAgency);

                    /* ------- status node -------- */
                    var userStatusNodes = JSON.parse('[]');

                    // login
                    userStatusNodes.push({
                        "text": "Login: " + loginAgency.length,
                        "values": loginAgency
                    });

                    // not login
                    userStatusNodes.push({
                        "text": "Not Login: " + notLoginAgency.length,
                        "values": notLoginAgency
                    });
                    /* ---------------------------f */

                    userAgencyNodes.push({
                        "text": agency_name + ": " + usersAgency.length,
                        "nodes": userStatusNodes,
                        "values": usersAgency
                    });
                    /*********************************************************/
                });

                /* status node for total **************/
                // per schedule
                scheduleAgencyNodes.push({
                    "text": "Total In-Store: " + inStoreAll.length,
                    "values": inStoreAll
                });
                scheduleAgencyNodes.push({
                    "text": "Total Visited: " + visitedAll.length,
                    "values": visitedAll
                });
                scheduleAgencyNodes.push({
                    "text": "Total Remaining: " + remainingAll.length,
                    "values": remainingAll
                });

                // per user
                userAgencyNodes.push({
                    "text": "Total Login: " + loginAll.length,
                    "values": loginAll
                });
                userAgencyNodes.push({
                    "text": "Total Not Login: " + notLoginAll.length,
                    "values": notLoginAll
                });
                /*************************************/

                var scheduleTree = [{
                    "text": "Schedules: " + scheduleAll.length,
                    "nodes": scheduleAgencyNodes,
                    "values": scheduleAll
                },{
                    "text": "Merchandisers: " + userAll.length,
                    "nodes": userAgencyNodes,
                    "values": userAll
                }];

                //display schedule treeview
                $('#schedule-tree').treeview({
                    data: scheduleTree,
                    enableLinks: true,

                    //treeview event
                    onNodeSelected: function(event, data) {
                        // /******* modal table height auto adjust *******/
                        // let treeHeight = $('#schedule-tree').height();
                        // let tableHeight = $('.table-responsive').height();
                        // if(treeHeight > tableHeight) $('.table-responsive').height(treeHeight);
                        // /*********************  **********************/

                        let filteredSchedules = data.values;
                        populateTable('table-schedule', filteredSchedules);
                    }
                });

                $('#schedule-count').text(scheduleAll.length);
                $('#schedule-tree').treeview('collapseAll', { silent: true });
                populateTable('table-schedule', scheduleAll);
            }
        });
    }
    /********************************************************/

    /* offtake dashboard ************************************/
    function getOfftake(){
        showLoading('loading-3', loadingState);
        $.ajax({
            type: 'GET',
            url: '/getOfftake/' + dateSelected,
            success: function(data){

                let offtakes = data;
                let customer_accounts = alasql("SELECT DISTINCT account_code, account_description FROM ?", [offtakes]);
                let columns = '' +
                    'material_code AS `Material Code`, ' +
                    'material_description AS Material, ' +
                    'SUM(ending_balance) AS Ending, ' +
                    'SUM(offtake) AS Offtake, ' +
                    'base_uom AS `UOM`, ' +
                    'group_main_description + \' \' + group_sub_description As Category';

                let group_by = ' GROUP BY ' +
                    'material_code,' +
                    'material_description,' +
                    'base_uom,' +
                    'group_main_code,' +
                    'group_sub_code,' +
                    'group_main_description,' +
                    'group_sub_description';

                let offtakeAll = [];
                let customerAccountNodes = JSON.parse('[]');
                let offtakeAllCount = 0;

                //customer account node
                $.each(customer_accounts, function(key, customer_account) {
                    let account_code = customer_account.account_code;
                    let account_description = customer_account.account_description;
                    let offtakeAccount = alasql("SELECT " + columns + " FROM ? WHERE account_code = '" + account_code + "'" + group_by, [offtakes]);
                    let offtakeAccountCount = objectSum(offtakeAccount, 'Offtake');

                    // customer chain node
                    let chainNodes = JSON.parse('[]');
                    let chains = alasql("SELECT DISTINCT chain_code, chain_description FROM ? WHERE account_code = '" + account_code + "'", [offtakes]);

                    $.each(chains, function(key, chain) {
                        let chain_code = chain.chain_code;
                        let chain_description = chain.chain_description;
                        let offtakeChain = alasql("SELECT " + columns + " FROM ? WHERE account_code = '" + account_code + "' AND chain_code = '" + chain_code + "'" + group_by, [offtakes]);
                        let offtakeChainCount = objectSum(offtakeChain, 'Offtake');

                        // material category node
                        let categoryNodes = JSON.parse('[]');
                        let categories = alasql("SELECT DISTINCT group_main_code, group_main_description, group_sub_code, group_sub_description FROM ? WHERE account_code = '" + account_code + "'", [offtakes]);

                        $.each(categories, function(key, category) {

                            let categoryMainCode = category.group_main_code;
                            let categoryMainDesc = category.group_main_description;
                            let categorySubCode = category.group_sub_code;
                            let categorySubDesc = category.group_sub_description;

                            /* this logic is used to count the null values (or materials without group assigned) during lefjoin */
                            //main group condition
                            if(categoryMainCode != null)
                                categoryMainCode =  '=\'' + categoryMainCode + '\'';
                            else
                                categoryMainCode = 'IS NULL';

                            //sub group condition
                            if(categorySubCode != null)
                                categorySubCode =  '=\'' + categorySubCode + '\'';
                            else
                                categorySubCode = 'IS NULL';

                            //description
                            let materialCategory = categoryMainDesc + ' ' + categorySubDesc;
                            if(categoryMainDesc == null || categorySubDesc == null) materialCategory = 'No Category';
                            /*****************************************************************************************************/

                            let offtakeCategory = alasql("SELECT " + columns + " FROM ? WHERE account_code = '" + account_code + "' AND chain_code = '" + chain_code + "' AND group_main_code " + categoryMainCode + " AND group_sub_code " + categorySubCode + group_by, [offtakes]);
                            let offtakeCategoryCount = objectSum(offtakeCategory, 'Offtake');

                            categoryNodes.push({
                                "text": materialCategory + ": " + offtakeCategoryCount,
                                "values": offtakeCategory
                            });
                        });

                        chainNodes.push({
                            "text": chain_description + ": " + offtakeChainCount,
                            "nodes": categoryNodes,
                            "values": offtakeChain
                        });
                    });

                    customerAccountNodes.push({
                        "text": account_description + ": " + offtakeAccountCount,
                        "nodes": chainNodes,
                        "values": offtakeAccount
                    });

                    offtakeAll.push.apply(offtakeAll, offtakeAccount);
                    offtakeAllCount += parseInt(offtakeAccountCount);
                });


                let offtakeTree = [{
                    "text": "Total Offtake: " + offtakeAllCount,
                    "nodes": customerAccountNodes,
                    "values": offtakeAll
                }];

                //display offtake treeview
                $('#offtake-tree').treeview({
                    data: offtakeTree,
                    enableLinks: true,

                    //treeview event
                    onNodeSelected: function(event, data) {
                        let filteredOfftakes = data.values;
                        populateTable('table-offtake', filteredOfftakes);
                    }
                });

                $('#offtake-count').text(offtakeAllCount);
                populateTable('table-offtake', offtakeAll);
                showLoading('loading-3', false);
            }
        });
    }
    /********************************************************/

    /* near expiry dashboard ********************************/
    function getNearExpiry() {
        showLoading('loading-1', loadingState);
        $.ajax({
            type: 'GET',
            url: '/getNearExpiry/' + dateSelected,
            success: function(data){

                let nearExpirations = data;
                let customer_accounts = alasql("SELECT DISTINCT account_code, account_description FROM ?", [nearExpirations]);
                let columns = '' +
                    'customer_code AS `Customer Code`, ' +
                    'customer_name + \' - \' + branch AS `Store`, ' +
                    'material_code AS `Material Code`, ' +
                    'material_description AS `Material`, ' +
                    'expiration_date AS `Expiration Date`, ' +
                    'base_qty AS `Qty`, ' +
                    'base_uom AS `Base UOM`';

                let nearExpiryAll = [];
                let customerAccountNodes = JSON.parse('[]');
                let nearExpiryAllCount = 0;

                //customer account node
                for (let customer_account of customer_accounts){
                    let account_code = customer_account.account_code;
                    let account_description = customer_account.account_description;
                    let nearExpiryAccount = alasql("SELECT " + columns + " FROM ? WHERE account_code = '" + account_code + "'", [nearExpirations]);
                    let nearExpiryAccountCount = objectSum(nearExpiryAccount, 'Qty');

                    // customer chain node
                    let chainNodes = JSON.parse('[]');
                    let chains = alasql("SELECT DISTINCT chain_code, chain_description FROM ? WHERE account_code = '" + account_code + "'", [nearExpirations]);

                    for(let chain of chains){
                        let chain_code = chain.chain_code;
                        let chain_description = chain.chain_description;
                        let nearExpiryChain = alasql("SELECT " + columns + " FROM ? WHERE account_code = '" + account_code + "' AND chain_code = '" + chain_code + "'", [nearExpirations]);
                        let nearExpiryChainCount = objectSum(nearExpiryChain, 'Qty');

                        // customer node
                        let customerNodes = JSON.parse('[]');
                        let customers = alasql("SELECT DISTINCT customer_code, customer_name, branch FROM ? WHERE account_code = '" + account_code + "' AND chain_code = '" + chain_code + "'", [nearExpirations]);

                        for(let customer of customers) {
                            let customer_code = customer.customer_code;
                            let customer_name = customer.customer_name + ' - ' + customer.branch;

                            let nearExpiryCustomer = alasql("SELECT " + columns + " FROM ? WHERE account_code = '" + account_code + "' AND chain_code = '" + chain_code + "' AND customer_code = '" + customer_code + "'", [nearExpirations]);
                            let nearExpiryCustomerCount = objectSum(nearExpiryCustomer, 'Qty');

                            customerNodes.push({
                                "text": customer_name + ": " + nearExpiryCustomerCount,
                                "values": nearExpiryCustomer
                            });
                        }

                        chainNodes.push({
                            "text": chain_description + ": " + nearExpiryChainCount,
                            "nodes": customerNodes,
                            "values": nearExpiryChain
                        });
                    }

                    customerAccountNodes.push({
                        "text": account_description + ": " + nearExpiryAccountCount,
                        "nodes": chainNodes,
                        "values": nearExpiryAccount
                    });

                    nearExpiryAll.push.apply(nearExpiryAll, nearExpiryAccount);
                    nearExpiryAllCount += parseInt(nearExpiryAccountCount);
                }

                let nearExpiryTree = [{
                    "text": "Total Near Expiry: " + nearExpiryAllCount,
                    "nodes": customerAccountNodes,
                    "values": nearExpiryAll
                }];

                //display near expiry treeview
                $('#near-expiry-tree').treeview({
                    data: nearExpiryTree,
                    enableLinks: true,

                    //treeview event
                    onNodeSelected: function(event, data) {
                        let filteredNearExpiry = data.values;
                        populateTable('table-near-expiry', filteredNearExpiry);
                    }
                });

                $('#near-expiry-count').text(nearExpiryAllCount);
                populateTable('table-near-expiry', nearExpiryAll);
                showLoading('loading-1', false);
            }
        });
    }
    /********************************************************/


    function populateTable(id, data) {
        $('#' + id).html(populateJsonArrayTable(data, true, 'merchandiser_id'));
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


    /* modal states ****************************************/
    $('.modal').on('hide.bs.modal', function (e) {
        realTimeState = true;
    });

    $('.modal').on('shown.bs.modal', function (e) {
        realTimeState = false;
    });
    /*******************************************************/

    function onLoad(){
        getNearExpiry();
        getInventory();
        getOfftake();
        getSchedule();
        getRecentlyLogin();
    }

    onLoad();

    setInterval(function() {
        if(realTimeState){
            loadingState = false; //to hide loading for auto-refresh
            onLoad();
        }
    }, refreshInterval);


</script>@endsection