<?php

namespace App\Http\Controllers;

use App\CustomerCategory;
use App\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerTypeController extends Controller
{
    public function show(){
        $customerTypes = DB::select('CALL p_chain');

        return view('masterData.customerType',compact(
            'customerTypes'
        ));
    }

    public function info(Request $request){
        $id = $request->id;
        $isEdit = false;
        $actionUrl = "save";

        if(!empty($id)){
            $customerType = CustomerType::where('id', $id)->get();
            #if id exist will edit record
            if(count($customerType)){
                $isEdit = true;
                $actionUrl = "update";
                $customerType = $customerType->first();
            }
        }

        $customerCategories = CustomerCategory::get()
            ->pluck('description', 'category_code');


        return view('masterData.customerTypeInfo',compact(
            'customerType',
            'isEdit',
            'actionUrl',
            'customerCategories'
        ));
    }

    public function save(Request $request){

        $validation = $request->validate([
            'chain_code' => 'required|unique:chain',
            'description' => 'required',
            'category_code' => 'required',
        ]);

        #save customer type
        $customerType = new CustomerType();
        $customerType->chain_code = $request->chain_code;
        $customerType->description = $request->description;
        $customerType->category_code = $request->category_code;
        $customerType->save();

        alert()->success('New Customer Type has been added.','');
        return redirect('/customers/types');
    }

    public function update(Request $request){

        $id = $request->id;
        $validation = $request->validate([
            'chain_code' => 'required|unique:chain,chain_code,' . $id,
            'description' => 'required',
            'category_code' => 'required',
        ]);


        #update customer type
        $customerType = CustomerType::find($id);
        $customerType->chain_code = $request->chain_code;
        $customerType->description = $request->description;
        $customerType->category_code = $request->category_code;
        $customerType->save();


        alert()->success('Customer Type info has been updated.','');
        return redirect('/customers/types');
    }
}
