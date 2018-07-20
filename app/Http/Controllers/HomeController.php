<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\InventoryTransactionHeader;
use App\MerchandiserSchedule;
use App\TransactionOfftake;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

        $chainOfftakes = $this->getChainOfftakeToday();

        return view('home', compact(
            'msgHeaders',
            'msgCount',
            'inventoryCount',
            'announcementCount',
            'scheduleCount',
            'announcements',
            'chainOfftakes'
        ));
    }


    private function getChainOfftakeToday(){
        $date = Carbon::now()->format('Y-m-d');
//        $date = '2018-07-18';

        $result = TransactionOfftake::whereDate('transaction_offtake.created_at', $date)
            ->join('customer_master_data', 'customer_master_data.customer_code', 'transaction_offtake.customer_code')
            ->join('chain', 'chain.chain_code', 'customer_master_data.chain_code')
            ->join('material_master_data', 'material_master_data.material_code', 'transaction_offtake.material_code')
            ->groupBy(
                'chain_code',
                'material_code'
            )
            ->select(
                DB::raw('SUM(transaction_offtake.offtake) AS offtake'),
                DB::raw('SUM(transaction_offtake.ending_balance) AS ending_balance'),
                DB::raw('MAX(customer_master_data.name) AS name'),
                'chain.chain_code',
                DB::raw('MAX(chain.description) AS description'),
                'material_master_data.material_code',
                DB::raw('MAX(material_master_data.material_description) AS material_description'),
                DB::raw('MAX(transaction_offtake.created_at) AS created_at')
            )
            ->get()
        ;

        return $result;
    }




}
