<header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>LT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">MobileDiser</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">


                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle">
                        <i class="fa fa-user"></i>
                        <span class="no-icon">{{ Auth::user()->first_name }}</span>
                    </a>

                </li>

                <li class="dropdown user user-menu">
                    <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }}" >
                        <i class="fa fa-sign-out"></i>
                        <span class="no-icon" >LOGOUT</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>

                </li>

            </ul>
        </div>

    </nav>

</header>