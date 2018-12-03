<?php

namespace App\Http\Controllers;

use App\Island;
use Illuminate\Http\Request;

class IslandController extends Controller
{

    public function all(){
        return Island::all();
    }
}
