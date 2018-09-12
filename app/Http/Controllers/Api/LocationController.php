<?php

namespace App\Http\Controllers\Api;

use App\CustomerGeofence;
use App\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function save(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;
        $user_id = $request->user_id;

        $location = new Location();
        $location->lat = $lat;
        $location->lng = $lng;
        $location->user_merchandiser_id = $user_id;


        if ($location->save()) {
            $result = ['status' => 'success'];
        } else {
            $result = ['status' => 'failed'];
        }

        return $result;

    }

    public function check(Request $request)
    {
        $result = 'failed';

        #location of merchandiser
        $curr_lat = $request->lat;
        $curr_lng = $request->lng;
        $customer_code = $request->customer_code;

        $customer_geofence = CustomerGeofence::where('customer_code', $customer_code)
            ->first();


        if(!empty($customer_geofence))
        {
            $customer_lat = $customer_geofence->geo_center_lat;
            $customer_lng = $customer_geofence->geo_center_lng;
            $radius_distance = $customer_geofence->radius_distance;

            $distance = CustomerGeofence::haversineGreatCircleDistance($curr_lat, $curr_lng, $customer_lat, $customer_lng, 6371);

            /*------- save location (for checking only) ---------*/
            $location = new Location();
            $location->lat = $customer_lat;
            $location->lng = $customer_lng;
            $location->customer_code = $customer_code;
            $location->save();
            /*---------------------------------------------------*/

            if($distance < $radius_distance){
                $result = 'success';
            }
        }

        return $result;

    }

}
