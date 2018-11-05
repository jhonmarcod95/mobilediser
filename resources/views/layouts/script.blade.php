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
            maximumSelectionLength: 10,
            width: '100%'
        });
    });

    /* set's select tag into select2 and its default value ********/
    function setSelect2(id, val) {
        $("#" + id).select2({width: '100%'});
        $("#" + id).select2('val', val);
    }
    /**************************************************************/

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

    function getCheckboxChecked(arrayId) {
        var checked = [];
        $("input[id='" + arrayId+ "']:checked").each(function ()
        {
            checked.push($(this).val());
        });
        return checked;
    }

    //in operator
    function whereIn(varString, varArray){
        var result = false;
        $.each(varArray, function(key, arr) {
            if(arr == varString){
                result = true;
            }
        });
        return result;
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

    function showSuccessAlert(text){
        var result = '<div class="alert bg-success alert-dismissible text-success small" role="alert">' + text + '<button type="button" class="close text-green" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        return result;
    }

    function showErrorAlert(data) {
        var errors = $.parseJSON(data.responseText);
        var errList = '';
        $.each(errors.errors, function (key, val) {
            errList += '<li>' + val + '</li>';
        });
        return '<div class="alert bg-danger text-danger"><button type="button" class="close text-red" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p><i class="fa fa-warning"></i><b style="margin-left: 6px">Error</b> ' + errors.message + '</p><ul class="small">' + errList + '</ul></div>';
    }

    function getDaysOfMonth(monthYear, pDay){
        var days = [];
        var day = moment(monthYear).day(pDay);

        if (day.date() > 7) day.add(7, 'd');
        var month = day.month();

        while (month === day.month()) {
            days.push(day.format('YYYY-MM-DD'));
            day.add(7, 'd');
        }
        return days;
    }

    //for selection with `all` options using multiple select2
    function getSelectMultipleValue(id, allString) {
        var result = [];
        var val = $("#" + id).val()

        if(val.includes(allString)){
            $('#' + id + ' option').each(function()
            {
                var value = $(this).val();
                if(value != allString){
                    result.push(value);
                }
            });
        }
        else{
            result = val;
        }
        return result
    }
</script>





@yield('select2Script')
@yield('script')
