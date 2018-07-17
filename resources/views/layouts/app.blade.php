<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('layouts.head')
</head>

<body class="hold-transition skin-blue-light fixed sidebar-mini">

@include('sweetalert::alert')

<div class="wrapper">

    @include('layouts.nav')
    @include('layouts.sidebar')

    <div id="app" class="content-wrapper">
        @yield('content')
    </div>

    @yield('vue')
    @include('layouts.footer')
</div>
@include('layouts.script')
</body>
</html>
