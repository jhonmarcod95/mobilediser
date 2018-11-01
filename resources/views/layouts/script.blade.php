<!-- Scripts -->
<!-- ./wrapper -->
{{-- High Cahrts --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<script src="{{ asset('/calendar/lib/moment.min.js') }}"></script>

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

<!-- Slimscroll -->
<script src="{{  asset('adminLTE/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{  asset('adminLTE/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{  asset('adminLTE/dist/js/adminlte.min.js') }}"></script>

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

    function toTimeString(time){
        return  moment('1995-12-30 ' + time).format('hh:mm a');
    }

    function showPageNavigation(data){
        var current_page = data.current_page;
        var last_page = data.last_page;
        var next_page_url = data.next_page_url;
        var prev_page_url = data.prev_page_url;
        var prev_page_button = '';
        var next_page_button = '';
        if(prev_page_url != null) prev_page_button = '<button class="btn btn-default btn-sm" onclick="getMessages(\'' + prev_page_url + '\')"><i class="fa fa-arrow-left"></i></button>';
        if(next_page_url != null) next_page_button = '<button class="btn btn-default btn-sm" onclick="getMessages(\'' + next_page_url + '\')"><i class="fa fa-arrow-right"></i></button>';

        var page_nav = '' +
            '<div class="mailbox-controls pull-right">' +
                prev_page_button +
                '<span> Page ' + current_page + ' </span>' +
                '<span> of ' + last_page + ' </span>' +
                next_page_button +
            '</div>';
        return page_nav;
    }
</script>





@yield('select2Script')
@yield('script')
