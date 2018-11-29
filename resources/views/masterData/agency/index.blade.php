@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Agency Master Data
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-user"></i>Agency Master Data</a></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            {{-- Table --}}
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="has-feedback">
                                    <input id="search-text" type="text" class="form-control input-sm" placeholder="Search Agency">
                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="has-feedback">
                                    <button id="btn-show-all" class="btn btn-primary btn-sm pull-right">Show All</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div-table-agencies" class="table-responsive mailbox-messages"></div>
                    </div>
                    <div id="page-nav" class="box-footer"></div>
                    <div id="loading-agencies"></div>
                </div>
            </div>

            {{-- Details Box --}}
            <div class="col-md-4">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Agency Details</h3>
                    </div>

                    {{-- Form Agency --}}
                    <div class="box-body">
                        <form id="form-agency">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Agecy Name</label>
                                        <input id="agency-name" name="name" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Address</label>
                                        <textarea id="address" name="address" class="form-control" maxlength="255" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="text-muted">Contact #</label>
                                        <input id="contact-number" name="contact_number" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="text-muted">Contact Person</label>
                                        <input id="contact-person" name="contact_person" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div id="response-details" class="col-md-12">
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button id="btn-add" class="btn btn-primary btn-sm">Add Agency</button>
                                <button id="btn-update" class="btn btn-primary btn-sm" style="display:none">Update Agency</button>
                                <button id="btn-cancel" class="btn btn-warning btn-sm pull-right" style="display: none">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div id="loading-details"></div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>

        let timeoutId = 0;
        let searchText;
        let paginate = '20';
        let agencies;
        let totalAgency = 0;
        let agencyUrl = '/agency-all';
        let agencySaveUrl = '/agencies/save';
        let agencyUpdateUrl = '/agencies/update';
        let selectedId;

        //searching
        $('#search-text').keyup(function () {
            clearTimeout(timeoutId);
            searchText = $('#search-text').val();
            timeoutId = setTimeout(function() {
                fetchAgencies(agencyUrl);
            }, 750)
        });

        //show all
        $('#btn-show-all').click(function () {
            paginate = totalAgency;
            fetchAgencies(agencyUrl);
        });

        //fetch agencies
        function fetchAgencies(url){
            showLoading('loading-agencies', true);
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                contentType: 'application/json',
                data: {
                    search: searchText,
                    paginate: paginate
                },
                success: function(data){
                    /*--------------------------- table content ---------------------------------*/
                    agencies = data.data;

                    let tbody = '';
                    totalAgency = data.total;

                    //body
                    for (let agency of agencies){
                        tbody +=
                        '<tr>' +
                            '<td>' + '<a href="#" onclick="fillDetails(' + agency.agency_code + ')">' + agency.agency_code  + '</a>' +
                            '<td>' + agency.name +
                            '<td>' + agency.address +
                            '<td>' + agency.contact_number +
                            '<td>' + agency.contact_person +
                            '<td>' + moment(agency.created_at).format('YYYY-MM-DD hh:mm A') +
                        '</tr>';
                    }

                    //table
                    let table =
                        '<table id="data-table-freeze" style="white-space: nowrap; width: 100%" class="table table-bordered">' +
                        '<thead>' +
                            '<th>Agency Code' +
                            '<th>Name' +
                            '<th>Address' +
                            '<th>Contact #' +
                            '<th>Contact Person' +
                            '<th>Created At' +
                        '</thead>' +
                        '<tbody>' + tbody +
                        '</tbody>' +
                        '</table>';

                    $('#div-table-agencies').html(table);
                    setDataTable(2);
                    /*--------------------------------------------------------------------------*/

                    /*------------------------------ paging ------------------------------------*/
                    let prevButton = '<button class="btn btn-default btn-sm" onclick="fetchAgencies(\'' + data.prev_page_url + '\')"><i class="fa fa-arrow-left"></i></button>';
                    let nextButton = '<button class="btn btn-default btn-sm" onclick="fetchAgencies(\'' + data.next_page_url + '\')"><i class="fa fa-arrow-right"></i></button>';
                    $('#page-nav').html(showPageNavigation(data, prevButton, nextButton));
                    /*--------------------------------------------------------------------------*/
                    showLoading('loading-agencies', false);
                }
            });
        }

        //page load
        function onLoad(){
            fetchAgencies(agencyUrl);
        }

        /* Agency Details Script  **********************************************************************/
        //add button
        $('#btn-add').click(function () {
            showLoading('loading-details', true);

            let datas = new FormData(document.getElementById('form-agency'));

            $.ajax({
                type: 'POST',
                url: agencySaveUrl,
                data: datas,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#btn-cancel").click();
                    $('#response-details').html(showSuccessAlert('Agency has been added.'));
                    showLoading('loading-details', false);
                    fetchAgencies(agencyUrl);
                },
                error: function(data){
                    $("#response-details").html(showErrorAlert(data));
                    showLoading('loading-details', false);
                },
            });
        });

        //update button
        $('#btn-update').click(function () {
            showLoading('loading-details', true);

            let datas = new FormData(document.getElementById('form-agency'));
            datas.append('agency_code', selectedId);

            $.ajax({
                type: 'POST',
                url: agencyUpdateUrl,
                data: datas,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#btn-cancel").click();
                    $('#response-details').html(showSuccessAlert('Agency has been updated.'));
                    showLoading('loading-details', false);
                    fetchAgencies(agencyUrl);
                },
                error: function(data){
                    $("#response-details").html(showErrorAlert(data));
                    showLoading('loading-details', false);
                },
            });
        });

        //cancel button
        $('#btn-cancel').click(function () {
            setEvent('save');
            $("#response-details").html("");
        });

        //set if add or update
        function setEvent(event){
            resetDetails();
            if(event == 'save'){
                $('#btn-cancel').css('display','none');
                $('#btn-update').css('display','none');
                $('#btn-add').css('display','block');
            }
            else{
                $('#btn-cancel').css('display','');
                $('#btn-update').css('display','');
                $('#btn-add').css('display','none');
            }
        }

        //clear form inputs
        function resetDetails() {
            //reset form inputs
            $('#form-agency').trigger("reset");
        }

        //fill-up form details once table id is clicked
        function fillDetails(id) {
            setEvent('update');

            let agency = alasql("SELECT * FROM ? WHERE agency_code = " + id + "", [agencies])[0];
            selectedId = agency.agency_code;
            $('#agency-name').val(agency.name);
            $('#address').val(agency.address);
            $('#contact-number').val(agency.contact_number);
            $('#contact-person').val(agency.contact_person);
        }
        /* *******************************************************************************************/


        onLoad();
    </script>
@endsection