<?php

namespace App\Http\Controllers;

use App\Chain;
use App\Customer;
use App\CustomerCategory;
use App\Http\Requests\FilterOfftake;
use App\Island;
use App\Material;
use App\Municipality;
use App\Province;
use App\Region;
use App\TransactionOfftake;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfftakeController extends Controller
{
    public function index(){

        $customerAccounts = CustomerCategory::showCodeAndName()
            ->put('%', 'All');

        $chains = Chain::showCodeAndName()
            ->put('%', 'All');

        $customers = Customer::showCodeAndName()
            ->put('%', 'All');

        $islands = Island::showCodeAndName()
            ->put('%', 'All');

        $regions = Region::showCodeAndName()
            ->put('%', 'All');

        $provinces = Province::showCodeAndName()
            ->put('%', 'All');

        $municipalities = Municipality::showCodeAndName()
            ->put('%', 'All');

        $materials = Material::showCodeAndDesc()
            ->put('%', 'All');


        $places = [
            '' => 'All',
//            'island' => 'Island',
            'region' => 'Sales Region',
            'province' => 'Province',
            'municipality' => 'Municipality',
        ];

        return view('offtake.index', compact(
            'places',
            'customers',
            'customerAccounts',
            'chains',
            'islands',
            'regions',
            'provinces',
            'municipalities',
            'materials'
        ));
    }

    public function filter(){

        $customerAccounts = CustomerCategory::all();

        $chains = Chain::all();

        $customers = Customer::all();

        $islands = Island::all();

        $regions = Region::all();

        $provinces = Province::all();

        $municipalities = Municipality::all();

        $materials = Material::all();

        $categories = Material::join('material_group_main', 'material_group_main.code', 'material_master_data.main_group')
            ->leftJoin('material_group_sub', 'material_group_sub.code', 'material_master_data.sub_group')
            ->distinct()
            ->get([
                'material_group_main.code AS group_main_code',
                'material_group_main.description AS group_main_description',
                'material_group_sub.code AS group_sub_code',
                'material_group_sub.description AS group_sub_description'
            ]);


        return [
            'accounts' => $customerAccounts,
            'chains' => $chains,
            'customers' => $customers,
            'islands' => $islands,
            'regions' => $regions,
            'provinces' => $provinces,
            'municipalities' => $municipalities,
            'materials' => $materials,
            'categories' => $categories,
        ];
    }

    public function getIslands(){
        return Island::all();
    }

    public function getRegions(){

    }

    public function getProvinces(){

    }

    public function getMunicipalities(){

    }

    public function indexData(Request $request){
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $transactions = TransactionOfftake::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->get();

        return $transactions;
    }

    public function customerData(){
        ini_set('memory_limit', '2048M'); // revise this
        $dates = ScheduleController::getDateRange('2021-04-01', '2021-04-30');

        $offtakes = TransactionOfftake::with('customer.chain.account', 'material')
            ->whereBetween('created_at', ['2021-04-01 00:00:00', '2021-04-30 23:59:59'])
//            ->whereIn('customer_code', ['1202000401', '1202000369'])
            ->whereHas('customer.chain', function($q){
                $q->where('chain_code', '004');
            })

            ->get();

        $offtakeCustomers = $offtakes->groupBy(['customer_code', 'material_code', function($item) {
             return $item->created_at->format('Y-m-d');
        }]);

        $offtake_customers = [];

        foreach ($offtakeCustomers as $c => $offtakeCustomer){

            $customer_details = [];
            $offtake_materials = [];

            foreach ($offtakeCustomer as $m => $material){

                foreach ($material as $ds => $offtake_dates){

                    // insert dates
                    foreach ($dates as $date){
                        if (isset($offtakeCustomer[$m][$date]) == false){ // date not exist
                            $offtakeCustomer[$m][$date] = [];
                        }
                    }

                    // init offtake per material
                    if ($offtakeCustomer[$m][$ds][0]->material){ // only includes material that are existing in db or material not null (replacement for whereHas query, since whereHas can effect performance)
                        $offtake_material = [
                            'material' => $offtakeCustomer[$m][$ds][0]->material,
                            'dates' => $offtakeCustomer[$m]
                        ];
                    }

                    // get customer details
                    foreach ($offtake_dates as $o => $offtake){

                        $customer_details = $offtake->customer;

                        // remove not needed keys, to reduce memory
                        unset($offtakeCustomer[$m][$ds][$o]['customer']);
                        unset($offtakeCustomer[$m][$ds][$o]['material']);
                        unset($offtakeCustomer[$m][$ds][$o]['created_at']);
                        unset($offtakeCustomer[$m][$ds][$o]['updated_at']);
                        unset($offtakeCustomer[$m][$ds][$o]['id']);
                        unset($offtakeCustomer[$m][$ds][$o]['transaction_number']);
                        unset($offtakeCustomer[$m][$ds][$o]['customer_code']);
                        unset($offtakeCustomer[$m][$ds][$o]['material_code']);
                        unset($offtakeCustomer[$m][$ds][$o]['base_uom']);
                    }


                    // sort
                    $offtakeCustomer[$m] = collect($material)->sortBy(function ($key, $value){
                        return $value;
                    });
                }

                // append offtake per material
                $offtake_materials[] = $offtake_material;
            }

            // append offtake per customer
            $offtake_customers[] = [
                'customer' => $customer_details,
                'offtake' => $offtake_materials
            ];
        }

        return $offtake_customers;

    }

    public function offtakeSummaryData(Request $request){

        return DB::table(DB::raw('transaction_offtake USE INDEX()'))
            ->whereDate('created_at', '>=', $request->date_from)
            ->whereDate('created_at', '<=', $request->date_to)
            ->whereIn('customer_code', $request->customer_codes)
            ->groupBy('material_code')
            ->select(DB::raw(
                'material_code,
                 SUM(beginning_balance) AS beginning_balance,
                 SUM(delivery) AS delivery,
                 SUM(rtv) AS rtv,
                 SUM(physical_count) AS physical_count,
                 SUM(warehouse_area) AS warehouse_area,
                 SUM(bo_area) AS bo_area,
                 SUM(ending_balance) AS ending_balance,
                 SUM(offtake) AS offtake
             '))
            ->get();
    }


}
