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
            '%' => 'All',
            'island' => 'Island',
            'region' => 'Region',
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


        return TransactionOfftake::with('customer.chain.account')
            ->whereDate('created_at', '>=', '2021-05-01')
            ->whereHas('customer.chain', function($q){
                $q->where('chain_code', '004');
            })
            ->get()
            ->groupBy(['customer_code', function($item) {
                return $item->created_at->format('Y-m-d');
            }])
            ;

        $request->validated();

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $customerCodes = $request->customer_codes;
        $report_type = $request->report_type;

        $dates = ScheduleController::getDateRange($dateFrom, $dateTo);
        $transactions = TransactionOfftake::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->when($report_type == 3, function ($query, $type) use ($customerCodes) {
                return $query->whereIn('customer_code', $customerCodes);
            })
            ->get();

        return [
            'dates' => $dates,
            'customers' => $customerCodes,
            'transactions' => $transactions,
        ];
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
