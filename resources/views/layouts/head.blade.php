<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}</title>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Favicon -->
<link href="{{ url('img/pliliLogo.png') }}" rel="icon" type="image/png">

<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/Ionicons/css/ionicons.min.css') }}">


<!-- DataTables -->
<link rel="stylesheet" href="{{  asset('adminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{  asset('customDataTable/fixedColumns.bootstrap4.min.css') }}">

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

<!-- Google Font -->
<link rel="stylesheet" href="{{  asset('adminLTE/css.css') }}">

{{-- Boostrap tree --}}
<link rel="stylesheet" href="{{  asset('bstree/bootstrap-treeview.css') }}">


{{-- modal custom size --}}
<style>
    .modal-xl {
        width: 94%;
    }

</style>



{{-- Image Upload preview --}}
<script src="{{  asset('adminLTE/jquery.min.js') }}"></script>

{{-- Laravel SweetAlert --}}
<script src="{{  asset('adminLTE/sweetalert2.all.js') }}"></script>




@yield('headScript')

