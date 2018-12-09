<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('storage/' . Auth::user()->userImage->image_path) }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->first_name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>

            <li>
                <a href="{{ url('/') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ url('/announcements') }}">
                    <i class="ion ion-speakerphone"></i> <span>Announcements</span>
                </a>
            </li>

            <li>
                <a href="{{ url('/messages') }}">
                    <i class="fa fa-envelope"></i> <span>Messages</span>
                    <span class="pull-right-container">
                    {{--<small class="label pull-right bg-yellow">12</small>--}}
                    {{--<small class="label pull-right bg-green">16</small>--}}
                    {{--<small class="label pull-right bg-red">5</small>--}}
                    </span>
                </a>
            </li>




            <li>
                <a href="{{ url('/schedules') }}">
                    <i class="fa fa-calendar"></i> <span>Schedules</span>
                    <span class="pull-right-container">
                    {{--<small class="label pull-right bg-yellow">12</small>--}}
                        {{--<small class="label pull-right bg-green">16</small>--}}
                        {{--<small class="label pull-right bg-red">5</small>--}}
                    </span>
                </a>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-database"></i> <span>Master Data</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('/users') }}"><i class="fa fa-circle"></i> Users</a></li>
                    <li><a href="{{ url('/agencies') }}"><i class="fa fa-circle"></i> Agency</a></li>
                    <li><a href="{{ url('/customers') }}"><i class="fa fa-circle"></i> Customer</a></li>
                    <li><a href="{{ url('/chains') }}"><i class="fa fa-circle"></i> Chain</a></li>
                    <li><a href="{{ url('/customer-accounts') }}"><i class="fa fa-circle"></i> Customer Account</a></li>
                    <li><a href="{{ url('/customer-carried') }}"><i class="fa fa-circle"></i> Customer Carried Materials</a></li>
                    <li><a href="{{ url('/municipalities') }}"><i class="fa fa-circle"></i> Municipalities</a></li>
                    <li><a href="{{ url('/materials') }}"><i class="fa fa-circle"></i> Material</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ url('/locations/geofences') }}">
                    <i class="fa fa-map-marker"></i> <span>Locations</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bar-chart"></i> <span>Reports</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('/reports/merchandiserLog') }}"><i class="fa fa-circle"></i> Merchandiser Logs</a></li>
                    <li><a href="{{ url('/reports/merchandiserAttendance') }}"><i class="fa fa-circle"></i> Merchandiser Attendance</a></li>
                    <li><a href="{{ url('/reports/merchandiserPerformance') }}"><i class="fa fa-circle"></i> Merchandiser Log Summary</a></li>
                    <li><a href="{{ url('/reports/inventoryLog') }}"><i class="fa fa-circle"></i> Inventory Logs</a></li>
                    <li><a href="{{ url('/reports/offtakePerCustomer') }}"><i class="fa fa-circle"></i> Offtake Per Customer</a></li>
                    {{--<li><a href="{{ url('/offtake') }}"><i class="fa fa-circle"></i> Offtake</a></li>--}}
                </ul>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>