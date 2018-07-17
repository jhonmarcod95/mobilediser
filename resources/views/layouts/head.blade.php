<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}</title>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/Ionicons/css/ionicons.min.css') }}">
{{--<!-- fullCalendar -->--}}
{{--<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/fullcalendar/dist/fullcalendar.min.css') }}">--}}
{{--<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/fullcalendar/dist/fullcalendar.print.min.css') }}" media="print">--}}
<!-- DataTables -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{  asset('adminLTE/dist/css/skins/_all-skins.min.css') }}">
<!-- Morris chart -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/morris.js/morris.css') }}">
<!-- jvectormap -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/jvectormap/jquery-jvectormap.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{  asset('adminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/select2/dist/css/select2.min.css') }}">

<!-- Theme style -->
<link rel="stylesheet" href="{{  asset('adminLTE/dist/css/AdminLTE.min.css') }}">


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


{{-- External Libraries --}}

{{-- Image Upload preview --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

{{-- Laravel SweetAlert --}}
<script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>

{{-- High Cahrts --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>


{{-- VUE--}}
<script src="https://unpkg.com/vue@2.1.6/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>


{{-- MY SCRIPT --}}
<script>
    function hideElement(id){
        $('#' + id).hide();
    }

    function showElement(id){
        $('#' + id).show();
    }

    function setElementValue(id, val) {
        $('#' + id).val(val);
    }

</script>


@yield('headScript')

