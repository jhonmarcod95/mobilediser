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
        $totalUserCount = 0;
        $totalUserLoginCount = 0;
        $totalUserNotLoginCount = 0;

        foreach ($agencies as $key => $agency){
            $agencyScheduleCount = $agency->count();
            $visitedCount = $agency->where('status', '001')->count();
            $remainingCount = $agency->where('time_in', null)->where('time_out', null)->count();
            $inStoreCount = $agency->where('time_in', '!=', null)->where('time_out', null)->count();

            $userCount = $agency->unique('merchandiser_id')->count();
            $userLoginCount = $agency->unique('merchandiser_id')->where('time_in', '!=', null)->count();
            $userNotLoginCount = $agency->unique('merchandiser_id')->where('time_in', null)->count();

            $status = ['in-store', 'visited', 'remaining', 'login', 'not-login'];
            $model = ['agency', 'merchandiser'];

            //agency schedule
            $agencySchedules[] = [
                'text' => $key . ': '  . $agencyScheduleCount,
                'agency' => $key,
                'model' => $model[0],
                'nodes' => [
                    [
                        'text' => 'In Store: ' . $inStoreCount,
                        'agency' => $key,
                        'model' => $model[0],
                        'status' => $status[0]
                    ],
                    [
                        'text' => 'Visited: ' . $visitedCount,
                        'agency' => $key,
                        'model' => $model[0],
                        'status' => $status[1]
                    ],
                    [
                        'text' => 'Remaining: ' . $remainingCount,
                        'agency' => $key,
                        'model' => $model[0],
                        'status' => $status[2]
                    ],
                ]
            ];

            //merchandiser schedule
            $merchandiserSchedules[] = [
                'text' => $key . ': '  . $userCount,
                'agency' => $key,
                'model' => $model[1],
                'nodes' => [
                    [
                        'text' => 'Login: ' . $userLoginCount,
                        'agency' => $key,
                        'model' => $model[1],
                        'status' => $status[3]
                    ],
                    [
                        'text' => 'Not Login: ' . $userNotLoginCount,
                        'agency' => $key,
                        'model' => $model[1],
                        'status' => $status[4]
                    ]
                ]
            ];

            $totalAgencyScheduleCount += $agencyScheduleCount;
            $totalVisitedCount += $visitedCount;
            $totalRemaining += $remainingCount;
            $totalInStoreCount += $inStoreCount;
            $totalUserCount += $userCount;
            $totalUserLoginCount += $userLoginCount;
            $totalUserNotLoginCount += $userNotLoginCount;

        }

        // add total counts into schedules
        $agencySchedules[] = [
            'text' => 'Total In Store: ' . $totalInStoreCount,
            'model' => $model[0],
            'agency' => '',
            'status' => $status[0]
        ];

        $agencySchedules[] = [
            'text' => 'Total Visited: ' . $totalVisitedCount,
            'model' => $model[0],
            'agency' => '',
            'status' => $status[1]
        ];

        $agencySchedules[] = [
            'text' => 'Total Remaining: ' . $totalRemaining,
            'model' => $model[0],
            'agency' => '',
            'status' => $status[2]
        ];

        // add total counts into merchandiser
        $merchandiserSchedules[] = [
            'text' => 'Total Login: ' . $totalUserLoginCount,
            'model' => $model[1],
            'agency' => '',
            'status' => $status[3]
        ];

        $merchandiserSchedules[] = [
            'text' => 'Total Not Login: ' . $totalUserNotLoginCount,
            'model' => $model[1],
            'agency' => '',
            'status' => $status[4]
        ];

        $scheduleDashboard = [
            [
                'text' => 'Schedules: ' . $totalAgencyScheduleCount,
                'agency' => '',
                'nodes' => $agencySchedules,
                'model' => $model[0],
            ],
            [
                'text' => 'Merchandisers: ' . $totalUserCount,
                'agency' => '',
                'nodes' => $merchandiserSchedules,
                'model' => $model[1],
            ],
            'total' => $totalAgencyScheduleCount,
            'schedules' => $schedules
        ];

        return $scheduleDashboard;
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
