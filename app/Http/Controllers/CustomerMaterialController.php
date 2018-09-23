<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Material;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerMaterialController extends Controller
{

    public function show(Request $request){
        $customer_ids = $this->customerCodeSearch($request->customer_ids);
        $material_ids = $this->materialCodeSearch($request->material_ids);

        $search_materials = Material::showCodeAndDesc()->put('0', 'All');
        $search_customers = Customer::showCodeAndName()->put('0', 'All');

        $materials = Material::whereIn('material_code', $material_ids)->get();
        $carried_materials = collect(DB::select("CALL p_customer_carried('" . $this->arrayToString($customer_ids) . "')"));


        return view('masterData.customerCarried', compact(
            'search_customers',
            'search_materials',
            'carried_materials',
            'materials'
        ));
    }


    /* ------------ Functions -------------- */
    public function customerCodeSearch($customer_ids){
        if(empty($customer_ids)){
            return [null];
        }
        foreach ($customer_ids as $customer_id) {
            if($customer_id == 0){
                return Customer::all()->pluck('customer_code');
            }
        }
        return $customer_ids;
    }

    public function materialCodeSearch($material_ids){
        if(empty($material_ids)){
            return [null];
        }
        foreach ($material_ids as $material_id) {
            if($material_id == 0){
                return Material::all()->pluck('material_code');
            }
        }
        return $material_ids;
    }

    public function arrayToString($items){
        $result = "";
        foreach ($items as $item) {
            $result .= $item . ',';
        }
        return $result;
    }


}
