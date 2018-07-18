<?php

namespace App\Http\Controllers;

use App\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function add(Request $request)
    {
        $validation = $request->validate([
            'message' => 'required',
        ]);

        $announcement = new Announcement();

        $announcement->user_id = Auth::user()->merchandiser_id;
        $announcement->message = $request->message;
        $announcement->save();

        return redirect('/');
    }
}
