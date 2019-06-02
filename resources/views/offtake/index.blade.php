@extends('layouts.app')
@section('content')

    <section class="content-header">
        <h1>
            Offtake Report
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-bar-chart"></i> Report</a></li>
            <li class="active">Inventory Logs</li>
        </ol>
    </section>

    <section class="content">

        {{-- Filter Box --}}
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ">
                    </div>
                    <div class="box-body">

                        {{-- First Filter --}}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Place : </label>
                                    {!! Form::select('place', $places, null, ['class' => 'form-control select2', 'id' => 'place']) !!}
                                </div>
                            </div>

                            <div id="div-place"></div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Customer Account : </label>
                                    {!! Form::select('customer_account', $customerAccounts, null, ['class' => 'form-control select2', 'placeholder' => 'Select value...', 'id' => 'customer-account']) !!}
                                </div>
                            </div>

                            {{-- Chain --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Chain : </label>
                                    <select id="chain" name="chain" class="form-control"></select>
                                </div>
                            </div>

                            {{-- Customer --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Customer : </label>
                                    <select id="customers" name="customers" class="form-control"></select>
                                </div>
                            </div>
                        </div>

                        {{-- Second Filter --}}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Category : </label>
                                    <select id="category" name="category" class="form-control"></select>
                                </div>
                            </div>

                            {{-- SKU --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">SKU : </label>
                                    <select id="materials" name="materials" class="form-control"></select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date From</label>
                                    <input id="date-from" name="date_from" type="date" class="form-control" value="">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date To</label>
                                    <input id="date-to" name="date_to" type="date" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">&nbsp;</label><br>
                                    <button id="btn-filter" class="btn btn-default"><i class="fa fa-filter"></i>Filter</button>
                                </div>
                            </div>

                        </div>

                        {{-- JSON Response --}}
                        <div class="row">
                            <div id="response-details" class="col-md-12">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- Offtake Per Customer Box --}}
        <div class="row">
            <div class="col-md-12">


                <div class="box box-default">
                    <div class="box-header ">
                        <label>Offtake Per Customer </label>
                    </div>
                    <div class="box-body">
                        <!-- Custom Tabs -->
                        <div class="nav-tabs-custom">
                            <ul id="customer-tab" class="nav nav-tabs">
                            </ul>

                        </div>
                        <div id="customer-tab-content">
                        </div>
                    </div>
                    <div id="loading-customer"></div>
                </div>
            </div>
        </div>

        {{-- --}}

    </section>

@endsection

@section('script')
    <script>

        // $('#customer').val('%').trigger('change');
        $('#customer-account').val(null).trigger('change');
        // $('#chain').val('%').trigger('change');


        let chainData;
        let customerData;
        let materialData;
        let islandData;
        let regionData;
        let provinceData;
        let municipalityData;
        let categoryData;
        let accountData;

        let customer_codes;
        let material_codes;
        let date_from;
        let date_to;
        let date_ranges;

        let offtakeData;

        fetchFilterOptions();

        function fetchFilterOptions(){
            $.ajax({
                type: 'GET',
                url: '/offtake-filter',
                success: function(data)
                {
                    chainData = data.chains;
                    customerData = data.customers;
                    materialData = data.materials;
                    islandData = data.islands;
                    regionData = data.regions;
                    provinceData = data.provinces;
                    municipalityData = data.municipalities;
                    categoryData = data.categories;
                    accountData = data.accounts;

                    /* populate category in select2 *************/
                    $("#category").html("");
                    let categories = alasql("SELECT '{\"group_main_code\":\"' + group_main_code + '\", \"group_sub_code\":\"' + group_sub_code + '\"}', (group_main_description + ' ' + group_sub_description) FROM ?", [data.categories]);
                    populateSelect('category', categories);
                    if (categories.length > 0) $("#category").append("<option value='{\"group_main_code\":\"%\", \"group_sub_code\":\"%\"}'>All</option>"); // add `all` option
                    setSelect2Multiple('category');
                    /* ******************************************/
                }
            });
        }

        // customer account events
        $('#customer-account').change(function () {

            let chains = alasql("SELECT chain_code, description, account_code FROM ? WHERE account_code LIKE '" + this.value + "'", [chainData]);

            /* populate chain in select2 **************************/
            $("#chain").html(""); // clear items
            populateSelect('chain', chains);
            if (chains.length > 0) $("#chain").append(optionAll()); // add `all` option
            setSelect2Multiple('chain');
            /* *****************************************************/

        });

        // chain events
        $('#chain').change(function () {

            //get selected values from multi select2
            // let chains = $('#chain').select2('data');
            let chains = $("#chain").val();

            /* populate customer in select2 *************************/
            let customerLength = 0; // use to hide & show option `all`
            $("#customers").html(""); // clear items
            for(let chain of chains){ // add items

                let customers = alasql("SELECT customer_code, (`name` + ' ' + `branch`) FROM ? WHERE chain_code LIKE '" + chain + "'", [customerData]);
                populateSelect('customers', customers);
                customerLength += customers.length;
            }
            if (customerLength > 0) $("#customers").append(optionAll()); // add `all` option
            setSelect2Multiple('customers');
            /* *****************************************************/
        });

        // category events
        $('#category').change(function () {

            let categories = $("#category").val();

            /* populate material in select2 ************************/
            let materialLength = 0;
            $("#materials").html("");
            for (let category of categories){
                let categoryJSON = JSON.parse(category);

                let group_main_code = categoryJSON.group_main_code;
                let group_sub_code = categoryJSON.group_sub_code;

                let materials = alasql("SELECT material_code, material_description FROM ? WHERE main_group LIKE '" + group_main_code + "' AND sub_group LIKE '" + group_sub_code + "'", [materialData]);

                populateSelect('materials', materials);

                materialLength += materials.length;
            }
            if (materialLength > 0) $('#materials').append(optionAll()); // add `all` option
            setSelect2Multiple('materials')
            /* ****************************************************/

        });

        // place event
        $('#place').change(function () {
            let place;
            switch (this.value){
                case '%':
                    place = null;
                    break;
                case 'island':
                    place = '<div class="col-md-2">' +
                        '<div class="form-group">' +
                        '<label class="text-muted">Island : </label>' +
                        '{!! Form::select('island', $islands, null, ['class' => 'form-control select2', 'id' => 'island']) !!}' +
                        '</div>' +
                        '</div>';
                    break;
                case 'region':
                    place = '<div class="col-md-2">' +
                        '<div class="form-group">' +
                        '<label class="text-muted">Region : </label>' +
                        '{!! Form::select('region', $regions, null, ['class' => 'form-control select2', 'multiple', 'id' => 'region']) !!}' +
                        '</div>' +
                        '</div>';
                    break;
                case 'province':
                    place = '<div class="col-md-2">' +
                        '<div class="form-group">' +
                        '<label class="text-muted">Province : </label>' +
                        '{!! Form::select('province', $provinces, null, ['class' => 'form-control select2', 'multiple', 'id' => 'province']) !!}' +
                        '</div>' +
                        '</div>';
                    break;
                case 'municipality':
                    place = '<div class="col-md-2">' +
                        '<div class="form-group">' +
                        '<label class="text-muted">Municipality : </label>' +
                        '{!! Form::select('municipality', $municipalities, null, ['class' => 'form-control select2', 'multiple', 'id' => 'municipality']) !!}' +
                        '</div>' +
                        '</div>';
                    break;
            }

            $('#div-place').html(place);

            if(place != null) setSelect2(this.value, '%');

        });

        // filter event
        $('#btn-filter').click(function () {

            // set parameters from input
            customer_codes = $('#customers').val();
            if (customer_codes.includes('%')){ // selected `all`
                customer_codes = selectValues('customers');
            }

            material_codes = $('#materials').val();
            if (material_codes.includes('%')){ // selected `all`
                material_codes = selectValues('materials');
            }

            date_from = $('#date-from').val();
            date_to = $('#date-to').val();

            // clear response for every request
            $('#response-details').html('');

            fetchOfftake();
            fetchOfftakeCustomerTab();
        });


        function fetchOfftake(){
            showLoading('loading-customer', true);

            $.ajax({
                type: 'POST',
                url: '/offtake-customer-data',
                data: {
                    customer_codes: customer_codes,
                    // material_codes: material_codes,
                    date_from: date_from,
                    date_to: date_to,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {

                    // return variables
                    date_ranges = data.dates;
                    let transactions = data.transactions;

                    offtakeData = alasql("" +
                        "SELECT " +
                        "transaction.beginning_balance," +
                        "transaction.delivery," +
                        "transaction.warehouse_area," +
                        "transaction.shelves_area," +
                        "transaction.bo_area," +
                        "transaction.rtv," +
                        "transaction.ending_balance," +
                        "transaction.offtake," +
                        "transaction.customer_code," +
                        "transaction.material_code," +
                        "transaction.created_at" +
                        " " +
                        "FROM ? AS transaction " +
                        "JOIN ? AS customer ON transaction.customer_code = customer.customer_code " +
                        "JOIN ? AS chain ON chain.chain_code = customer.chain_code " +
                        "JOIN ? AS account ON account.account_code = chain.account_code " +
                        "JOIN ? AS municipality ON municipality.municipality_code = customer.municipality_code " +
                        "JOIN ? AS province ON province.provincial_code = municipality.provincial_code " +
                        "JOIN ? AS region ON region.region_code = province.region_code " +
                        "JOIN ? AS island ON island.island_group_code = region.island_group_code " +
                        "ORDER BY " +
                        "transaction.material_code, " +
                        "transaction.created_at"
                        , [transactions, customerData, chainData, accountData, municipalityData, provinceData, regionData, islandData]);

                    showLoading('loading-customer', false);
                },
                error: function (data) {
                    $('#response-details').html(showErrorAlert(data));
                    showLoading('loading-customer', false);
                }

            });
        }

        function fetchOfftakeCustomerTab() {
            let customerTabHtml = '';

            for(let customer_code of customer_codes){

                let customer_name = alasql("SELECT (name + ' ' + branch) AS customer_name FROM ? WHERE customer_code = '" + customer_code + "'", [customerData])[0].customer_name;

                // generate tab headers
                customerTabHtml += "<li class=\"\"><a href=\"#" + customer_code + "\" data-toggle=\"tab\" aria-expanded=\"false\" onclick=\"fetchOfftakeCustomerContent('" + customer_code + "')\">" + customer_name + "</a></li>";

            }

            $("#customer-tab").html(customerTabHtml);
        }

        function fetchOfftakeCustomerContent(customer_code) {

            /* generate column headers **************/
            let column_header_date = '';
            let column_header_inventory = '';
            for (let date of date_ranges){
                column_header_date += '<th colspan="8" style="text-align: center">' + moment(date).format('MMM DD, YYYY (ddd)');
                column_header_inventory +=
                    '<th>Beginning Balance' +
                    '<th>Delivery' +
                    '<th>Warehouse' +
                    '<th>Shelves' +
                    '<th>BO Area' +
                    '<th>Return' +
                    '<th>Ending Balance' +
                    '<th>Offtake';
            }
            /* ***************************************/

            /* generate inventory content ************/
            let inventory_content = '';
            let inventories = alasql("SELECT * FROM ? WHERE customer_code = '" + customer_code + "'", [offtakeData]);
            let inventory_dates = date_ranges;

            let inventory_column = '';
            console.log(date_ranges);
            loop1:
            for (let inventory of inventories){


                let created_at = moment(inventory.created_at).format('YYYY-MM-DD');

                loop2:
                for (let inventory_date of inventory_dates){

                    if(inventory_date === created_at){
                        inventory_column +=
                            '<td>' + inventory.material_code +
                            '<td>' + inventory.material_code +
                            '<td>' + inventory.material_code +
                            '<td>' + inventory.beginning_balance +
                            '<td>' + inventory.delivery +
                            '<td>' + inventory.warehouse_area +
                            '<td>' + inventory.shelves_area +
                            '<td>' + inventory.bo_area +
                            '<td>' + inventory.rtv +
                            '<td>' + inventory.ending_balance +
                            '<td>' + inventory.offtake +
                            '';

                        removeA(inventory_dates, inventory_date);
                        break loop2;
                    }
                    else{
                        inventory_column +=
                            '<td>' +
                            '<td>' +
                            '<td>' +
                            '<td>' +
                            '<td>' +
                            '<td>' +
                            '<td>' +
                            '<td>' +
                            '<td>' +
                            '<td>' +
                            '<td>' +
                            '';
                    }




                }
                // console.log(inventory_dates);



                if(created_at === '2019-05-15'){

                    inventory_content += '<tr>' + inventory_column + '</tr>';
                    inventory_column = '';
                    inventory_dates = [
                        '2019-05-01',
                        '2019-05-02',
                        '2019-05-03',
                        '2019-05-04',
                        '2019-05-05',
                        '2019-05-06',
                        '2019-05-07',
                        '2019-05-08',
                        '2019-05-09',
                        '2019-05-10',
                        '2019-05-11',
                        '2019-05-12',
                        '2019-05-13',
                        '2019-05-14',
                        '2019-05-15',
                    ];

                    console.log(inventory_dates);
                }

            }

            /* generate table ************************/
            let tableOfftakeHtml =
                '<div class="table-responsive">' +
                    '<table class="table table-bordered" style="white-space: nowrap; width: 100%">' +
                    '<thead>' +
                        '<tr>' +
                            '<th colspan="3" style="text-align: center">Production Information' +
                                column_header_date +
                        '</tr>' +
                        '<tr>' +
                            '<th>Material Code' +
                            '<th>Material Description' +
                            '<th>Base UOM' +
                            column_header_inventory +
                        '</tr>' +
                    '</thead>' +
                    '<tbody>' +
                '<tr>' +
                        inventory_content +
                    '</tr>' +
                '</tbody>' +
                    '</table>' +
                '</div>';

            $("#customer-tab-content").html(tableOfftakeHtml);
            /* **************************************/

        }

        function removeA(arr) {
            var what, a = arguments, L = a.length, ax;
            while (L > 1 && arr.length) {
                what = a[--L];
                while ((ax= arr.indexOf(what)) !== -1) {
                    arr.splice(ax, 1);
                }
            }
            return arr;
        }

    </script>
@endsection