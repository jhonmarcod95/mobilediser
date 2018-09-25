<?php

namespace App\Http\Controllers;

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

    public function offtakePerCustomer(){
        $materialOfftakes = TransactionOfftake::getMaterialOfftake();

        return view('report.offtakePerCustomer', compact(
            'materialOfftakes'
        ));
    }

    public function merchandiserLog(Request $request){
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $merchandiser_logs = DB::select("CALL p_merchandiser_logs ('" . $date_from . "', '" .$date_to . "')");

        return view('report.merchandiserLog', compact(
            'merchandiser_logs'
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
