<?php

namespace App\Http\Controllers;

use App\CustomerCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerCategoryController extends Controller
{

    public function index(){
        return view('masterData.customer-account.index');
    }

    public function indexData(Request $request){
        $paginate = $request->paginate;
        $search = $request->search;

        return CustomerCategory::where('description', 'LIKE', '%' . $search . '%')
            ->paginate($paginate);
    }

    public function save(Request $request){

        $request->validate([
            'account_code' => 'required|unique:customer_accounts',
            'description' => 'required',
        ]);

        #save customer category
        $customerCategory = new CustomerCategory();
        $customerCategory->account_code = $request->account_code;
        $customerCategory->description = $request->description;
        $customerCategory->save();

        return $customerCategory;
    }

    public function update(Request $request){

        $request->validate([
            'account_code' => 'required|unique:customer_accounts,account_code,' . $request->id,
            'description' => 'required',
        ]);

        #update customer category
        $customerCategory = CustomerCategory::find($request->id);
        $customerCategory->account_code = $request->account_code;
        $customerCategory->description = $request->description;
        $customerCategory->save();

        return $customerCategory;
    }
}
