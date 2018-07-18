<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\InventoryTransactionHeader;
use App\MerchandiserSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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


        $msgCount = $msgHeaders->count();

        $inventoryCount = InventoryTransactionHeader::where(DB::raw('DATE(created_at)'), $dateToday)
            ->get()
            ->count();

        $announcementCount = Announcement::where(DB::raw('DATE(created_at)'), $dateToday)
            ->get()
            ->count();

        $scheduleCount = MerchandiserSchedule::where('date', $dateToday)
            ->get()
            ->count();


        return view('home', compact(
            'msgHeaders',
            'msgCount',
            'inventoryCount',
            'announcementCount',
            'scheduleCount',
            'announcements',
            'Carbon'
        ));
    }
}
