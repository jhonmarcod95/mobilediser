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

        return view('home', compact(
            'msgHeaders',
            'announcements'
        ));
    }

    public function getInStore(){
        return MerchandiserSchedule::join('merchandiser_attendance', 'merchandiser_schedule.id', '=', 'merchandiser_attendance.schedule_id')
            ->whereDate('date', Carbon::now()->toDateString())
            ->whereNull('merchandiser_attendance.time_out')
            ->get();
    }

    public function getVisitedStore(){
        return MerchandiserSchedule::where('status', '001')
            ->whereDate('date', Carbon::now()->toDateString())
            ->get();
    }

    public function getInventory(){
        return InventoryTransactionHeader::whereDate('created_at', Carbon::now()->toDateString())
            ->get();
    }

    public function getOfftake(){

    }

}
