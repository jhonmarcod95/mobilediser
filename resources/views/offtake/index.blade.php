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

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-inline">
                                    <div class="form-group mx-sm-3 mb-2">
                                        <button id="btn-filter-accounts" class="btn btn-default" @click="getOfftake()">Offtake Per Accounts</button>
                                    </div>
                                    <div class="form-group mb-2">
                                        <button id="btn-filter-chain" class="btn btn-default">Offtake Per Chain</button>
                                    </div>
                                    <div class="form-group mx-sm-3 mb-2">
                                        <button id="btn-filter-customer" class="btn btn-default">Offtake Per Customer</button>
                                    </div>
{{--                                    <div class="form-group mx-sm-3 mb-2">--}}
{{--                                        <button id="btn-offtake-summary" class="btn btn-default">Offtake Summary</button>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                        </div>

                        {{-- JSON Response --}}
                        <div class="row">
                            <div id="response-details" class="col-md-12" style="margin-top: 12px">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Offtake --}}
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header ">
                        <label>Offtake Result </label>
                        <div class="row pull-right">
                            <div class="col-md-12">
                                <button onclick="tableToExcel('table-offtake-customer')" class="btn btn-primary btn-sm"><i class="fa fa-file-excel-o"></i>&nbsp;Export Per Product</button>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">

                        {{-- Customer Offtake Table --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered" style="white-space: nowrap; width: 100%">
                                        <thead>
                                        <tr>
                                            <th colspan="2">SKU Description</th>
                                            <th v-for="(filtered_date, fd) in filtered_dates" :v-bind="fd" colspan="8">
                                                @{{ moment(filtered_date).format('MMM DD, YYYY (ddd)') }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Branch</th>
                                            <th>SKU</th>
                                            <template v-for="(filtered_date, fd) in filtered_dates" :v-bind="fd">
                                                <th>Beginning Balance</th>
                                                <th>Delivery</th>
                                                <th>Warehouse</th>
                                                <th>Shelves</th>
                                                <th>BO Area</th>
                                                <th>Return</th>
                                                <th>Ending Balance</th>
                                                <th>Offtake</th>
                                            </template>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <template v-for="(offtakeCustomer, c) in offtakeCustomers" :v-bind="c">
                                            <tr>

                                                <td :rowspan="Object.keys(offtakeCustomer.offtake).length + 1">@{{ offtakeCustomer.customer.name + ' - ' + offtakeCustomer.customer.branch }}</td>
                                            </tr>

                                            <template v-for="(offtake, o) in offtakeCustomer.offtake" :v-bind="o">
                                                <tr>
                                                    <td>@{{ offtake.material.material_description }}</td>



                                                    <template v-for="(date, d) in offtake.dates" :v-bind="d">
                                                        <template v-if="date.length">
                                                            <td>@{{ date[0].beginning_balance }}</td>
                                                            <td>@{{ date[0].delivery }}</td>
                                                            <td>@{{ date[0].warehouse_area }}</td>
                                                            <td>@{{ date[0].shelves_area }}</td>
                                                            <td>@{{ date[0].bo_area }}</td>
                                                            <td>@{{ date[0].rtv }}</td>
                                                            <td>@{{ date[0].ending_balance }}</td>
                                                            <td>@{{ date[0].offtake }}</td>
                                                        </template>

                                                        <template v-else>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </template>
                                                    </template>



                                                </tr>
                                            </template>

                                        </template>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="loading-offtake"></div>
                </div>
            </div>
        </div>


    </section>

@endsection

@push('js')
    <script>
        new Vue({
            el: '#app',
            data: {

                offtakeCustomers: [],
                filtered_dates: [],
                isProcessing: false
            },
            methods: {
                getOfftake(){
                    showLoading('loading-offtake', true);

                    this.filtered_dates = this.getDates(new Date('2021-04-01'), new Date('2021-04-30'));


                    axios.get('/offtake-customer-data')
                        .then(response => {
                            this.offtakeCustomers = response.data;
                            showLoading('loading-offtake', false);
                        });
                },
                getDates : function(startDate, endDate) {
                    var dates = [],
                        currentDate = startDate,
                        addDays = function(days) {
                            var date = new Date(this.valueOf());
                            date.setDate(date.getDate() + days);
                            return date;
                        };
                    while (currentDate <= endDate) {
                        dates.push(currentDate);
                        currentDate = addDays.call(currentDate, 1);
                    }
                    return dates;
                },
                moment: function (val) { // todo:: convert this to mixin
                    return moment(val);
                }


            },
            mounted() {

            }
        })
    </script>





{{--    <script>--}}

{{--        $('#customer-account').val(null).trigger('change');--}}
{{--        // $('#chain').val('%').trigger('change');--}}

{{--        let chainData;--}}
{{--        let customerData;--}}
{{--        let materialData;--}}
{{--        let islandData;--}}
{{--        let regionData;--}}
{{--        let provinceData;--}}
{{--        let municipalityData;--}}
{{--        let categoryData;--}}
{{--        let accountData;--}}

{{--        let customer_codes;--}}
{{--        let chain_codes;--}}
{{--        let date_from;--}}
{{--        let date_to;--}}
{{--        let date_ranges;--}}

{{--        let offtakeData;--}}

{{--        fetchFilterOptions();--}}

{{--        function fetchFilterOptions(){--}}
{{--            $.ajax({--}}
{{--                type: 'GET',--}}
{{--                url: '/offtake-filter',--}}
{{--                success: function(data)--}}
{{--                {--}}
{{--                    chainData = data.chains;--}}
{{--                    customerData = data.customers;--}}
{{--                    materialData = data.materials;--}}
{{--                    islandData = data.islands;--}}
{{--                    regionData = data.regions;--}}
{{--                    provinceData = data.provinces;--}}
{{--                    municipalityData = data.municipalities;--}}
{{--                    categoryData = data.categories;--}}
{{--                    accountData = data.accounts;--}}

{{--                    /* populate category in select2 *************/--}}
{{--                    $("#category").html("");--}}
{{--                    let categories = alasql("SELECT '{\"group_main_code\":\"' + group_main_code + '\", \"group_sub_code\":\"' + group_sub_code + '\"}', (group_main_description + ' ' + group_sub_description) FROM ?", [data.categories]);--}}
{{--                    populateSelect('category', categories);--}}
{{--                    if (categories.length > 0) $("#category").append("<option value='{\"group_main_code\":\"%\", \"group_sub_code\":\"%\"}'>All</option>"); // add `all` option--}}
{{--                    setSelect2Multiple('category');--}}
{{--                    /* ******************************************/--}}
{{--                }--}}
{{--            });--}}
{{--        }--}}

{{--        // customer account events--}}
{{--        $('#customer-account').change(function () {--}}

{{--            let chains = alasql("SELECT chain_code, description, account_code FROM ? WHERE account_code LIKE '" + this.value + "'", [chainData]);--}}

{{--            /* populate chain in select2 **************************/--}}
{{--            $("#chain").html(""); // clear items--}}
{{--            populateSelect('chain', chains);--}}
{{--            if (chains.length > 0) $("#chain").append(optionAll()); // add `all` option--}}
{{--            setSelect2Multiple('chain');--}}
{{--            /* *****************************************************/--}}

{{--        });--}}

{{--        // chain events--}}
{{--        $('#chain').change(function () {--}}

{{--            //get selected values from multi select2--}}
{{--            // let chains = $('#chain').select2('data');--}}
{{--            let chains = $("#chain").val();--}}

{{--            /* populate customer in select2 *************************/--}}
{{--            $("#customers").html(""); // clear items--}}
{{--            for(let chain of chains){ // add items--}}

{{--                let customers = alasql("SELECT customer_code, (`name` + ' ' + `branch`) FROM ? WHERE chain_code LIKE '" + chain + "'", [customerData]);--}}
{{--                populateSelect('customers', customers);--}}
{{--            }--}}
{{--            setSelect2Multiple('customers', 5);--}}
{{--            /* *****************************************************/--}}
{{--        });--}}

{{--        // category events--}}
{{--        $('#category').change(function () {--}}

{{--            let categories = $("#category").val();--}}

{{--            /* populate material in select2 ************************/--}}
{{--            let materialLength = 0;--}}
{{--            $("#materials").html("");--}}
{{--            for (let category of categories){--}}
{{--                let categoryJSON = JSON.parse(category);--}}

{{--                let group_main_code = categoryJSON.group_main_code;--}}
{{--                let group_sub_code = categoryJSON.group_sub_code;--}}

{{--                let materials = alasql("SELECT material_code, material_description FROM ? WHERE main_group LIKE '" + group_main_code + "' AND sub_group LIKE '" + group_sub_code + "'", [materialData]);--}}

{{--                populateSelect('materials', materials);--}}

{{--                materialLength += materials.length;--}}
{{--            }--}}
{{--            if (materialLength > 0) $('#materials').append(optionAll()); // add `all` option--}}
{{--            setSelect2Multiple('materials')--}}
{{--            /* ****************************************************/--}}

{{--        });--}}

{{--        // place event--}}
{{--        $('#place').change(function () {--}}
{{--            let place;--}}
{{--            switch (this.value){--}}
{{--                case '%':--}}
{{--                    place = null;--}}
{{--                    break;--}}
{{--                case 'island':--}}
{{--                    place = '<div class="col-md-2">' +--}}
{{--                        '<div class="form-group">' +--}}
{{--                        '<label class="text-muted">Island : </label>' +--}}
{{--                        '{!! Form::select('island', $islands, null, ['class' => 'fo<tarm-control select2', 'id' => 'island']) !!}' +--}}
{{--                        '</div>' +--}}
{{--                        '</div>';--}}
{{--                    break;--}}
{{--                case 'region':--}}
{{--                    place = '<div class="col-md-2">' +--}}
{{--                        '<div class="form-group">' +--}}
{{--                        '<label class="text-muted">Region : </label>' +--}}
{{--                        '{!! Form::select('region', $regions, null, ['class' => 'form-control select2', 'multiple', 'id' => 'region']) !!}' +--}}
{{--                        '</div>' +--}}
{{--                        '</div>';--}}
{{--                    break;--}}
{{--                case 'province':--}}
{{--                    place = '<div class="col-md-2">' +--}}
{{--                        '<div class="form-group">' +--}}
{{--                        '<label class="text-muted">Province : </label>' +--}}
{{--                        '{!! Form::select('province', $provinces, null, ['class' => 'form-control select2', 'multiple', 'id' => 'province']) !!}' +--}}
{{--                        '</div>' +--}}
{{--                        '</div>';--}}
{{--                    break;--}}
{{--                case 'municipality':--}}
{{--                    place = '<div class="col-md-2">' +--}}
{{--                        '<div class="form-group">' +--}}
{{--                        '<label class="text-muted">Municipality : </label>' +--}}
{{--                        '{!! Form::select('municipality', $municipalities, null, ['class' => 'form-control select2', 'multiple', 'id' => 'municipality']) !!}' +--}}
{{--                        '</div>' +--}}
{{--                        '</div>';--}}
{{--                    break;--}}
{{--            }--}}

{{--            $('#div-place').html(place);--}}

{{--            if(place != null) setSelect2(this.value, '%');--}}
{{--        });--}}

{{--        // filter account--}}
{{--        $('#btn-filter-accounts').click(function () {--}}
{{--            setParameters();--}}
{{--            fetchOfftake(1, 'loading-accounts', 'accounts-tab');--}}
{{--            fetchOfftakeAccountsTab();--}}
{{--            showOfftakeCards('#div-offtake-account');--}}
{{--        });--}}

{{--        // filter chain--}}
{{--        $('#btn-filter-chain').click(function () {--}}
{{--            setParameters();--}}
{{--            fetchOfftake(2, 'loading-chain', 'chain-tab');--}}
{{--            fetchOfftakeChainTab();--}}
{{--            showOfftakeCards('#div-offtake-chain');--}}
{{--        });--}}

{{--        // filter customer--}}
{{--        $('#btn-filter-customer').click(function () {--}}
{{--            setParameters();--}}
{{--            fetchOfftake(3, 'loading-customer', 'customer-tab');--}}
{{--            fetchOfftakeCustomerTab();--}}
{{--            showOfftakeCards('#div-offtake-customer');--}}
{{--        });--}}

{{--        $('#btn-offtake-summary').click(function () {--}}
{{--            setParameters();--}}
{{--            fetchOfftakeSummary();--}}
{{--        });--}}

{{--        function showOfftakeCards(id) {--}}
{{--            $('#div-offtake-account').hide();--}}
{{--            $('#div-offtake-chain').hide();--}}
{{--            $('#div-offtake-customer').hide();--}}
{{--            $(id).show();--}}
{{--        }--}}


{{--        function setParameters() {--}}

{{--            // set parameters from input--}}
{{--            customer_codes = $('#customers').val();--}}
{{--            chain_codes = $('#chain').val();--}}

{{--            if (chain_codes){--}}
{{--                if (chain_codes.includes('%')){ // selected `all`--}}
{{--                    chain_codes = selectValues('chain');--}}
{{--                }--}}
{{--            }--}}

{{--            date_from = $('#date-from').val();--}}
{{--            date_to = $('#date-to').val();--}}

{{--            // clear response for every request--}}
{{--            $('#response-details').html('');--}}
{{--        }--}}

{{--        function locationFilter(){--}}
{{--            let result = '';--}}

{{--            let places = $('#place').val();--}}
{{--            if (places === '%'){--}}
{{--                result = '';--}}
{{--            }--}}
{{--            else{--}}
{{--                result = "WHERE ";--}}

{{--                let islands = $('#island').val();--}}
{{--                let region = $('#region').val();--}}
{{--                let province = $('#province').val();--}}
{{--                let municipality = $('#municipality').val();--}}

{{--                if(islands){--}}
{{--                    result += "island.island_group_code LIKE '" + islands + "'";--}}
{{--                }--}}

{{--                if(region){--}}
{{--                    if (region.includes('%')){ // selected `all`--}}
{{--                        region = selectValues('region');--}}
{{--                    }--}}
{{--                    region = arrayToSingleQuotes(region);--}}
{{--                    result += "region.region_code IN (" + region + ")";--}}
{{--                }--}}

{{--                if(province){--}}
{{--                    if (province.includes('%')){ // selected `all`--}}
{{--                        province = selectValues('province');--}}
{{--                    }--}}
{{--                    province = arrayToSingleQuotes(province);--}}
{{--                    result += "province.provincial_code IN (" + province + ")";--}}
{{--                }--}}

{{--                if(municipality){--}}
{{--                    if (municipality.includes('%')){ // selected `all`--}}
{{--                        municipality = selectValues('municipality');--}}
{{--                    }--}}
{{--                    municipality = arrayToSingleQuotes(municipality);--}}
{{--                    result += "municipality.municipality_code IN (" + municipality + ")";--}}
{{--                }--}}
{{--            }--}}

{{--            return result;--}}
{{--        }--}}

{{--        function fetchOfftake(report_type, loading_id, tab_header){--}}
{{--            let filters = locationFilter();--}}

{{--            showLoading(loading_id, true);--}}

{{--            $.ajax({--}}
{{--                type: 'GET',--}}
{{--                url: '/offtake-customer-data',--}}
{{--                data: {--}}
{{--                    customer_account: $('#customer-account').val(),--}}
{{--                    chain_codes: chain_codes,--}}
{{--                    customer_codes: customer_codes,--}}
{{--                    report_type: report_type,--}}
{{--                    date_from: date_from,--}}
{{--                    date_to: date_to,--}}
{{--                    _token: '{{ csrf_token() }}'--}}
{{--                },--}}
{{--                success: function(data) {--}}

{{--                    console.log(data);--}}

{{--                    // return variables--}}
{{--                    date_ranges = data.dates;--}}
{{--                    let transactions = data.transactions;--}}

{{--                    offtakeData = alasql("" +--}}
{{--                        "SELECT " +--}}
{{--                        "transaction.beginning_balance," +--}}
{{--                        "transaction.delivery," +--}}
{{--                        "transaction.warehouse_area," +--}}
{{--                        "transaction.shelves_area," +--}}
{{--                        "transaction.bo_area," +--}}
{{--                        "transaction.rtv," +--}}
{{--                        "transaction.ending_balance," +--}}
{{--                        "transaction.offtake," +--}}
{{--                        "municipality.municipality_code," +--}}
{{--                        "province.provincial_code," +--}}
{{--                        "region.region_code," +--}}
{{--                        "island.island_group_code," +--}}
{{--                        "transaction.customer_code," +--}}
{{--                        "account.account_code," +--}}
{{--                        "chain.chain_code," +--}}
{{--                        "transaction.material_code," +--}}
{{--                        "material.material_description," +--}}
{{--                        "material.base_unit," +--}}
{{--                        "material.main_group," +--}}
{{--                        "material.sub_group," +--}}
{{--                        "transaction.created_at" +--}}
{{--                        " " +--}}
{{--                        "FROM ? AS transaction " +--}}
{{--                        "JOIN ? AS customer ON transaction.customer_code = customer.customer_code " +--}}
{{--                        "JOIN ? AS chain ON chain.chain_code = customer.chain_code " +--}}
{{--                        "JOIN ? AS account ON account.account_code = chain.account_code " +--}}
{{--                        "JOIN ? AS municipality ON municipality.municipality_code = customer.municipality_code " +--}}
{{--                        "JOIN ? AS province ON province.provincial_code = municipality.provincial_code " +--}}
{{--                        "JOIN ? AS region ON region.region_code = province.region_code " +--}}
{{--                        "JOIN ? AS island ON island.island_group_code = region.island_group_code " +--}}
{{--                        "JOIN ? AS material ON material.material_code = transaction.material_code " +--}}
{{--                        filters +--}}
{{--                        "ORDER BY " +--}}
{{--                        "transaction.material_code, " +--}}
{{--                        "transaction.created_at"--}}
{{--                        , [transactions, customerData, chainData, accountData, municipalityData, provinceData, regionData, islandData, materialData]);--}}

{{--                    $('#' + tab_header + ' a:first-child').tab('show').trigger('click');--}}

{{--                    showLoading(loading_id, false);--}}
{{--                },--}}
{{--                error: function (data) {--}}
{{--                    console.log(data);--}}

{{--                    $('#response-details').html(showErrorAlert(data));--}}
{{--                    showLoading(loading_id, false);--}}
{{--                }--}}
{{--            });--}}
{{--        }--}}

{{--        function fetchOfftakeSummary(){--}}
{{--            $.ajax({--}}
{{--                type: 'GET',--}}
{{--                url: '/offtake-summary-data',--}}
{{--                data: {--}}
{{--                    customer_codes: customer_codes,--}}
{{--                    date_from: date_from,--}}
{{--                    date_to: date_to,--}}
{{--                },--}}
{{--                success: function(data) {--}}

{{--                },--}}
{{--                error: function (data) {--}}
{{--                    $('#response-details').html(showErrorAlert(data));--}}
{{--                }--}}
{{--            });--}}
{{--        }--}}

{{--        /*  Customer Offtake Filter *******************/--}}
{{--        function fetchOfftakeCustomerTab() {--}}
{{--            let customerTabHtml = '';--}}

{{--            for(let customer_code of customer_codes){--}}

{{--                let customer_name = alasql("SELECT (name + ' ' + branch) AS customer_name FROM ? WHERE customer_code = '" + customer_code + "'", [customerData])[0].customer_name;--}}

{{--                // generate tab headers--}}
{{--                customerTabHtml += "<li class=\"\"><a href=\"#" + customer_code + "\" data-toggle=\"tab\" aria-expanded=\"false\" onclick=\"fetchOfftakeCustomerContent('" + customer_code + "')\">" + customer_name + "</a></li>";--}}
{{--            }--}}

{{--            $("#customer-tab").html(customerTabHtml);--}}
{{--        }--}}

{{--        function fetchOfftakeCustomerContent(customer_code) {--}}

{{--            let inventories = alasql("SELECT * FROM ? WHERE customer_code = '" + customer_code + "'", [offtakeData]);--}}

{{--            generateOfftakeTable(inventories, 'customer-tab-content', 'table-offtake-customer');--}}
{{--            generateOfftakeCategoryTable(inventories, 'customer-tab-content-item-category', 'table-offtake-category-customer');--}}
{{--        }--}}
{{--        /* *******************************************/--}}

{{--        /*  Chain Offtake Filter *********************/--}}
{{--        function fetchOfftakeChainTab() {--}}
{{--            let chainHtmlTab = '';--}}

{{--            for(let chain_code of chain_codes){--}}

{{--                let chain_description = alasql("SELECT description FROM ? WHERE chain_code = '" + chain_code + "'", [chainData])[0].description;--}}

{{--                // generate tab headers--}}
{{--                chainHtmlTab += "<li class=\"\"><a href=\"#" + chain_code + "\" data-toggle=\"tab\" aria-expanded=\"false\" onclick=\"fetchOfftakeChainContent('" + chain_code + "')\">" + chain_description + "</a></li>";--}}
{{--            }--}}

{{--            $("#chain-tab").html(chainHtmlTab);--}}
{{--        }--}}

{{--        function fetchOfftakeChainContent(chain_code) {--}}

{{--            let inventories = alasql("" +--}}
{{--                "SELECT " +--}}
{{--                "SUM(beginning_balance) AS `beginning_balance`," +--}}
{{--                "SUM(delivery) AS `delivery`," +--}}
{{--                "SUM(warehouse_area) AS `warehouse_area`," +--}}
{{--                "SUM(shelves_area) AS `shelves_area`," +--}}
{{--                "SUM(bo_area) AS `bo_area`," +--}}
{{--                "SUM(rtv) AS `rtv`," +--}}
{{--                "SUM(ending_balance) AS `ending_balance`," +--}}
{{--                "SUM(offtake) AS `offtake`," +--}}
{{--                "chain_code," +--}}
{{--                "material_code," +--}}
{{--                "material_description," +--}}
{{--                "main_group," +--}}
{{--                "sub_group," +--}}
{{--                "base_unit," +--}}
{{--                "toDate(created_at) AS `created_at`" +--}}
{{--                " " +--}}
{{--                "FROM ? " +--}}
{{--                "WHERE chain_code = '" + chain_code + "' " +--}}
{{--                "GROUP BY " +--}}
{{--                "chain_code," +--}}
{{--                "material_code," +--}}
{{--                "material_description," +--}}
{{--                "main_group," +--}}
{{--                "sub_group," +--}}
{{--                "base_unit," +--}}
{{--                "toDate(created_at)" +--}}
{{--                "", [offtakeData]);--}}

{{--            generateOfftakeTable(inventories, 'chain-tab-content', 'table-offtake-chain');--}}
{{--            generateOfftakeCategoryTable(inventories, 'chain-tab-content-item-category', 'table-offtake-category-chain');--}}
{{--        }--}}
{{--        /* *******************************************/--}}

{{--        /*  Accounts Offtake Filter ******************/--}}
{{--        function fetchOfftakeAccountsTab() {--}}
{{--            let accountsHtmlTab = '';--}}

{{--            let customer_account = $('#customer-account').val();--}}
{{--            let accounts = alasql("SELECT * FROM ? WHERE account_code LIKE '" + customer_account + "'", [accountData]);--}}

{{--            for(let account of accounts){--}}
{{--                // generate tab headers--}}
{{--                accountsHtmlTab += "<li class=\"\"><a href=\"#" + account.account_code + "\" data-toggle=\"tab\" aria-expanded=\"false\" onclick=\"fetchOfftakeAccountsContent('" + account.account_code + "')\">" + account.description + "</a></li>";--}}
{{--            }--}}

{{--            $("#accounts-tab").html(accountsHtmlTab);--}}
{{--        }--}}

{{--        function fetchOfftakeAccountsContent(account_code) {--}}

{{--            let inventories = alasql("" +--}}
{{--                "SELECT " +--}}
{{--                "SUM(beginning_balance) AS `beginning_balance`," +--}}
{{--                "SUM(delivery) AS `delivery`," +--}}
{{--                "SUM(warehouse_area) AS `warehouse_area`," +--}}
{{--                "SUM(shelves_area) AS `shelves_area`," +--}}
{{--                "SUM(bo_area) AS `bo_area`," +--}}
{{--                "SUM(rtv) AS `rtv`," +--}}
{{--                "SUM(ending_balance) AS `ending_balance`," +--}}
{{--                "SUM(offtake) AS `offtake`," +--}}
{{--                "account_code," +--}}
{{--                "material_code," +--}}
{{--                "material_description," +--}}
{{--                "main_group," +--}}
{{--                "sub_group," +--}}
{{--                "base_unit," +--}}
{{--                "toDate(created_at) AS `created_at`" +--}}
{{--                " " +--}}
{{--                "FROM ? " +--}}
{{--                "WHERE chain_code = '" + account_code + "' " +--}}
{{--                "GROUP BY " +--}}
{{--                "account_code," +--}}
{{--                "material_code," +--}}
{{--                "material_description," +--}}
{{--                "main_group," +--}}
{{--                "sub_group," +--}}
{{--                "base_unit," +--}}
{{--                "toDate(created_at)" +--}}
{{--                "", [offtakeData]);--}}

{{--            generateOfftakeTable(inventories, 'accounts-tab-content', 'table-offtake-account');--}}
{{--            generateOfftakeCategoryTable(inventories, 'accounts-tab-content-item-category', 'table-offtake-category-account');--}}
{{--        }--}}
{{--        /* *******************************************/--}}

{{--        function generateOfftakeTable(data, tab_id, table_id) {--}}

{{--            data = data.filter(x => x.material_code !== undefined); //avoid to display undefined--}}

{{--            /* generate column headers **************/--}}
{{--            let column_header_date = '';--}}
{{--            let column_header_inventory = '';--}}
{{--            for (let date of date_ranges){--}}
{{--                column_header_date += '<th colspan="8" style="text-align: center">' + moment(date).format('MMM DD, YYYY (ddd)');--}}
{{--                column_header_inventory +=--}}
{{--                    '<th>Beginning Balance' +--}}
{{--                    '<th>Delivery' +--}}
{{--                    '<th>Warehouse' +--}}
{{--                    '<th>Shelves' +--}}
{{--                    '<th>BO Area' +--}}
{{--                    '<th>Return' +--}}
{{--                    '<th>Ending Balance' +--}}
{{--                    '<th>Offtake';--}}
{{--            }--}}
{{--            /* ***************************************/--}}

{{--            let inventory_content = '';--}}
{{--            /* generate inventory content ************/--}}
{{--            let materials = alasql("SELECT DISTINCT material_code, material_description, base_unit FROM ? ", [data]); // generate materials as table row--}}
{{--            let offtakeDatas = JSON.parse('[]');--}}
{{--            for (let material of materials){--}}

{{--                let inventory = alasql("SELECT * FROM ? WHERE material_code = '" + material.material_code + "'", [data]);--}}

{{--                offtakeDatas.push({--}}
{{--                    "material": material.material_code,--}}
{{--                    "material_description": material.material_description,--}}
{{--                    "base_unit": material.base_unit,--}}
{{--                    "inventories": inventory--}}
{{--                });--}}
{{--            }--}}

{{--            // fetch json into table rows--}}
{{--            for (let offtakeData of offtakeDatas){--}}

{{--                let inventory_column = '';--}}
{{--                let prev_beginning_balance = '';--}}
{{--                let prev_delivery = '';--}}
{{--                let prev_warehouse = '';--}}
{{--                let prev_shelves = '';--}}
{{--                let prev_bo_area = '';--}}
{{--                let prev_rtv = '';--}}
{{--                let prev_ending_balance = '';--}}
{{--                let prev_offtake = '';--}}

{{--                for(let date of date_ranges){--}}

{{--                    let inventories = alasql("SELECT * FROM ? WHERE toDate(created_at) = '" + date + "'", [offtakeData.inventories]);--}}

{{--                    if (inventories.length > 0){--}}
{{--                        for (let inventory of inventories){--}}
{{--                            inventory_column +=--}}
{{--                                '<td>' + inventory.beginning_balance +--}}
{{--                                '<td>' + inventory.delivery +--}}
{{--                                '<td>' + inventory.warehouse_area +--}}
{{--                                '<td>' + inventory.shelves_area +--}}
{{--                                '<td>' + inventory.bo_area +--}}
{{--                                '<td>' + inventory.rtv +--}}
{{--                                '<td>' + inventory.ending_balance +--}}
{{--                                '<td>' + inventory.offtake;--}}

{{--                            prev_beginning_balance = inventory.ending_balance;--}}
{{--                            prev_delivery = '0';--}}
{{--                            prev_warehouse = inventory.warehouse_area;--}}
{{--                            prev_shelves = inventory.shelves_area;--}}
{{--                            prev_bo_area = inventory.bo_area;--}}
{{--                            prev_rtv = '0';--}}
{{--                            prev_ending_balance = inventory.ending_balance;--}}
{{--                            prev_offtake = '0';--}}
{{--                        }--}}
{{--                    }--}}
{{--                    else{--}}
{{--                        inventory_column +=--}}
{{--                            '<td style="color: red;">' + prev_beginning_balance +--}}
{{--                            '<td style="color: red;">' + prev_delivery +--}}
{{--                            '<td style="color: red;">' + prev_warehouse +--}}
{{--                            '<td style="color: red;">' + prev_shelves +--}}
{{--                            '<td style="color: red;">' + prev_bo_area +--}}
{{--                            '<td style="color: red;">' + prev_rtv +--}}
{{--                            '<td style="color: red;">' + prev_ending_balance +--}}
{{--                            '<td style="color: red;">' + prev_offtake;--}}
{{--                    }--}}
{{--                }--}}

{{--                inventory_content +=--}}
{{--                    '<tr>' +--}}
{{--                    '<td>' + offtakeData.material +--}}
{{--                    '<td>' + offtakeData.material_description +--}}
{{--                    '<td>' + offtakeData.base_unit +--}}
{{--                    inventory_column +--}}
{{--                    '</tr>';--}}
{{--            }--}}
{{--            /* ***************************************/--}}

{{--            /* generate table ************************/--}}
{{--            let tableOfftakeHtml =--}}
{{--                '<div class="table-responsive">' +--}}
{{--                '<table id="' + table_id + '" class="table table-bordered" style="white-space: nowrap; width: 100%">' +--}}
{{--                '<thead>' +--}}
{{--                '<tr>' +--}}
{{--                '<th colspan="3" style="text-align: center">Production Information' +--}}
{{--                column_header_date +--}}
{{--                '</tr>' +--}}
{{--                '<tr>' +--}}
{{--                '<th>Material Code' +--}}
{{--                '<th>Material Description' +--}}
{{--                '<th>Base UOM' +--}}
{{--                column_header_inventory +--}}
{{--                '</tr>' +--}}
{{--                '</thead>' +--}}
{{--                '<tbody>' +--}}
{{--                '<tr>' +--}}
{{--                inventory_content +--}}
{{--                '</tr>' +--}}
{{--                '</tbody>' +--}}
{{--                '</table>' +--}}
{{--                '</div>';--}}

{{--            tableOfftakeHtml = tablePlaceHolder(tableOfftakeHtml, data);--}}
{{--            $("#" + tab_id).html(tableOfftakeHtml);--}}
{{--            /* **************************************/--}}
{{--        }--}}

{{--        function generateOfftakeCategoryTable(data, tab_id, table_id) {--}}

{{--            data = data.filter(x => x.material_code !== undefined); //avoid to display undefined--}}

{{--            /* generate column headers **************/--}}
{{--            let column_header_date = '';--}}
{{--            let column_header_inventory = '';--}}
{{--            for (let date of date_ranges){--}}
{{--                column_header_date += '<th colspan="8" style="text-align: center">' + moment(date).format('MMM DD, YYYY (ddd)');--}}
{{--                column_header_inventory +=--}}
{{--                    '<th>Beginning Balance' +--}}
{{--                    '<th>Delivery' +--}}
{{--                    '<th>Warehouse' +--}}
{{--                    '<th>Shelves' +--}}
{{--                    '<th>BO Area' +--}}
{{--                    '<th>Return' +--}}
{{--                    '<th>Ending Balance' +--}}
{{--                    '<th>Offtake';--}}
{{--            }--}}
{{--            /* ***************************************/--}}

{{--            /* generate inventory content ************/--}}
{{--            let inventory_content = '';--}}

{{--            let offtakeCategories = alasql("" +--}}
{{--                "SELECT " +--}}
{{--                "SUM(beginning_balance) AS `beginning_balance`," +--}}
{{--                "SUM(delivery) AS `delivery`," +--}}
{{--                "SUM(warehouse_area) AS `warehouse_area`," +--}}
{{--                "SUM(shelves_area) AS `shelves_area`," +--}}
{{--                "SUM(bo_area) AS `bo_area`," +--}}
{{--                "SUM(rtv) AS `rtv`," +--}}
{{--                "SUM(ending_balance) AS `ending_balance`," +--}}
{{--                "SUM(offtake) AS `offtake`," +--}}
{{--                "main_group," +--}}
{{--                "sub_group," +--}}
{{--                "toDate(created_at) AS `created_at`" +--}}
{{--                " " +--}}
{{--                "FROM ? " +--}}
{{--                "GROUP BY " +--}}
{{--                "main_group," +--}}
{{--                "sub_group," +--}}
{{--                "toDate(created_at)" +--}}
{{--                "", [data]);--}}

{{--            // generate materials as table row--}}
{{--            let categories = categoryData;--}}
{{--            let offtakeDatas = JSON.parse('[]');--}}
{{--            for (let category of categories){--}}

{{--                let inventory = alasql("SELECT * FROM ? WHERE main_group = '" + category.group_main_code + "' AND sub_group = '" + category.group_sub_code + "'", [offtakeCategories]);--}}

{{--                if (inventory.length > 0){ // only those categories with inventory will display--}}
{{--                    offtakeDatas.push({--}}
{{--                        "group_main_code": category.group_main_code,--}}
{{--                        "group_main_description": category.group_main_description,--}}
{{--                        "group_sub_code": category.group_sub_code,--}}
{{--                        "group_sub_description": category.group_sub_description,--}}
{{--                        "inventories": inventory--}}
{{--                    });--}}
{{--                }--}}

{{--            }--}}

{{--            // fetch json into table rows--}}
{{--            for (let offtakeData of offtakeDatas){--}}

{{--                let inventory_column = '';--}}

{{--                let prev_beginning_balance = '';--}}
{{--                let prev_delivery = '';--}}
{{--                let prev_warehouse = '';--}}
{{--                let prev_shelves = '';--}}
{{--                let prev_bo_area = '';--}}
{{--                let prev_rtv = '';--}}
{{--                let prev_ending_balance = '';--}}
{{--                let prev_offtake = '';--}}

{{--                for(let date of date_ranges){--}}

{{--                    let inventories = alasql("SELECT * FROM ? WHERE created_at = '" + date + "'", [offtakeData.inventories]);--}}

{{--                    if (inventories.length > 0){--}}
{{--                        for (let inventory of inventories){--}}
{{--                            inventory_column +=--}}
{{--                                '<td>' + inventory.beginning_balance +--}}
{{--                                '<td>' + inventory.delivery +--}}
{{--                                '<td>' + inventory.warehouse_area +--}}
{{--                                '<td>' + inventory.shelves_area +--}}
{{--                                '<td>' + inventory.bo_area +--}}
{{--                                '<td>' + inventory.rtv +--}}
{{--                                '<td>' + inventory.ending_balance +--}}
{{--                                '<td>' + inventory.offtake;--}}

{{--                            prev_beginning_balance = inventory.ending_balance;--}}
{{--                            prev_delivery = '0';--}}
{{--                            prev_warehouse = inventory.warehouse_area;--}}
{{--                            prev_shelves = inventory.shelves_area;--}}
{{--                            prev_bo_area = inventory.bo_area;--}}
{{--                            prev_rtv = '0';--}}
{{--                            prev_ending_balance = inventory.ending_balance;--}}
{{--                            prev_offtake = '0';--}}
{{--                        }--}}
{{--                    }--}}
{{--                    else{--}}
{{--                        inventory_column +=--}}
{{--                            '<td style="color: red;">' + prev_beginning_balance +--}}
{{--                            '<td style="color: red;">' + prev_delivery +--}}
{{--                            '<td style="color: red;">' + prev_warehouse +--}}
{{--                            '<td style="color: red;">' + prev_shelves +--}}
{{--                            '<td style="color: red;">' + prev_bo_area +--}}
{{--                            '<td style="color: red;">' + prev_rtv +--}}
{{--                            '<td style="color: red;">' + prev_ending_balance +--}}
{{--                            '<td style="color: red;">' + prev_offtake;--}}
{{--                    }--}}
{{--                }--}}

{{--                inventory_content +=--}}
{{--                    '<tr>' +--}}
{{--                        '<td>' + offtakeData.group_main_description + ' ' + offtakeData.group_sub_description +--}}
{{--                        inventory_column +--}}
{{--                    '</tr>';--}}
{{--            }--}}
{{--            /* ***************************************/--}}

{{--            /* generate table ************************/--}}
{{--            let tableOfftakeHtml =--}}
{{--                '<div class="table-responsive">' +--}}
{{--                    '<table id="' + table_id + '" class="table table-bordered" style="white-space: nowrap; width: 100%">' +--}}
{{--                    '<thead>' +--}}
{{--                        '<tr>' +--}}
{{--                            '<th style="text-align: center">Category Information' +--}}
{{--                            column_header_date +--}}
{{--                        '</tr>' +--}}
{{--                        '<tr>' +--}}
{{--                            '<th>Category' +--}}
{{--                            column_header_inventory +--}}
{{--                        '</tr>' +--}}
{{--                    '</thead>' +--}}
{{--                    '<tbody>' +--}}
{{--                        '<tr>' +--}}
{{--                            inventory_content +--}}
{{--                        '</tr>' +--}}
{{--                    '</tbody>' +--}}
{{--                    '</table>' +--}}
{{--                '</div>';--}}

{{--            tableOfftakeHtml = tablePlaceHolder(tableOfftakeHtml, data);--}}
{{--            $("#" + tab_id).html(tableOfftakeHtml);--}}
{{--            /* **************************************/--}}

{{--        }--}}

{{--        // date format to alasql--}}
{{--        alasql.fn.toDate = function (dateStr) {--}}
{{--            let date = new Date(dateStr);--}}
{{--            return moment(date).format('YYYY-MM-DD');--}}
{{--        };--}}

{{--    </script>--}}
@endpush