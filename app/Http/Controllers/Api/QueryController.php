<?php

namespace App\Http\Controllers\Api;

use App\InventoryTransactionHeader;
use App\InventoryTransactionImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function query(Request $request)
    {
        $query = $request->qry;
        try
        {
            $result = ['items' => DB::select($query)];
        }
        catch (\Exception $e)
        {
            $result = "failed";
        }
        return $result;
    }

    public function execute(Request $request)
    {
        $query = $request->qry;

        try
        {
            $result = DB::select($query);
            $result = "success";
        }
        catch (\Exception $e)
        {
            $result = "failed";
        }
        return $result;
    }

    public function inserttransaction(Request $request)
    {
         $transaction_number = $request->transaction_number;
        $header = $request->header;
        $item = $request->item;
        $expiration = $request->expiration;

        $delete_header="delete from inventory_transaction_header where transaction_number = '$transaction_number'";
        $delete_item="delete from inventory_transaction_items where transaction_number = '$transaction_number'";
        $delete_expiration="delete from inventory_transaction_expiration where transaction_number = '$transaction_number'";
        $delete_image ="delete from inventory_transaction_image where transaction_number = '$transaction_number'";
        //delete image file

        try
        {
            DB::select($delete_header);
            DB::select($delete_item);
            DB::select($delete_expiration);

            DB::select($header);
            DB::select($item);
            DB::select($expiration);

            $result = "success";
        }
        catch (\Exception $e)
        {
            DB::select($delete_header);
            DB::select($delete_item);
            DB::select($delete_expiration);
            DB::select($delete_image);
            $result = "failed";
        }
        return $result;
    }

    public function insertTransactionImage(Request $request){
        $transaction_number = $request->transaction_number;

        try{
            #upload image
            $path = $request->file('img')->store('inventories','public');
            $transactionImage = new InventoryTransactionImage();
            $transactionImage->transaction_number = $transaction_number;
            $transactionImage->image_path = $path;
            $transactionImage->save();
            $result = "success";
        }
        catch (\Exception $e){
            $result = "failed";
        }
        return $result;
    }

    public function transactionNumber(Request $request){

        $merchandiser_id = $request->merchandiser_id;
        $customer_id = $request->customer_id;
        $customer_code = $request->customer_code;

        $pad_length = 12;

        $transaction_number = InventoryTransactionHeader::where('merchandiser_id', $merchandiser_id)
            ->where('customer_code', $customer_code)
            ->max(DB::raw("RIGHT(transaction_number, $pad_length)"));


        $transaction_number = str_pad($transaction_number + 1, $pad_length, '0', STR_PAD_LEFT);

        $result = $customer_id . $merchandiser_id . ($transaction_number);
        return $result;
    }
}
