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
                <a href="{{ url('/message') }}">
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
                    <i class="fa fa-folder"></i> <span>Master Data</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('/users') }}"><i class="fa fa-circle-o"></i> Users</a></li>
                    <li><a href="{{ url('/agencies') }}"><i class="fa fa-circle-o"></i> Agency</a></li>
                    <li><a href="{{ url('/customers') }}"><i class="fa fa-circle-o"></i> Customer</a></li>
                    <li><a href="{{ url('/customers/types') }}"><i class="fa fa-circle-o"></i> Customer Type</a></li>
                    <li><a href="{{ url('/customers/categories') }}"><i class="fa fa-circle-o"></i> Customer Category</a></li>
                    <li><a href="{{ url('/municipalities') }}"><i class="fa fa-circle-o"></i> Municipalities</a></li>
                    <li><a href="{{ url('/materials') }}"><i class="fa fa-circle-o"></i> Material</a></li>
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
                    <li><a href="{{ url('/reports/offtakePerCustomer') }}"><i class="fa fa-circle-o"></i> Offtake Per Customer</a></li>
                </ul>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>