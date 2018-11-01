<?php

namespace App\Http\Controllers;

use App\Customer;
use App\MerchandiserSchedule;
use App\Rules\ScheduleConflictRule;
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

    private $merchandisers;
    private $customers;

    public function __construct()
    {
        $this->merchandisers = User::select(
            DB::raw("CONCAT(first_name, ' ', last_name) AS fullname"), 'merchandiser_id')
            ->where('account_type', 3)
            ->pluck('fullname', 'merchandiser_id');

        $this->customers = Customer::showCodeAndName();
    }

    public function index(Request $request)
    {
        $merchandisers = $this->merchandisers
            ->put('0', 'All');

        return view('schedule.index', compact(
            'merchandisers'
        ));
    }

    public function indexData(Request $request){
        $merchandiser_ids = $this->merchandiserIdSearch($request->merchandiser_ids);
        $monthYear = $request->monthYear;

        $month = Carbon::parse($monthYear)->month;
        $year = Carbon::parse($monthYear)->year;

        $daysCount = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates = $this->dateRange("$year-$month-01", "$year-$month-$daysCount");

        $merchandisers = User::where('account_type', 3)
            ->whereIn('merchandiser_id', $merchandiser_ids)
            ->get([
                'merchandiser_id',
                'first_name',
                'last_name',
            ]);

        $schedules = DB::table('vw_schedules')
            ->whereIn('merchandiser_id', $merchandiser_ids)
            ->whereBetween('date', [$dates[0], $dates[count($dates) - 1]])
            ->get();

        return [
            'merchandisers' => $merchandisers,
            'dates' => $dates,
            'schedules' => $schedules,
        ];
    }


    public function records($merchandiser_id, $date)
    {
        $customers = $this->customers;
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

    public function edit($id)
    {
        $customers = $this->customers;
        $schedule = DB::table('vw_schedules')
            ->where('id', $id)
            ->first();

        session(['schedule_id' => $schedule->id]);

        return view('schedule.edit', compact(
            'schedule',
            'customers'
        ));
    }


    public function save(Request $request)
    {
        $validation = $request->validate([
            'merchandiser_id' => 'required',
            'customer' => ['required', new ScheduleConflictRule(
                $request->merchandiser_id,
                $request->date,
                $request->startTime,
                $request->endTime)
            ],
            'date' => 'required',
            'startTime' => 'required',
            'endTime' => 'required|after:startTime',
        ]);

        $schedule = new MerchandiserSchedule();
        $schedule->merchandiser_id = $request->merchandiser_id;
        $schedule->customer_code = $request->customer;
        $schedule->date = $request->date;
        $schedule->time_in = $request->startTime;
        $schedule->time_out = $request->endTime;
        $schedule->status = '002';
        $schedule->save();

        alert()->success('Schedule has been added','');

        return redirect()->back();
    }

    public function update(Request $request){
        $validation = $request->validate([
            'customer' => 'required',
            'date' => 'required',
            'startTime' => 'required',
            'endTime' => 'required|after:startTime',
        ]);

        $schedule = MerchandiserSchedule::find(session('schedule_id'));
        $schedule->customer_code = $request->customer;
        $schedule->date = $request->date;
        $schedule->time_in = $request->startTime;
        $schedule->time_out = $request->endTime;
        $schedule->save();

        alert()->success('Schedule has been updated','');

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $validation = $request->validate([
            'schedule_ids' => 'required',
        ]);

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

        $request->validate([
            'monthYear' => 'required',
            'import_file' => ['required', 'mimes:xlsx,xls', new ScheduleUploadRule($monthYear)],
        ]);

        if($request->hasFile('import_file')){
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) use ($monthYear){
                $schedules = collect($reader->toArray());

                /*------------------- save schedule ----------------------*/
                DB::beginTransaction();
                foreach ($schedules as $schedule){
                    $weekDays = MerchandiserSchedule::parseExcelWeekDay($schedule);
                    $timeLog = MerchandiserSchedule::parseExcelTime($schedule);

                    $timeIn = date("H:i", strtotime($timeLog[0]));
                    $timeOut = date("H:i", strtotime($timeLog[1]));

                    foreach ($weekDays as $weekDay){

                        $days =  MerchandiserSchedule::parseDates($monthYear, $weekDay); #get dates of weekdays
                        foreach ($days as $day){
                            $sched = new MerchandiserSchedule();
                            $sched->merchandiser_id = $schedule[MerchandiserSchedule::$ID];
                            $sched->customer_code = $schedule[MerchandiserSchedule::$BRANCH_CODE];
                            $sched->date = $day;
                            $sched->time_in = $timeIn;
                            $sched->time_out = $timeOut;
                            $sched->status = '002'; #not visited
                            $sched->save();
                        }
                    }
                }
                DB::commit();
                /*--------------------------------------------------------*/
            });
        }

        return 'success';
    }



    /*---------------------- Functions ---------------------------*/
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

    public function merchandiserIdSearch($merchandiser_ids){
        if(empty($merchandiser_ids)){
            return [null];
        }
        foreach ($merchandiser_ids as $merchandiser_id) {
            if($merchandiser_id == 0){
                return User::all()->pluck('merchandiser_id');
            }
        }
        return $merchandiser_ids;
    }

    /*------------------------------------------------------------*/
}
