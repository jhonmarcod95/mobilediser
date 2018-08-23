<?php

namespace App\Http\Controllers;

use App\TransactionOfftake;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function offtakePerCustomer(){
        $materialOfftakes = TransactionOfftake::getMaterialOfftake();

        return view('report.offtakePerCustomer', compact(
            'materialOfftakes'
        ));
    }

    public function merchandiserLog(){
        return view('report.merchandiserLog');
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
