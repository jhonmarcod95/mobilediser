<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Customer;
use App\MerchandiserSchedule;
use App\Rules\ScheduleConflictRule;
use App\Rules\ScheduleUploadRule;
use App\User;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends Controller
{

    private $merchandisers;
    private $customers;
    private $weekDays;
    private $weeks;
    private $agencies;

    public function __construct()
    {
        $this->merchandisers = User::select(
            DB::raw("CONCAT(first_name, ' ', last_name) AS fullname"), 'merchandiser_id')
            ->where('account_type', 3)
            ->pluck('fullname', 'merchandiser_id')
            ->put('0', 'All');

        $this->agencies = Agency::all()
            ->pluck('name', 'agency_code')
            ->put('0', 'All');

        $this->weekDays = [
            '1' => 'Mon',
            '2' => 'Tue',
            '3' => 'Wed',
            '4' => 'Thu',
            '5' => 'Fri',
            '6' => 'Sat',
            '7' => 'Sun'
        ];

        $this->weeks = [
            '1' => '1st',
            '2' => '2nd',
            '3' => '3rd',
            '4' => '4th',
            '5' => '5th',
            '%' => 'All'
        ];

        $this->customers = Customer::showCodeAndName();
    }

    public function index(Request $request)
    {
        $merchandisers = $this->merchandisers;
        $customers = $this->customers;
        $weekDays = $this->weekDays;
        $weeks = $this->weeks;

        return view('schedule.index', compact(
            'merchandisers',
            'customers',
            'weeks',
            'weekDays'
        ));
    }

    public function indexData(Request $request){
        $merchandiser_ids = $this->merchandiserIdSearch($request->merchandiser_ids);
        $startOfMonth = $request->startOfMonth;
        $endOfMonth = $request->endOfMonth;

        $dates = $this->getDateRange($startOfMonth, $endOfMonth);

        $merchandisers = User::where('account_type', 3)
            ->whereIn('merchandiser_id', $merchandiser_ids)
            ->get([
                'merchandiser_id',
                'first_name',
                'last_name',
            ]);

        $schedules = DB::table('vw_schedules')
            ->whereIn('merchandiser_id', $merchandiser_ids)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        return [
            'merchandisers' => $merchandisers,
            'dates' => $dates,
            'schedules' => $schedules
        ];
    }

    public function show($id, Request $request){
        $merchandiser_id = $id;
        $monthYear = $request->monthYear;

        $dates = $this->getMonthDays($monthYear);

        $schedules = DB::table('vw_schedules')
            ->where('merchandiser_id', $merchandiser_id)
            ->whereBetween('date', [$dates[0], $dates[count($dates) - 1]])
            ->get();

        $customers = $schedules->pluck('customer_name', 'customer_code')->put('%', 'All');

        return [
            'schedules' => $schedules,
            'customers' => $customers,
            'weekdays' => $this->weekDays
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
        $request->validate([
            'merchandiser_id' => 'required',
            'store' => ['required', new ScheduleConflictRule(
                null,
                $request->merchandiser_id,
                $request->weekdays
            )],
            'weekdays' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $weekdays = $request->weekdays;

        DB::beginTransaction();
        foreach ($weekdays as $weekday) {
            if (!empty($weekday)){ #not include those 5fth weeks without dates (null values).
                $schedule = new MerchandiserSchedule();
                $schedule->merchandiser_id = $request->merchandiser_id;
                $schedule->customer_code = $request->store;
                $schedule->date = $weekday;
                $schedule->time_in = $request->start_time;
                $schedule->time_out = $request->end_time;
                $schedule->status = '002';
                $schedule->save();
            }

        }
        DB::commit();

        return $schedule;
    }

    public function update(Request $request){

//        $request->validate([
//            'checkbox' => 'required',
//            'merchandiser_id' => 'required',
//            'store' => ['required', new ScheduleConflictRule(
//                $request->checkbox,
//                $request->merchandiser_id,
//                $request->weekdays
//            )],
//            'weekdays' => 'required',
//            'start_time' => 'required',
//            'end_time' => 'required|after:start_time',
//        ]);


        $request->validate([
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
        $request->validate([
            'checkbox' => 'required',
        ]);

        $schedule_ids = $request->checkbox;
        DB::beginTransaction();
        foreach ($schedule_ids as $schedule_id) {
            $schedule = MerchandiserSchedule::find($schedule_id);
            $schedule->delete();
        }
        DB::commit();

        return $schedule;
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


    /* Reports *****************************/
    public function merchandiserPerformanceData(Request $request){
        $merchandiser_ids = $this->merchandiserIdSearch($request->merchandiser_ids);
        $startOfMonth = $request->startOfMonth;
        $endOfMonth = $request->endOfMonth;

        $dates = $this->getDateRange($startOfMonth, $endOfMonth);

        $merchandisers = User::where('account_type', 3)
            ->whereIn('merchandiser_id', $merchandiser_ids)
            ->get([
                'merchandiser_id',
                'first_name',
                'last_name',
            ]);

        $schedules = MerchandiserSchedule::join('merchandiser_attendance', 'merchandiser_schedule.id', '=', 'merchandiser_attendance.schedule_id')
            ->join('users', 'merchandiser_schedule.merchandiser_id', '=', 'users.merchandiser_id')
            ->join('agency_master_data', 'agency_master_data.agency_code', '=', 'users.agency_code')
            ->join('customer_master_data', 'merchandiser_schedule.customer_code', '=', 'customer_master_data.customer_code')
            ->whereIn('users.merchandiser_id', $merchandiser_ids)
            ->whereBetween('merchandiser_schedule.date', [$startOfMonth, $endOfMonth])
            ->get([
                'merchandiser_schedule.id',
                'merchandiser_schedule.date',
                'merchandiser_schedule.time_in AS start_time',
                'merchandiser_schedule.time_out AS end_time',
                'merchandiser_attendance.time_in',
                'merchandiser_attendance.time_out',
                'merchandiser_schedule.status',
                'users.merchandiser_id',
                'users.first_name',
                'users.last_name',
                'agency_master_data.agency_code',
                'agency_master_data.name AS agency',
                'customer_master_data.name AS store',
                'customer_master_data.branch',
            ]);


        return [
            'merchandisers' => $merchandisers,
            'dates' => $dates,
            'schedules' => $schedules
        ];
    }

    public function merchandiserPerformance(){
        $merchandisers = $this->merchandisers;
        return view('report.merchandiserPerformance', compact(
            'merchandisers'
        ));
    }

    public function merchandiserLogData(Request $request){
        $merchandiser_ids = $this->merchandiserIdSearch($request->merchandiser_ids);
        $startOfMonth = $request->startOfMonth;
        $endOfMonth = $request->endOfMonth;

        $logs = MerchandiserSchedule::join('merchandiser_attendance', 'merchandiser_schedule.id', 'merchandiser_attendance.schedule_id')
            ->join('merchandiser_attendance_image', 'merchandiser_attendance_image.schedule_id', 'merchandiser_attendance.schedule_id')
            ->join('users', 'merchandiser_schedule.merchandiser_id', 'users.merchandiser_id')
            ->join('customer_master_data', 'merchandiser_schedule.customer_code', 'customer_master_data.customer_code')
            ->leftjoin('inventory_transaction_header', 'inventory_transaction_header.schedule_id', 'merchandiser_schedule.id')
            ->whereIn('users.merchandiser_id', $merchandiser_ids)
            ->whereBetween('merchandiser_schedule.date', [$startOfMonth, $endOfMonth])
            ->get([
                'merchandiser_schedule.id',
                'merchandiser_schedule.date',
                'merchandiser_schedule.time_in AS start_time',
                'merchandiser_schedule.time_out AS end_time',
                'merchandiser_schedule.status',
                'merchandiser_attendance.time_in',
                'merchandiser_attendance.time_out',
                'merchandiser_attendance_image.image_path',
                'inventory_transaction_header.transaction_number',
                'merchandiser_schedule.status',
                'users.merchandiser_id',
                'users.first_name',
                'users.last_name',
                'customer_master_data.name AS store',
                'customer_master_data.branch',
            ]);

        return $logs;
    }

    public function merchandiserLog(){
        $merchandisers = $this->merchandisers;
        return view('report.merchandiserLog', compact(
            'merchandisers'
        ));
    }

    public function merchandiserAttendanceData(Request $request){
        $merchandiser_ids = $this->merchandiserIdSearch($request->merchandiser_ids);
        $agency_ids = $this->agencyIdSearch($request->agency_ids);

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $attendances = MerchandiserSchedule::join('customer_master_data', 'customer_master_data.customer_code', 'merchandiser_schedule.customer_code')
            ->leftjoin('merchandiser_attendance', 'merchandiser_attendance.schedule_id', 'merchandiser_schedule.id')
            ->join('users', 'users.merchandiser_id', 'merchandiser_schedule.merchandiser_id')
            ->join('agency_master_data', 'agency_master_data.agency_code', 'users.agency_code')
            ->whereIn('merchandiser_schedule.merchandiser_id', $merchandiser_ids)
            ->whereIn('agency_master_data.agency_code', $agency_ids)
            ->whereBetween('merchandiser_schedule.date', [$dateFrom, $dateTo])
            ->get([
                'merchandiser_schedule.id',
                'merchandiser_schedule.merchandiser_id',
                'merchandiser_schedule.date',
                'customer_master_data.customer_code',
                'customer_master_data.name  AS store',
                'customer_master_data.branch',
                'merchandiser_schedule.time_in AS start_time',
                'merchandiser_schedule.time_out AS end_time',
                'merchandiser_attendance.time_in',
                'merchandiser_attendance.time_out'
            ])
            ->groupBy('merchandiser_id');

        $merchandisers = User::where('account_type',3)->get();
        $agencies = Agency::all();
        $dates = $this->getDateRange($dateFrom, $dateTo);

        $jsonDates = [];
        foreach ($dates as $date){
            $jsonDates[] = ["date" => $date];
        }

        return [
            'merchandisers' => $merchandisers,
            'attendances' => $attendances,
            'agencies' => $agencies,
            'dates' => $jsonDates
        ];
    }

    public function merchandiserAttendance(){
        $agencies = $this->agencies;
        $merchandisers = $this->merchandisers;
        return view('report.merchandiserAttendance', compact(
            'merchandisers',
            'agencies'
        ));
    }
    /* *************************************/


    /* Function ****************************/
    private function getDateRange($start, $end){
        $periodEnd = new DateTime($end);

        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            $periodEnd->add(new DateInterval('P1D'))
        );

        foreach ($period as $key => $value) {
            $dates[] = $value->format('Y-m-d');
        }
        return $dates;
    }

    private function getMonthDays($monthYear){
        $month = Carbon::parse($monthYear)->month;
        $year = Carbon::parse($monthYear)->year;

        $daysCount = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates = $this->dateRange("$year-$month-01", "$year-$month-$daysCount");
        return $dates;
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

    public function agencyIdSearch($agency_ids){
        if(empty($agency_ids)){
            return [null];
        }
        foreach ($agency_ids as $agency_id) {
            if($agency_id == 0){
                return Agency::all()->pluck('agency_code');
            }
        }
        return $agency_ids;
    }

    /* *************************************/
}
