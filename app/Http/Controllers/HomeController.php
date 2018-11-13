<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\Attendance;
use App\InventoryTransactionHeader;
use App\MerchandiserSchedule;
use App\TransactionOfftake;
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
        $inventories = MerchandiserSchedule::leftjoin('inventory_transaction_header', 'inventory_transaction_header.schedule_id', 'merchandiser_schedule.id')
            ->join('users', 'merchandiser_schedule.merchandiser_id', '=', 'users.merchandiser_id')
            ->join('agency_master_data', 'agency_master_data.agency_code', '=', 'users.agency_code')
            ->join('customer_master_data', 'merchandiser_schedule.customer_code', '=', 'customer_master_data.customer_code')
            ->whereDate('date', $date)
            ->where('inventory_transaction_header.transaction_number', '!=', 'NULL')
            ->get();

        return $inventories;
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

        return $schedules;
    }

    public function getOfftake($date){
        $offtakes = TransactionOfftake::join('customer_master_data', 'customer_master_data.customer_code', 'transaction_offtake.customer_code')
            ->join('material_master_data', 'material_master_data.material_code', 'transaction_offtake.material_code')
            ->join('chain', 'chain.chain_code', 'customer_master_data.chain_code')
            ->join('customer_accounts', 'customer_accounts.account_code', 'chain.account_code')
            ->join('inventory_transaction_header', 'inventory_transaction_header.transaction_number', 'transaction_offtake.transaction_number')
            ->join('users', 'users.merchandiser_id', 'inventory_transaction_header.merchandiser_id')
            ->leftJoin('material_group_main', 'material_group_main.code', 'material_master_data.main_group')
            ->leftJoin('material_group_sub', 'material_group_sub.code', 'material_master_data.sub_group')
            ->whereDate('transaction_offtake.created_at', $date)
            ->get([
                'transaction_offtake.id',
                'transaction_offtake.transaction_number',
                'transaction_offtake.base_uom',
                'transaction_offtake.beginning_balance',
                'transaction_offtake.delivery',
                'transaction_offtake.rtv',
                'transaction_offtake.physical_count',
                'transaction_offtake.warehouse_area',
                'transaction_offtake.bo_area',
                'transaction_offtake.shelves_area',
                'transaction_offtake.offtake',
                'transaction_offtake.ending_balance',
                'customer_master_data.customer_code',
                'customer_master_data.name AS customer_name',
                'material_master_data.material_code',
                'material_master_data.material_description',
                'chain.chain_code',
                'chain.description AS chain_description',
                'customer_accounts.account_code',
                'customer_accounts.description AS account_description',
                'material_group_main.code AS group_main_code',
                'material_group_main.description AS group_main_description',
                'material_group_sub.code AS group_sub_code',
                'material_group_sub.description AS group_sub_description',
                'transaction_offtake.created_at',
                'transaction_offtake.updated_at'
            ]);

        return $offtakes;
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
}
