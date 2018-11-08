<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\Attendance;
use App\InventoryTransactionHeader;
use App\MerchandiserSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dateToday = Carbon::now()->format('Y-m-d');

        $msgHeaders = DB::table('vw_merchandiser_message_header')
            ->where(DB::raw('DATE(created_at)'), $dateToday)
            ->get()
            ->sortByDesc('created_at');

        $announcements = DB::table('vw_announcements')
            ->where(DB::raw('DATE(created_at)'), $dateToday)
            ->get()
            ->sortByDesc('created_at');

        return view('home', compact(
            'msgHeaders',
            'announcements'
        ));
    }

    public function getInStore($date){
        return MerchandiserSchedule::join('merchandiser_attendance', 'merchandiser_schedule.id', '=', 'merchandiser_attendance.schedule_id')
            ->whereDate('date', $date)
            ->whereNull('merchandiser_attendance.time_out')
            ->get();
    }

    public function getVisitedStore($date){
        return MerchandiserSchedule::where('status', '001')
            ->whereDate('date', $date)
            ->get();
    }

    public function getInventory($date){
        return InventoryTransactionHeader::whereDate('created_at', $date)
            ->get();
    }

    public function getSchedule($date){
        $schedules = MerchandiserSchedule::leftjoin('merchandiser_attendance', 'merchandiser_schedule.id', '=', 'merchandiser_attendance.schedule_id')
            ->join('users', 'merchandiser_schedule.merchandiser_id', '=', 'users.merchandiser_id')
            ->join('agency_master_data', 'agency_master_data.agency_code', '=', 'users.agency_code')
            ->join('customer_master_data', 'merchandiser_schedule.customer_code', '=', 'customer_master_data.customer_code')
            ->whereDate('date', $date)
            ->get([
                'merchandiser_schedule.id',
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

        $agencies = collect($schedules)
            ->groupBy('agency');

        $totalAgencyScheduleCount = 0;
        $totalVisitedCount = 0;
        $totalRemaining = 0;
        $totalInStoreCount = 0;

        foreach ($agencies as $key => $agency){
            $agencyScheduleCount = $agency->count();
            $visitedCount = $agency->where('status', '001')->count();
            $remainingCount = $agency->where('time_in', null)->where('time_out', null)->count();
            $inStoreCount = $agency->where('time_in', '!=', null)->where('time_out', null)->count();

            $status = ['in-store', 'visited', 'remaining'];

            $agencySchedules[] = [
                'text' => $key . ': '  . $agencyScheduleCount,
                'agency' => $key,
                'nodes' => [
                    [
                        'text' => 'In Store: ' . $inStoreCount,
                        'agency' => $key,
                        'status' => $status[0]
                    ],
                    [
                        'text' => 'Visited: ' . $visitedCount,
                        'agency' => $key,
                        'status' => $status[1]
                    ],
                    [
                        'text' => 'Remaining: ' . $remainingCount,
                        'agency' => $key,
                        'status' => $status[2]
                    ],
                ]
            ];

            $totalAgencyScheduleCount += $agencyScheduleCount;
            $totalVisitedCount += $visitedCount;
            $totalRemaining += $remainingCount;
            $totalInStoreCount += $inStoreCount;
        }

        $agencies = [
            [
                'text' => 'Total Schedule: ' . $totalAgencyScheduleCount,
                'agency' => '',
                'nodes' => $agencySchedules
            ],
            [
                'text' => 'Total In Store: ' . $totalInStoreCount,
                'agency' => '',
                'status' => $status[0]
            ],
            [
                'text' => 'Total Visited: ' . $totalVisitedCount,
                'agency' => '',
                'status' => $status[1]
            ],
            [
                'text' => 'Total Remaining: ' . $totalRemaining,
                'agency' => '',
                'status' => $status[2]
            ],
            'total' => $totalAgencyScheduleCount,
            'schedules' => $schedules
        ];

        return $agencies;
    }

    public function getRecentlyLogin(){
        return Attendance::join('merchandiser_schedule', 'merchandiser_attendance.schedule_id', 'merchandiser_schedule.id')
            ->join('users', 'merchandiser_schedule.merchandiser_id', 'users.merchandiser_id')
            ->join('customer_master_data', 'merchandiser_schedule.customer_code', 'customer_master_data.customer_code')
            ->orderByDesc('merchandiser_attendance.id')
            ->get([
                'merchandiser_attendance.id',
                'merchandiser_attendance.created_at',
                'merchandiser_attendance.time_in',
                'users.last_name',
                'users.first_name',
                'customer_master_data.name AS store',
                'customer_master_data.branch',
            ])
            ->take(5);
    }

    public function getScheduleSummary(Request $request){

        $schedules = MerchandiserSchedule::join('users', 'merchandiser_schedule.merchandiser_id', 'users.merchandiser_id')
            ->whereDate('date', Carbon::now()->toDateString())
            ->select(
                'merchandiser_schedule.merchandiser_id',
                'users.last_name',
                'users.first_name',
                'merchandiser_schedule.customer_code',
                'merchandiser_schedule.status'
            )
            ->get()
            ->groupBy('merchandiser_id')

            ;

        $page = Input::get('page', 1); // Get the ?page=1 from the url
        $perPage = 15; // Number of items per page
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(
            array_slice($schedules->toArray(), $offset, $perPage, true), // Only grab the items we need
            count($schedules), // Total items
            $perPage, // Items per page
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }
}
