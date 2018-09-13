<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerGeofence;
use App\Location;

use GeneaLabs\LaravelMaps\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function show()
    {

        $locations = Location::latest()->first();

        #configuration
        $config = [];
        $config['center'] = "$locations->lat, $locations->lng";
        $config['zoom'] = '16';
        $config['map_type'] = 'HYBRID';
        $config['drawing'] = true;

        app('map')->initialize($config);


        $circle['center'] = "$locations->lat, $locations->lng";
        $circle['radius'] = '100';
        $circle['strokeColor'] = '#FF0000';
        $circle['fillOpacity'] = '0.2';

        app('map')->add_circle($circle);



        $marker = [];
        $marker['position'] = "$locations->lat, $locations->lng";
        $marker['flat'] = FALSE;
        app('map')->add_marker($marker);

        $map = app('map')->create_map();

        return view('location.show', compact(
            'locations',
            'map'
        ));
    }

    public function geofences()
    {
        $customerGeofences = DB::table('customer_geofences')
            ->leftJoin('customer_master_data', 'customer_geofences.customer_code', '=', 'customer_master_data.customer_code')
            ->get();

        $customers = Customer::showCodeAndName();

        return view('location.geofence',compact(
            'customerGeofences',
            'customers'
        ));
    }

    public function save(Request $request)
    {
        $validation = $request->validate([
            'customer_code' => 'required',
            'radius' => 'required',
        ]);

        $customer_code = $request->customer_code;
        $center_lat = $request->center_lat;
        $center_lng = $request->center_lng;
        $outer_lat = $request->outer_lat;
        $outer_lng = $request->outer_lng;
        $radius = $request->radius;
        $color = $request->color;
        $customer_code_update = $request->customer_code_update;

        if($customer_code_update == '0') {

            $validation = $request->validate([
                'customer_code' => 'unique:customer_geofences',
            ]);

            #Add
            $customerGeofence = new CustomerGeofence();
            $customerGeofence->customer_code = $customer_code;
            $customerGeofence->geo_center_lat = $center_lat;
            $customerGeofence->geo_center_lng = $center_lng;
            $customerGeofence->radius = $radius;
            $customerGeofence->radius_distance = $customerGeofence->haversineGreatCircleDistance($outer_lat, $outer_lng, $center_lat, $center_lng, 6371);
            $customerGeofence->color = $color;
            $customerGeofence->save();

            alert()->success('Customer geofence has been added.','');
        }
        elseif($customer_code_update == '1') {
            #Edit
            $customer_id_update = $request->customer_id_update;

            $customerGeofence = CustomerGeofence::find($customer_id_update);
            $customerGeofence->geo_center_lat = $center_lat;
            $customerGeofence->geo_center_lng = $center_lng;
            $customerGeofence->radius = $radius;
            $customerGeofence->radius_distance = $customerGeofence->haversineGreatCircleDistance($outer_lat, $outer_lng, $center_lat, $center_lng, 6371);
            $customerGeofence->color = $color;
            $customerGeofence->save();

            alert()->success('Customer geofence has been changed.','');
        }


        return redirect('/locations/geofences');


    }
}
