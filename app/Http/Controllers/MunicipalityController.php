<?php

namespace App\Http\Controllers;

use App\Municipality;
use App\Island;
use App\Province;
use App\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MunicipalityController extends Controller
{
    public function show(){
        $municipalities = DB::select('CALL p_municipalities');

        return view('masterData.municipality',compact(
            'municipalities'
        ));
    }

    public function info(Request $request){
        $id = $request->id;
        $isEdit = false;
        $actionUrl = "save";

        if(!empty($id)){
            $municipality = Municipality::where('id', $id)
                ->get();

            #if id exist will edit record
            if(count($municipality)){
                $isEdit = true;
                $actionUrl = "update";
                $municipality = $municipality->first();
            }
        }

        $provinces = Province::get()->pluck('description', 'provincial_code');
        #$regions = Region::get()->pluck('description', 'region_code');
        #$islands = Island::get()->pluck('description', 'island_group_code');

        return view('masterData.municipalityInfo',compact(
            'municipality',
            'isEdit',
            'actionUrl',
            'provinces'
        ));
    }

    public function save(Request $request){

        $validation = $request->validate([
            'municipality_code' => 'required|unique:place_municipality',
            'description' => 'required',
            'provincial_code' => 'required',
        ]);

        #save
        $municipality = new Municipality();
        $municipality->municipality_code = $request->municipality_code;
        $municipality->description = $request->description;
        $municipality->provincial_code = $request->provincial_code;
        $municipality->save();

        alert()->success('New Municipality has been added.','');
        return redirect('/municipalities');
    }

    public function update(Request $request){

        $id = $request->id;
        $validation = $request->validate([
            'municipality_code' => 'required|unique:place_municipality,municipality_code,' . $id,
            'description' => 'required',
            'provincial_code' => 'required',
        ]);

        #update
        $municipality = Municipality::find($id);
        $municipality->municipality_code = $request->municipality_code;
        $municipality->description = $request->description;
        $municipality->provincial_code = $request->provincial_code;
        $municipality->save();

        alert()->success('Municipality info has been updated.','');
        return redirect('/municipalities');
    }
}
