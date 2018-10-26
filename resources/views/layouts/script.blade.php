<!-- Scripts -->
<!-- ./wrapper -->
{{-- High Cahrts --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>


<!-- jQuery 3 -->
<script src="{{  asset('adminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{  asset('adminLTE/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{  asset('adminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Morris.js charts -->
<script src="{{  asset('adminLTE/bower_components/raphael/raphael.min.js') }}"></script>
<script src="{{  asset('adminLTE/bower_components/morris.js/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{  asset('adminLTE/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{  asset('adminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{  asset('adminLTE/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{  asset('adminLTE/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
{{--<script src="{{  asset('adminLTE/bower_components/moment/min/moment.min.js') }}"></script>--}}
<script src="{{  asset('adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{  asset('adminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{  asset('adminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- Slimscroll -->
<script src="{{  asset('adminLTE/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{  asset('adminLTE/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{  asset('adminLTE/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{  asset('adminLTE/dist/js/pages/dashboard.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{  asset('adminLTE/dist/js/demo.js') }}"></script>


<!-- DataTables -->
<script src="{{  asset('adminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{  asset('adminLTE/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{  asset('adminLTE/bower_components/datatables.net/js/dataTables.rowsGroup.js') }}"></script>
<script src="{{  asset('customDataTable/dataTables.fixedColumns.min.js') }}"></script>

<!-- Select2 -->
<script src="{{  asset('adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

<script>
    $(function () {
        $('#dataTable').DataTable();
        $('#dataTable1').DataTable({
            scrollY:        "400px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            ordering: false,
            searching: false,
            fixedColumns:   {
                leftColumns: 1
            }
        });

        $('.select2').select2({
            maximumSelectionLength: 10
        });
    });

    function setDataTable(freezeIndex){
        $('#dataTable2').DataTable({
            scrollY:        "400px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            ordering: false,
            searching: false,
            fixedColumns:   {
                leftColumns: freezeIndex
            }
        });
    }

    function showLoading(elementId, state) {
        if(state){
            $('#' + elementId).attr('class', 'overlay');
            $('#' + elementId).html("<i class=\"fa fa-refresh fa-spin\"></i>");
        }
        else{
            $('#' + elementId).attr('class', '');
            $('#' + elementId).html("");
        }
    }
</script>

{{--<!-- fullCalendar -->--}}
{{--<script src="{{  asset('adminLTE/bower_components/moment/moment.js') }}"></script>--}}
{{--<script src="{{  asset('adminLTE/bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>--}}
{{--<!-- Page specific script -->--}}

@yield('select2Script')
@yield('script')
