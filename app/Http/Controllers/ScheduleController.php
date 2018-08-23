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
use Maatwebsite\Excel\Facades\Excel;

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
        $validation = $request->validate([
            'remarks' => 'required',
            'weekdays' => 'required',
            'merchandiser' => ['required', new ConflictScheduleRule],
        ]);


        if($request->exists('delete')){
            $this->delete($request);
        }

        $customers = $request->customers;
        $weekdays = $request->weekdays;
        $remarks = $request->remarks;


        foreach ($customers as $key => $value){
            foreach ($weekdays[$key] as $weekday){
                #$remarks = $remarks[$key];

                return $weekday;
            }
        }

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

    public static function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
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

    public function upload(Request $request){

        if($request->hasFile('import_file')){
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) {
                dd($reader);

                foreach ($reader->toArray() as $key => $row) {

                    $result[] = (object) [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'code' => $row['code'],
                        'branch' => $row['branch'],
                    ];


//                    $data['title'] = $row['title'];
//                    $data['description'] = $row['description'];
//
//                    if(!empty($data)) {
//                        DB::table('post')->insert($data);
//                    }
                }

            });


        }

        $day = date('N', strtotime('Thurs'));

        $days = $this->getDays('2018','08', $day);
        return $days;
    }

    public function getDays($y, $m, $d){
        /*---- to format 1 = sunday, 7 = saturday ----*/
        $d = $d + 1;
        if($d > 7) $d = 1;
        /*--------------------------------------------*/

        $date = "$y-$m-01";
        $first_day = date('N',strtotime($date));
        $first_day = $d -  $d - $first_day + $d;
        $last_day =  date('t',strtotime($date));
        $days = array();
        for($i=$first_day; $i<=$last_day; $i=$i+7 ){
            if($i > 0){ //avoid negative values
                $days[] = $i;
            }
        }
        return  $days;
    }
}
