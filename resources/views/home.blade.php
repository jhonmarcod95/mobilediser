@extends('layouts.app')
@section('content')



    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            {{--<small>Today Status</small>--}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ $inventoryCount }}</h3>

                        <p>Today's Inventory</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-pricetags"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $announcementCount }}</h3>
                        <p>Today's Announcement</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-speakerphone"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $msgCount }}</h3>

                        <p>Today's Message</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-chatbox"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ $scheduleCount }}</h3>

                        <p>Today's Schedule</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-calendar"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->


        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">

                <!-- Announcements box -->
                <div class="box box-success">
                    <div class="box-header">
                        <i class="ion ion-speakerphone"></i>

                        <h3 class="box-title">Announcements</h3>

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

                    @if(count($announcements))
                    <div class="box-body chat" id="chat-box">
                        @foreach($announcements as $announcement)
                        <div class="item">
                            <img src="{{ asset('adminLTE/dist/img/face-0.jpg') }}" alt="user image">

                            <p class="message">
                                <a href="#" class="name">
                                    <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> {{ Carbon\Carbon::parse($announcement->created_at)->format('h:i a') }}</small>
                                    {{ $announcement->first_name . ' ' . $announcement->last_name }}
                                </a>
                               <?php echo nl2br($announcement->message) ?>
                            </p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>


                <!-- Offtake Per Customer -->
                @if(count($chainOfftakes))
                <div class="nav-tabs-custom">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Offtake Per Customer Group</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <div class="table-responsive">
                                    <table id="tblGroup" class="table no-margin table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>Chain</th>
                                            <th>Material</th>
                                            <th>Offtake</th>
                                            <th>Ending balance</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($chainOfftakes as $chainOfftake)
                                                <tr>
                                                    <td>{{ $chainOfftake->description }}</td>
                                                    <td>{{ $chainOfftake->material_description }}</td>
                                                    <td>{{ $chainOfftake->offtake }}</td>
                                                    <td>{{ $chainOfftake->ending_balance }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <a href="#" class="small-box-footer">More Details <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </section>
            <!-- /.Left col -->

            <section class="col-lg-5 connectedSortable">

                <!-- TABLE: Merchandisers Message Headers -->
                @if(count($msgHeaders))
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Today's Message</h3>
                        <div class="text-muted">{{  \Carbon\Carbon::now()->format('Y-m-d') }}</div>
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
                                    <th>ID</th>
                                    <th>Subject</th>
                                    <th>Name</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($msgHeaders as $msgHeader)
                                <tr>
                                    <td><a href="pages/examples/invoice.html">{{ $msgHeader->message_id }}</a></td>
                                    <td>{{ $msgHeader->subject }}</td>
                                    <td>{{ $msgHeader->first_name . ' ' . $msgHeader->last_name }}</td>
                                    <td><small class="text-muted"><i class="fa fa-clock-o"></i> {{ Carbon\Carbon::parse($msgHeader->created_at)->format('h:i a') }}</small></td>
                                    <td><small class="label {{  $msgHeader->bootstrap_class }}">{{  $msgHeader->status }}</small></td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>

                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                @endif

                {{--<!-- Off Take Per Catagory -->--}}
                {{--<div class="box box-info">--}}
                    {{--<div class="box-header with-border">--}}
                        {{--<h3 class="box-title">Off Take Per Category</h3>--}}

                        {{--<div class="box-tools pull-right">--}}
                            {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                            {{--</button>--}}
                            {{--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="box-body">--}}
                        {{--<div class="chart">--}}

                            {{--<div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>--}}
                            {{--<script>--}}
                                {{--Highcharts.chart('container', {--}}
                                    {{--chart: {--}}
                                        {{--plotBackgroundColor: null,--}}
                                        {{--plotBorderWidth: null,--}}
                                        {{--plotShadow: false,--}}
                                        {{--type: 'pie'--}}
                                    {{--},--}}
                                    {{--title: {--}}
                                        {{--text: 'Off Take Per Category'--}}
                                    {{--},--}}
                                    {{--subtitle: {--}}
                                        {{--text: 'Date: June 11, 2018 <br> Total: 4500'--}}
                                    {{--},--}}
                                    {{--tooltip: { enabled: false },--}}
                                    {{--plotOptions: {--}}
                                        {{--pie: {--}}
                                            {{--allowPointSelect: true,--}}
                                            {{--cursor: 'pointer',--}}
                                            {{--dataLabels: {--}}
                                                {{--enabled: true,--}}
                                                {{--format: '<b>{point.name}</b>: {point.percentage:.1f} %',--}}
                                                {{--style: {--}}
                                                    {{--color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'--}}
                                                {{--}--}}
                                            {{--}--}}
                                        {{--}--}}
                                    {{--},--}}
                                    {{--series: [{--}}
                                        {{--name: 'Items',--}}
                                        {{--colorByPoint: true,--}}
                                        {{--data: [{--}}
                                            {{--name: 'Amigo Segurado Pasta',--}}
                                            {{--y: 1500--}}
                                        {{--}, {--}}
                                            {{--name: 'Amigo Segurado Spaghetti Sauce',--}}
                                            {{--y: 700--}}
                                        {{--}, {--}}
                                            {{--name: 'Frying mix',--}}
                                            {{--y: 200--}}
                                        {{--}, {--}}
                                            {{--name: 'La Filipina Canned Meats (Classic)',--}}
                                            {{--y: 500--}}

                                        {{--}, {--}}
                                            {{--name: 'La Filipina Canned Meats (Flavored)',--}}
                                            {{--y: 800--}}
                                        {{--}, {--}}
                                            {{--name: 'La Filipina Pasta',--}}
                                            {{--y: 1000--}}
                                        {{--}, {--}}
                                            {{--name: 'Rice Corn',--}}
                                            {{--y: 150--}}
                                        {{--}, {--}}
                                            {{--name: 'PROMO PACK',--}}
                                            {{--y: 100--}}
                                        {{--}]--}}
                                    {{--}]--}}
                                {{--});--}}
                            {{--</script>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<!-- /.box-body -->--}}
                {{--</div>--}}
                {{--<!-- /.box -->--}}

            </section>
        </div>
    </section>


    <script>
        $(document).ready(function() {
            $('#tblGroup').DataTable({
                'rowsGroup': [0]
            });
        });
    </script>
@endsection
