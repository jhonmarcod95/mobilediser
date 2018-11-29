@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Customer Account Master Data
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-user"></i>Customer Account Master Data</a></li>
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
                                    <input id="search-text" type="text" class="form-control input-sm" placeholder="Search Account">
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
                        <div id="div-table-customer-account" class="table-responsive mailbox-messages"></div>
                    </div>
                    <div id="page-nav" class="box-footer"></div>
                    <div id="loading-customer-account"></div>
                </div>
            </div>

            {{-- Details Box --}}
            <div class="col-md-4">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Customer Account Details</h3>
                    </div>

                    {{-- Form Agency --}}
                    <div class="box-body">
                        <form id="form-customer-account">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-muted">Account Code</label>
                                        <input id="account-code" name="account_code" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Description</label>
                                        <input id="description" name="description" type="text" class="form-control" maxlength="255">
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
                                <button id="btn-add" class="btn btn-primary btn-sm">Add Customer Account</button>
                                <button id="btn-update" class="btn btn-primary btn-sm" style="display:none">Update Customer Account</button>
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
        let customerAccounts;
        let totalCustomerAccount = 0;
        let customerAccountUrl = '/customer-account-all';
        let customerSaveUrl = '/customer-accounts/save';
        let customerUpdateUrl = '/customer-accounts/update';
        let selectedId;

        //searching
        $('#search-text').keyup(function () {
            clearTimeout(timeoutId);
            searchText = $('#search-text').val();
            timeoutId = setTimeout(function() {
                fetchCustomerAccounts(customerAccountUrl);
            }, 750)
        });

        //show all
        $('#btn-show-all').click(function () {
            paginate = totalCustomerAccount;
            fetchCustomerAccounts(customerAccountUrl);
        });

        //fetch customerAccounts
        function fetchCustomerAccounts(url){
            showLoading('loading-customer-account', true);
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
                    customerAccounts = data.data;

                    let tbody = '';
                    totalCustomerAccount = data.total;

                    //body
                    for (let customerAccount of customerAccounts){
                        tbody +=
                            '<tr>' +
                                '<td>' + '<a href="#" onclick="fillDetails(' + customerAccount.id + ')">' + customerAccount.account_code  + '</a>' +
                                '<td>' + customerAccount.description +
                            '</tr>';
                    }

                    //table
                    let table =
                        '<table id="data-table-freeze" style="white-space: nowrap; width: 100%" class="table table-bordered">' +
                        '<thead>' +
                            '<th>Account Code' +
                            '<th>Description' +
                        '</thead>' +
                        '<tbody>' + tbody +
                        '</tbody>' +
                        '</table>';

                    $('#div-table-customer-account').html(table);
                    setDataTable(2);
                    /*--------------------------------------------------------------------------*/

                    /*------------------------------ paging ------------------------------------*/
                    let prevButton = '<button class="btn btn-default btn-sm" onclick="fetchCustomerAccounts(\'' + data.prev_page_url + '\')"><i class="fa fa-arrow-left"></i></button>';
                    let nextButton = '<button class="btn btn-default btn-sm" onclick="fetchCustomerAccounts(\'' + data.next_page_url + '\')"><i class="fa fa-arrow-right"></i></button>';
                    $('#page-nav').html(showPageNavigation(data, prevButton, nextButton));
                    /*--------------------------------------------------------------------------*/
                    showLoading('loading-customer-account', false);
                }
            });
        }

        //page load
        function onLoad(){
            fetchCustomerAccounts(customerAccountUrl);
        }

        /* Agency Details Script  **********************************************************************/
        //add button
        $('#btn-add').click(function () {
            showLoading('loading-details', true);

            let datas = new FormData(document.getElementById('form-customer-account'));

            $.ajax({
                type: 'POST',
                url: customerSaveUrl,
                data: datas,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#btn-cancel").click();
                    $('#response-details').html(showSuccessAlert('Customer account has been added.'));
                    showLoading('loading-details', false);
                    fetchCustomerAccounts(customerAccountUrl);
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

            let datas = new FormData(document.getElementById('form-customer-account'));
            datas.append('id', selectedId);

            $.ajax({
                type: 'POST',
                url: customerUpdateUrl,
                data: datas,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#btn-cancel").click();
                    $('#response-details').html(showSuccessAlert('Customer account has been updated.'));
                    showLoading('loading-details', false);
                    fetchCustomerAccounts(customerAccountUrl);
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
            $('#form-customer-account').trigger("reset");
        }

        //fill-up form details once table id is clicked
        function fillDetails(id) {
            setEvent('update');

            let customerAccount = alasql("SELECT * FROM ? WHERE id = " + id + "", [customerAccounts])[0];
            selectedId = customerAccount.id;
            $('#account-code').val(customerAccount.account_code);
            $('#description').val(customerAccount.description);
        }
        /* *******************************************************************************************/


        onLoad();
    </script>
@endsection