@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>
            Customer Master Data
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-user"></i>Customer Master Data</a></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="has-feedback">
                                    <input id="search-text" type="text" class="form-control input-sm" placeholder="Search Customer">
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

                    {{-- Table --}}
                    <div class="box-body">
                        <div id="div-table-customers" class="table-responsive mailbox-messages"></div>
                    </div>

                    <div id="page-nav" class="box-footer"></div>
                    <div id="loading-customers"></div>
                </div>
            </div>

            {{-- Details Box --}}
            <div class="col-md-4">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Customer Details</h3>
                    </div>

                    <div class="box-body">

                        {{-- Form Customer --}}
                        <form id="form-customer">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Customer Code</label>
                                        <input id="customer-code" name="customer_code" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Customer Name</label>
                                        <input id="customer-name" name="customer_name" type="text" class="form-control" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-muted">Branch</label>
                                        <input id="branch" name="branch" type="text" class="form-control" maxlength="255">
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Chain</label>
                                        {!! Form::select('chain', $chains, null, ['class' => 'form-control select2', 'placeholder' => 'Select Chain', 'id' => 'chain']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Municipality</label>
                                        {!! Form::select('municipality', $municipalities, null, ['class' => 'form-control select2', 'placeholder' => 'Select Municipality', 'id' => 'municipality']) !!}
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
                                <button id="btn-add" class="btn btn-primary btn-sm">Add Customer</button>
                                <button id="btn-update" class="btn btn-primary btn-sm" style="display:none">Update Customer</button>
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
        let customers;
        let totalCustomers = 0;
        let customerUrl = '/customer-all';
        let customerSaveUrl = '/customers/save';
        let customerUpdateUrl = '/customers/update';
        let selectedId;

        //searching
        $('#search-text').keyup(function () {
            clearTimeout(timeoutId);
            searchText = $('#search-text').val();
            timeoutId = setTimeout(function() {
                fetchCustomers(customerUrl);
            }, 750)
        });

        //show all
        $('#btn-show-all').click(function () {
            paginate = totalCustomers;
            fetchCustomers(customerUrl);
        });

        //fetch customers
        function fetchCustomers(url){
            showLoading('loading-customers', true);
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
                    customers = data.data;

                    let tbody = '';
                    totalCustomers = data.total;

                    //body
                    for (let customer of customers){
                        tbody +=
                            '<tr>' +
                                '<td>' + '<a href="#" onclick="fillDetails(' + customer.customer_id + ')">' + customer.customer_code  + '</a>' +
                                '<td>' + customer.name + ' - ' + customer.branch +
                                '<td>' + customer.address +
                                '<td>' + customer.chain_description +
                                '<td>' + customer.municipality_description +
                            '</tr>';
                    }

                    //table
                    let table =
                        '<table id="data-table-freeze" style="white-space: nowrap; width: 100%" class="table table-bordered">' +
                        '<thead>' +
                            '<th>Customer Code' +
                            '<th>Name' +
                            '<th>Address' +
                            '<th>Chain' +
                            '<th>Municipality' +
                        '</thead>' +
                        '<tbody>' + tbody +
                        '</tbody>' +
                        '</table>';

                    $('#div-table-customers').html(table);
                    setDataTable(2);
                    /*--------------------------------------------------------------------------*/

                    /*------------------------------ paging ------------------------------------*/
                    let prevButton = '<button class="btn btn-default btn-sm" onclick="fetchCustomers(\'' + data.prev_page_url + '\')"><i class="fa fa-arrow-left"></i></button>';
                    let nextButton = '<button class="btn btn-default btn-sm" onclick="fetchCustomers(\'' + data.next_page_url + '\')"><i class="fa fa-arrow-right"></i></button>';
                    $('#page-nav').html(showPageNavigation(data, prevButton, nextButton));
                    /*--------------------------------------------------------------------------*/
                    showLoading('loading-customers', false);
                }
            });
        }

        //page load
        function onLoad(){
            fetchCustomers(customerUrl);
        }

        /* Customer Details Script  ******************************************************************/
        //add button
        $('#btn-add').click(function () {
            showLoading('loading-details', true);

            let datas = new FormData(document.getElementById('form-customer'));

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
                    $('#response-details').html(showSuccessAlert('Customer has been added.'));
                    showLoading('loading-details', false);
                    fetchCustomers(customerUrl);
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

            let datas = new FormData(document.getElementById('form-customer'));
            datas.append('customer_id', selectedId);

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
                    $('#response-details').html(showSuccessAlert('Customer has been updated.'));
                    showLoading('loading-details', false);
                    fetchCustomers(customerUrl);
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
            $('#form-customer').trigger("reset");
            $('#chain').trigger('change');
            $('#municipality').trigger('change');
        }

        //fill-up form details once table id is clicked
        function fillDetails(id) {
            setEvent('update');

            let customer = alasql("SELECT * FROM ? WHERE customer_id = " + id + "", [customers])[0];
            selectedId = customer.customer_id;
            $('#customer-code').val(customer.customer_code);
            $('#customer-name').val(customer.name);
            $('#branch').val(customer.branch);
            $('#address').val(customer.address);
            $('#chain').val(customer.chain_code).trigger('change');
            $('#municipality').val(customer.municipality_code).trigger('change');
        }
        /* *******************************************************************************************/


        onLoad();
    </script>
@endsection