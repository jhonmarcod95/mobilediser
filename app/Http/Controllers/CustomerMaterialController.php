<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerMaterial;
use App\Material;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class CustomerMaterialController extends Controller
{
    public function index(Request $request){
        $customer_ids = $this->customerCodeSearch($request->customer_ids);
        $material_ids = $this->materialCodeSearch($request->material_ids);

        $search_materials = Material::showCodeAndDesc()->put('0', 'All');
        $search_customers = Customer::showCodeAndName()->put('0', 'All');

        $materials = Material::whereIn('material_code', $material_ids)->get();
        $carried_materials = collect(DB::select("CALL p_customer_carried('" . $this->arrayToString($customer_ids) . "')"));

        return view('customerCarried.index', compact(
            'search_customers',
            'search_materials',
            'carried_materials',
            'materials'
        ));
    }

    public function indexData(Request $request){
        $customer_ids = $this->customerCodeSearch($request->customer_ids);
        $material_ids = $this->materialCodeSearch($request->material_ids);

        $materials = Material::whereIn('material_code', $material_ids)->get();
        $carried_materials = collect(DB::select("CALL p_customer_carried('" . $this->arrayToString($customer_ids) . "')"));


        foreach($carried_materials->unique('customer_code') as $customer){
            $item = new stdClass();
            $item->customer_code = $customer->customer_code;
            $item->name = $customer->name;

            //check if carried material
            foreach ($materials as $material){
                $tag = '';
                if(count($carried_materials->where('customer_code', $customer->customer_code)
                    ->where('material_code', $material->material_code)))
                {
                    $tag = 'checked';
                }
                $item->carrieds[] = [
                    'material_code' => $material->material_code,
                    'tag' => $tag
                ];
            }

            $carrieds[] = $item;

        }

        $result['carrieds'] = $carrieds;
        $result['materials'] = $materials;

        return $result;
    }

    public function setCarried($customer_code, $material_code){

        if(CustomerMaterial::where('customer_code', $customer_code)
            ->where('material_code', $material_code)
            ->exists()){

            $customer_material = CustomerMaterial::where('customer_code', $customer_code)
                ->where('material_code', $material_code);
            $customer_material->delete();
        }
        else{
            $customer_material = new CustomerMaterial();
            $customer_material->customer_code = $customer_code;
            $customer_material->material_code = $material_code;
            $customer_material->save();
        }


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
