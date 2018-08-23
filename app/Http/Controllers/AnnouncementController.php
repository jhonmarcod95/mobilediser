<?php

namespace App\Http\Controllers;

use App\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function show(){

        $announcements = Announcement::join('users', 'announcement.user_id', 'users.merchandiser_id')
            ->get([
                'announcement.id',
                'announcement.message',
                'users.last_name',
                'users.first_name',
                'announcement.created_at'
            ])
            ->sortByDesc('id');


        return view('announcement.show',compact(
            'announcements'
        ));
    }

    public function edit($id){

        $announcement = Announcement::where('announcement.id', $id)
            ->join('users', 'announcement.user_id', 'users.merchandiser_id')
            ->get([
                'announcement.id',
                'announcement.message',
                'users.last_name',
                'users.first_name',
                'announcement.created_at'
            ])
            ->first();

        return view('announcement.info', compact(
            'announcement'
        ));
    }

    public function post(Request $request)
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

    public function add(Request $request)
    {
        $validation = $request->validate([
            'message' => 'required',
        ]);

        $announcement = new Announcement();
        $announcement->user_id = Auth::user()->merchandiser_id;
        $announcement->message = $request->message;
        $announcement->save();

        return redirect('/announcements');
    }

    public function update($id, Request $request){

        $validation = $request->validate([
            'message' => 'required',
        ]);

        $btnClicked = $request->submit;

        #for updating
        if($btnClicked == 'Save Changes'){
            $announcement = Announcement::find($id);
            $announcement->message = $request->message;
            $announcement->save();

            alert()->success('Announcement Message has been updated.','');
        }

        #for deletion
        elseif($btnClicked == 'Delete'){
            $announcement = Announcement::find($id);
            $announcement->delete();

            alert()->success('Announcement Message has been deleted.','');
        }

        return redirect('/announcements');
    }
}
