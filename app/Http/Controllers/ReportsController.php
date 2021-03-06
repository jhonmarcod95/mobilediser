<?php

namespace App\Http\Controllers;

use App\Customer;
use App\InventoryTransactionImage;
use App\TransactionOfftake;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    private $merchandisers;

    public function __construct()
    {
        $this->merchandisers = User::select(
            DB::raw("CONCAT(first_name, ' ', last_name) AS fullname"), 'merchandiser_id')
            ->where('account_type', 3)
            ->pluck('fullname', 'merchandiser_id');
    }

    public function offtakePerCustomer(Request $request){
        $customer_code = $request->customer_code;
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $materialOfftakes = TransactionOfftake::getMaterialOfftake();
        $customers = Customer::showCodeAndName();
        $transactionOfftakes = collect(DB::select("CALL p_transaction_offtake ('" . $customer_code . "', '" . $date_from .  "', '" . $date_to . "')"));
        $customer_detail = collect(DB::select('CALL p_customers(\'' . $customer_code . '\')'))->first();

        return view('report.offtakePerCustomer', compact(
            'transactionOfftakes',
            'materialOfftakes',
            'customers',
            'customer_detail'
        ));
    }

    public function inventoryLog(Request $request){
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $inventory_logs = DB::select("CALL p_inventory_logs ('" . $date_from . "', '" .$date_to . "')");

        return view('report.inventoryLog', compact(
            'inventory_logs'
        ));
    }

    public function inventoryLogTransaction($transactionNumber){

        $transactionImage = InventoryTransactionImage::where('transaction_number', $transactionNumber)
            ->pluck('image_path')
            ->first();

        $inventory_items = collect(DB::select("CALL p_inventory_item_logs ('" . $transactionNumber .  "')"));
        $physicalCountItems = $inventory_items->whereIn('inventory_type', ['1','2','3']);
        $deliveryCountItems = $inventory_items->whereIn('inventory_type', ['4']);
        $returnCountItems = $inventory_items->whereIn('inventory_type', ['5']);

        return view('report.inventoryLogModalContent', compact(
            'transactionImage',
            'physicalCountItems',
            'deliveryCountItems',
            'returnCountItems'
        ));
    }

    public function merchandiserAttendance(Request $request){
        $monthYear = $request->monthYear;
        $month = Carbon::parse($monthYear)->month;
        $year = Carbon::parse($monthYear)->year;

        $daysCount = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dates =  ScheduleController::dateRange("$year-$month-01", "$year-$month-$daysCount");
        $schedules = DB::table('vw_schedules')->get();

        $merchandisers = User::select(
            DB::raw("CONCAT(first_name, ' ', last_name) AS fullname"), 'merchandiser_id')
            ->where('account_type', 3)
            ->get();


        return view('report.merchandiserAttendance', compact(
            'dates',
            'schedules',
            'merchandisers'
        ));
    }






}
