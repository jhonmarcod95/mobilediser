<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerMunicipality;
use App\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function show(){
        $customers = DB::select('CALL p_customers (\'%%\')');

        return view('masterData.customer',compact(
            'customers'
        ));
    }

    public function info(Request $request){
        $customer_id = $request->id;
        $isEdit = false;
        $actionUrl = "save";

        if(!empty($customer_id)){
            $customer = Customer::where('customer_id', $customer_id)->get();
            #if id exist will edit record
            if(count($customer)){
                $isEdit = true;
                $actionUrl = "update";
                $customer = $customer->first();
            }
        }

        $customerTypes = CustomerType::get()->pluck('description', 'chain_code');
        $customerMunicipalities = CustomerMunicipality::get()->pluck('description', 'municipality_code');

        return view('masterData.customerInfo',compact(
            'customer',
            'isEdit',
            'actionUrl',
            'customerTypes',
            'customerMunicipalities'
        ));
    }

    public function save(Request $request){

        $request->validate([
            'customer_code' => 'required|unique:customer_master_data',
            'name' => 'required',
            'address' => 'required',
            'branch' => 'required',
            'customer_type_code' => 'required',
        ]);

        #save customers
        $customer = new Customer();
        $customer->customer_code = $request->customer_code;
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->branch = $request->branch;
        $customer->chain_code = $request->customer_type_code;
        $customer->municipality_code = $request->municipality_code;
        $customer->save();

        alert()->success('New Customer has been added.','');
        return redirect('/customers');
    }

    public function update(Request $request){

        $customer_id = $request->customer_id;
        $request->validate([
            'customer_code' => 'required|unique:customer_master_data,customer_code,' . $customer_id . ',customer_id',
            'name' => 'required',
            'address' => 'required',
            'branch' => 'required',
            'customer_type_code' => 'required',
        ]);

        #update customer
        $customer = Customer::find($customer_id);
        $customer->customer_code = $request->customer_code;
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->branch = $request->branch;
        $customer->chain_code = $request->customer_type_code;
        $customer->municipality_code = $request->municipality_code;
        $customer->save();

        alert()->success('Customer info has been updated.','');
        return redirect('/customers');
    }
}
