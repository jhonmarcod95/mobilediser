<?php

namespace App\Http\Controllers;

use App\TransactionOfftake;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function offtakePerCustomer(){
        $materialOfftakes = TransactionOfftake::getMaterialOfftake();

        return view('report.offtakePerCustomer', compact(
            'materialOfftakes'
        ));
    }

    public function merchandiserLog(){


        return view('report.merchandiserLog'
        );
    }



}
