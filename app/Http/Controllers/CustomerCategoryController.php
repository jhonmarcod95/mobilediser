<?php

namespace App\Http\Controllers;

use App\CustomerCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerCategoryController extends Controller
{
    public function show(){
        $customerCategories = CustomerCategory::all();

        return view('masterData.customerCategory',compact(
            'customerCategories'
        ));
    }

    public function info(Request $request){
        $id = $request->id;
        $isEdit = false;
        $actionUrl = "save";

        if(!empty($id)){
            $customerCategory = CustomerCategory::where('id', $id)
                ->get();

            #if id exist will edit record
            if(count($customerCategory)){
                $isEdit = true;
                $actionUrl = "update";
                $customerCategory = $customerCategory->first();
            }
        }

        return view('masterData.customerCategoryInfo',compact(
            'customerCategory',
            'isEdit',
            'actionUrl'
        ));
    }

    public function save(Request $request){

        $validation = $request->validate([
            'category_code' => 'required|unique:customer_category',
            'description' => 'required',
        ]);

        #save customer category
        $customerCategory = new CustomerCategory();
        $customerCategory->category_code = $request->category_code;
        $customerCategory->description = $request->description;
        $customerCategory->save();

        alert()->success('New Customer Category has been added.','');
        return redirect('/customers/categories');
    }

    public function update(Request $request){

        $id = $request->id;
        $validation = $request->validate([
            'category_code' => 'required|unique:customer_category,category_code,' . $id,
            'description' => 'required',
        ]);

        #update customer category
        $customerCategory = CustomerCategory::find($id);
        $customerCategory->category_code = $request->category_code;
        $customerCategory->description = $request->description;
        $customerCategory->save();

        alert()->success('Customer Category info has been updated.','');
        return redirect('/customers/categories');
    }
}
