<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Municipality;
use App\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(){
        $chains = CustomerType::get()->pluck('description', 'chain_code');
        $municipalities = Municipality::get()->pluck('description', 'municipality_code');

        return view('masterData.customer.index',compact(
            'chains',
            'municipalities'
        ));
    }

    public function indexData(Request $request){
        $paginate = $request->paginate;
        $search = $request->search;

        return Customer::leftjoin('chain', 'chain.chain_code', 'customer_master_data.chain_code')
            ->leftjoin('place_municipality', 'place_municipality.municipality_code', 'customer_master_data.municipality_code')
            ->where('customer_master_data.name', 'LIKE', '%' . $search . '%')
            ->orWhere('customer_master_data.branch', 'LIKE', '%' . $search . '%')
            ->orWhere('chain.description', 'LIKE', '%' . $search . '%')
            ->select([
                'customer_master_data.customer_id',
                'customer_master_data.customer_code',
                'customer_master_data.name',
                'customer_master_data.branch',
                'customer_master_data.address',
                'chain.chain_code',
                'chain.description AS chain_description',
                'place_municipality.municipality_code',
                'place_municipality.description AS municipality_description',
                'customer_master_data.created_at',
                'customer_master_data.updated_at'
            ])
            ->paginate($paginate);
    }

    public function save(Request $request){

        $request->validate([
            'customer_code' => 'required|unique:customer_master_data',
            'customer_name' => 'required',
            'branch' => 'required',
            'address' => 'required',
            'chain' => 'required',
            'municipality' => 'required',
        ]);

        #save customers
        $customer = new Customer();
        $customer->customer_code = $request->customer_code;
        $customer->name = $request->customer_name;
        $customer->branch = $request->branch;
        $customer->address = $request->address;
        $customer->chain_code = $request->chain;
        $customer->municipality_code = $request->municipality;
        $customer->save();

        return $customer;
    }

    public function update(Request $request){

        $request->validate([
            'customer_code' => 'required|unique:customer_master_data,customer_code,' . $request->customer_id . ',customer_id',
            'customer_name' => 'required',
            'branch' => 'required',
            'address' => 'required',
            'chain' => 'required',
            'municipality' => 'required',
        ]);

        #update customer
        $customer = Customer::find($request->customer_id);
        $customer->customer_code = $request->customer_code;
        $customer->name = $request->customer_name;
        $customer->branch = $request->branch;
        $customer->address = $request->address;
        $customer->chain_code = $request->chain;
        $customer->municipality_code = $request->municipality;
        $customer->save();

        return $customer;
    }
}
