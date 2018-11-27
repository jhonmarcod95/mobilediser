<?php

namespace App\Http\Controllers;

use App\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{

    public function index(){
        return view('masterData.agency.index');
    }

    public function indexData(Request $request){
        $paginate = $request->paginate;
        $search = $request->search;

        return Agency::where('name', 'LIKE', '%' . $search . '%')
            ->paginate($paginate);
    }

    public function save(Request $request){

        $request->validate([
            'name' => 'required|min:10',
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

        return $agency;
    }

    public function update(Request $request){

        $agency_code = $request->agency_code;
        $request->validate([
            'name' => 'required|min:10',
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

        return $agency;
    }


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


}
