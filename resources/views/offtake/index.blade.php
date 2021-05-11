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
                                    <select class="form-control select2">
                                        <option v-for="(place, p) in places" :value="p">@{{ place }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- region -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Sales Region : </label>
                                    <select class="form-control select2">
                                        <option v-for="(region, r) in regions" :value="r">@{{ region }}</option>
                                    </select>
                                </div>
                            </div>

{{--                            <!-- province -->--}}
{{--                            <div class="col-md-2">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="text-muted">Province : </label>--}}
{{--                                    <select class="form-control select2">--}}
{{--                                        <option v-for="(province, p) in provinces" :value="p">@{{ province }}</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <!-- municipality -->
{{--                            <div class="col-md-2">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="text-muted">Municipality : </label>--}}
{{--                                    <select v-model="selected_fields" class="form-control select2">--}}
{{--                                        <option v-for="(municipality, m) in municipalities" :value="m">@{{ municipality }}</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            {{-- account --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Customer Account : </label>
                                    {!! Form::select('customer_account', $customerAccounts, null, ['class' => 'form-control select2', 'placeholder' => 'Select value...', 'id' => 'customer-account']) !!}
                                </div>
                            </div>

                            {{-- chain --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Chain : </label>
                                    <select id="chain" name="chain" class="form-control"></select>
                                </div>
                            </div>

                            {{-- customer --}}
                            <div class="col-md-3">
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

                            <!-- column to display -->
{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="text-muted">Column to display : </label>--}}
{{--                                    <multiselect  v-model="selected_fields"  :multiple="true" :options="['beginning balance','delivery', 'warehouse', 'shelves', 'bo area', 'return', 'ending balance', 'offtake']"></multiselect>--}}
{{--                                </div>--}}
{{--                            </div>--}}



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
                                <button onclick="exportTableToExcel('table-offtake-customer')" class="btn btn-primary btn-sm"><i class="fa fa-file-excel-o"></i>&nbsp;Export Per Product</button>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">

                        {{-- Customer Offtake Table --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="table-offtake-customer" class="table table-bordered" style="white-space: nowrap; width: 100%" border="1">
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
                places: {!! json_encode($places) !!},
                customer_accounts: {!! json_encode($customerAccounts) !!},
                chains: {!! json_encode($chains) !!},
                customers: {!! json_encode($customers) !!},
                islands: {!! json_encode($islands) !!},
                regions: {!! json_encode($regions) !!},
                provinces: {!! json_encode($provinces) !!},
                municipalities: {!! json_encode($municipalities) !!},
                materials: {!! json_encode($materials) !!},

                selected_fields: [],

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

@endpush