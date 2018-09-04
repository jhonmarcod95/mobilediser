<?php

namespace App\Http\Controllers\Api;

use App\AttendanceImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceImageController extends Controller
{
    public function upload(Request $request){
        $path = $request->file('img')->store('attendance','public');
        $schedule_id = $request->schedule_id;

        #image
        $attendanceImage = new AttendanceImage();
        $attendanceImage->schedule_id = $schedule_id;
        $attendanceImage->image_path = $path;
        $attendanceImage->save();
        return 'success';
    }
}
