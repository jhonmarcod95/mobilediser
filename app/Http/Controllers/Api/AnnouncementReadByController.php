<?php

namespace App\Http\Controllers\api;

use App\AnnouncementReadBy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnnouncementReadByController extends Controller
{
    public function insertreadby(Request $request)
    {
    	$announcement_id = $request->announcement_id;
        $user_id = $request->user_id;
    }
}
