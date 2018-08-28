<?php

namespace App\Http\Controllers;

use App\Customer;
use App\MerchandiserSchedule;
use App\Rules\ScheduleUploadRule;
use App\Rules\MerchandiserIdRule;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends Controller
{
    public function show(Request $request)
    {
        $mechandiser_id = $request->merchandiser_ids;
        $monthYear = $request->monthYear;
        $month = Carbon::parse($monthYear)->month;
        $year = Carbon::parse($monthYear)->year;

        $daysCount = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dates = $this->dateRange("$year-$month-01", "$year-$month-$daysCount");

        $schedules = DB::table('vw_schedules')
//            ->where('merchandiser_id', $mechandiser_id)
            ->get();

        $customers = Customer::showCodeAndName();

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

    public function records($merchandiser_id, $date)
    {
        $customers = Customer::showCodeAndName();
        $merchandiser = User::getName($merchandiser_id);

        $schedules = DB::table('vw_schedules')
            ->where('merchandiser_id', $merchandiser_id)
            ->whereDate('date', '=', $date)
            ->get();


        return view('schedule.records', compact(
            'schedules',
            'customers',
            'merchandiser_id',
            'merchandiser',
            'date'
        ));
    }


    public function save(Request $request)
    {
        $validation = $request->validate([
            'merchandiser_id' => 'required',
            'customer' => 'required',
            'date' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
        ]);

        $schedule = new MerchandiserSchedule();
        $schedule->merchandiser_id = $request->merchandiser_id;
        $schedule->customer_code = $request->customer;
        $schedule->date = $request->date;
        $schedule->time_in = $request->startTime;
        $schedule->time_out = $request->endTime;
        $schedule->save();

        alert()->success('Schedule has been added','');

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $schedule_ids = $request->schedule_ids;
        foreach ($schedule_ids as $schedule_id) {
            $schedule = MerchandiserSchedule::find($schedule_id);
            $schedule->delete();
        }

        alert()->success('Selected schedules has been deleted','');

        return redirect()->back();
    }

    public function upload(Request $request){
        $monthYear = $request->monthYear;

        $validation = $request->validate([
            'monthYear' => 'required',
            'import_file' => ['required', new ScheduleUploadRule($monthYear)],
        ]);


        if($request->hasFile('import_file')){
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) use ($monthYear){
                $schedules = collect($reader->toArray());

                /*------------------- save schedule ----------------------*/
                foreach ($schedules as $schedule){
                    $weekDays = explode('/', $schedule['day']); #split slashes to get days
                    $timeLog = explode('-', $schedule['time']); #split dashes to get timein and timeout

                    $timeIn = date("H:i", strtotime($timeLog[0]));
                    $timeOut = date("H:i", strtotime($timeLog[1]));

                    foreach ($weekDays as $weekDay){

                        $days = $this->getDates($monthYear, $weekDay); #get dates of weekdays
                        foreach ($days as $day){
                            $sched = new MerchandiserSchedule();
                            $sched->merchandiser_id = $schedule['id'];
                            $sched->customer_code = $schedule['code'];
                            $sched->date = $day;
                            $sched->time_in = $timeIn;
                            $sched->time_out = $timeOut;
                            $sched->save();
                        }
                    }
                }
                /*--------------------------------------------------------*/
            });

            alert()->success('Schedule has been uploaded','');
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

    public static function getDates($monthYear, $dayDesc){

        $day = date('N', strtotime($dayDesc));
        /*---- to format 1 = sunday, 7 = saturday ----*/
        $day = $day + 1;
        if($day > 7) $day = 1;
        /*--------------------------------------------*/

        $date = "$monthYear-01";
        $first_day = date('N',strtotime($date));
        $first_day = $day -  $day - $first_day + $day;
        $last_day =  date('t',strtotime($date));
        $days = array();
        for($i=$first_day; $i<=$last_day; $i=$i+7 ){
            if($i > 0){ //avoid negative values
                $days[] = "$monthYear-" . sprintf('%02d', $i);
            }
        }
        return  $days;
    }
}
