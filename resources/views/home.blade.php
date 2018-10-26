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

            {{-- Offtake --}}
            <div class="col-lg-3 col-xs-6">
                <div class="box box-solid small-box bg-red">
                    <div class="inner">
                        <h3 id="offtake-count">0</h3>
                        <p>Offtake</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-cart"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    <div id="loading-4"></div>
                </div>
            </div>
        </div>

        <!-- Main row -->
        <div class="row">
            <section class="col-lg-7 connectedSortable">
                <div class="box box-success">

                    <div class="box-header">
                        <i class="ion ion-speakerphone"></i>
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


            <section class="col-lg-5 connectedSortable">
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
                    </div>
                </div>
                @endif


            </section>
        </div>
    </section>
@endsection

@section('script')
<script>

    getInStore();
    getVisitedStore();
    getInventory();
    getOfftake();

    function getInStore() {
        showLoading('loading-1', true);
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
        showLoading('loading-2', true);
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
        showLoading('loading-3', true);
        $.ajax({
            type: 'GET',
            url: '/getInventory',
            success: function(data){
                $('#submitted-inventory-count').text(data.length);
                showLoading('loading-3', false);
            }
        });
    }

    function getOfftake(){
        showLoading('loading-4', true);
        $.ajax({
            type: 'GET',
            url: '/getOfftake',
            success: function(data){
                $('#offtake-count').text(data.length);
                showLoading('loading-4', false);
            }
        });
    }
</script>



@endsection