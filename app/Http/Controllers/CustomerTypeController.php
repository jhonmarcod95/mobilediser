<?php

namespace App\Http\Controllers;

use App\CustomerCategory;
use App\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerTypeController extends Controller
{

    public function index(){
        $customerAccounts = CustomerCategory::all()
            ->pluck('description', 'account_code');

        return view('masterData.chain.index',compact(
            'customerAccounts'
        ));
    }

    public function indexData(Request $request){
        $paginate = $request->paginate;
        $search = $request->search;

        return CustomerType::join('customer_accounts', 'customer_accounts.account_code', 'chain.account_code')
            ->where('chain.description', 'LIKE', '%' . $search . '%')
            ->orWhere('chain.chain_code', 'LIKE', '%' . $search . '%')
            ->orWhere('customer_accounts.description', 'LIKE', '%' . $search . '%')
            ->select([
                'chain.id',
                'chain.chain_code',
                'chain.description AS chain_description',
                'chain.account_code',
                'customer_accounts.description AS account_description',
            ])
            ->paginate($paginate);
    }

    public function save(Request $request){

        $request->validate([
            'chain_code' => 'required|unique:chain',
            'description' => 'required',
            'account' => 'required',
        ]);

        #save customer type
        $customerType = new CustomerType();
        $customerType->chain_code = $request->chain_code;
        $customerType->description = $request->description;
        $customerType->account_code = $request->account;
        $customerType->save();

        return $customerType;
    }

    public function update(Request $request){

        $request->validate([
            'chain_code' => 'required|unique:chain,chain_code,' . $request->id,
            'description' => 'required',
            'account' => 'required',
        ]);


        #update customer type
        $customerType = CustomerType::find($request->id);
        $customerType->chain_code = $request->chain_code;
        $customerType->description = $request->description;
        $customerType->account_code = $request->account;
        $customerType->save();

        return $customerType;
    }
}
