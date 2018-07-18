<?php

namespace App\Http\Controllers;

use App\Customer;
use App\MerchandiserSchedule;
use App\Rules\ConflictScheduleRule;
use App\Rules\MerchandiserIdRule;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function show(Request $request)
    {
        $monthYear = $request->monthYear;
        $month = Carbon::parse($monthYear)->month;
        $year = Carbon::parse($monthYear)->year;

        $daysCount = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dates = $this->dateRange("$year-$month-01", "$year-$month-$daysCount");


        $schedules = DB::table('vw_schedules')->get();

        $customers = Customer::select(DB::raw("CONCAT(name, ' - ', branch) AS name"), 'customer_code')
            ->get()
            ->pluck('name','customer_code');

        $merchandisers = User::select(
            DB::raw("CONCAT(first_name, ' ', last_name) AS fullname"), 'merchandiser_id')
            ->where('account_type', 3)
            ->get();


        return view('schedule.show', compact(
            'monthYear',
            'dates',
            'merchandisers',
            'customers',
            'schedules'
        ));
    }

    public function records(Request $request)
    {
        $monthYear = Carbon::parse($request->monthYear);
        $month = $monthYear->month;
        $year = $monthYear->year;

        $daysCount = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($day = 1; $day <= $daysCount; $day++) {
            $days[] = $day;
        }

        $schedules = DB::table('vw_schedules')->get();

        $customers = Customer::get()
            ->pluck(
                'name',
                'customer_code'
            );

        $merchandisers = User::select(
            DB::raw("CONCAT(first_name, ' ', last_name) AS fullname"), 'merchandiser_id')
            ->where('account_type', 3)
            ->get();


        return view('schedule.records', compact(
            'days',
            'merchandisers',
            'customers',
            'schedules'
        ));
    }


    public function save(Request $request)
    {
        return $request;
        if($request->exists('delete')){
            $this->delete($request);
        }


        $validation = $request->validate([
            'merchandiser1' => ['required', new ConflictScheduleRule],
        ]);

//        $entries = explode(',', $request->entries);
//
//        foreach ($entries as $entry) {
//            $merchandiser_id = $request["merchandiser$entry"];
//            $customer_codes = $request["customers$entry"];
//            $dtRange = explode('-', $request["dtRange$entry"]);
//            $remarks = $request["remarks$entry"];
//
//            foreach ($customer_codes as $customer_code) {
//                $begin = Carbon::parse($dtRange[0]);
//                $end = Carbon::parse($dtRange[1])->modify('+1 day'); //+2 to loop until end
//                for ($date = $begin; $date < $end; $date->modify('+1 day')) {
//
//                    $merchandiser = new MerchandiserSchedule();
//                    $merchandiser->merchandiser_id = $merchandiser_id;
//                    $merchandiser->customer_code = $customer_code;
//                    $merchandiser->date = $date;
//                    $merchandiser->status = '002';
//                    $merchandiser->remarks = $remarks;
//                    $merchandiser->created_by = Auth::user()->merchandiser_id;
//                    $merchandiser->save();
//                }
//            }
//
//        }
//
//        $merchandisers = $request->merchandisers;
//
//        foreach ($merchandisers as $merchandiser_id){
//            $customer_codes = $request->$merchandiser_id;
//            $dtRange = explode('-',$request["dtRange$merchandiser_id"]);
//            $remarks = $request["remarks$merchandiser_id"];
//
//            foreach ($customer_codes as $customer_code){
//                $begin = Carbon::parse( $dtRange[0]);
//                $end  = Carbon::parse( $dtRange[1])->modify('+1 day'); //+2 to loop until end
//                for($date = $begin; $date < $end; $date->modify('+1 day')){
//
//                    $merchandiser = new MerchandiserSchedule();
//                    $merchandiser->merchandiser_id = $merchandiser_id;
//                    $merchandiser->customer_code = $customer_code;
//                    $merchandiser->date = $date;
//                    $merchandiser->status = '002';
//                    $merchandiser->remarks = $remarks;
//                    $merchandiser->created_by = Auth::user()->merchandiser_id;
//                    $merchandiser->save();
//                }
//            }
//        }
//
////        $url = url('/schedules');
////        echo "
////            <script>window.top.location.href = \"$url\" </script>
////        ";
//
        return redirect('/schedules');

    }

    public function delete(Request $request)
    {
        $schedule_ids = $request->chkSchedules;
        foreach ($schedule_ids as $schedule_id) {
            $schedule = MerchandiserSchedule::find($schedule_id);
            $schedule->delete();
        }

        return redirect('/schedules');
    }

    function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
    {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while ($current <= $last) {

            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }
}
