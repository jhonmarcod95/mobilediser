<?php

namespace App\Http\Controllers;

use App\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function show(){

        $agencies = Agency::get();

        return view('masterData.agency',compact(
            'agencies'
        ));
    }

    public function info(Request $request){
        $id = $request->id;
        $isEdit = false;
        $actionUrl = "save";


        if(!empty($id)){
            $agency = Agency::where('agency_code', $id)->get();
            #if id exist will edit record
            if(count($agency)){
                $isEdit = true;
                $actionUrl = "update";
                $agency = $agency->first();
            }
        }

        return view('masterData.agencyInfo',compact(
            'agency',
            'isEdit',
            'actionUrl',
            'agency'
        ));
    }

    public function save(Request $request){

        $validation = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'contact_number' => 'required|unique:agency_master_data',
            'contact_person' => 'required',
        ]);

        #user
        $agency = new Agency();
        $agency->name = $request->name;
        $agency->address = $request->address;
        $agency->contact_number = $request->contact_number;
        $agency->contact_person = $request->contact_person;
        $agency->save();

        alert()->success('New Agency has been added.','');
        return redirect('/agencies');
    }

    public function update(Request $request){

        $agency_code = $request->agency_code;
        $validation = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'contact_number' => 'required|unique:agency_master_data,contact_number,' . $agency_code . ',agency_code',
            'contact_person' => 'required',
        ]);

        #agency
        $agency = Agency::find($agency_code);
        $agency->name = $request->name;
        $agency->address = $request->address;
        $agency->contact_number = $request->contact_number;
        $agency->contact_person = $request->contact_person;
        $agency->save();

        alert()->success('Agency info has been updated.','');
        return redirect('/agencies');
    }
}
