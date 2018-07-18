<?php

namespace App\Http\Controllers;

use App\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function show(){
        $materials = Material::all();

        return view('masterData.material',compact(
            'materials'
        ));
    }

    public function info(Request $request){
        $id = $request->id;
        $isEdit = false;
        $actionUrl = "save";

        if(!empty($id)){
            $material = Material::where('id', $id)
                ->get();

            #if id exist will edit record
            if(count($material)){
                $isEdit = true;
                $actionUrl = "update";
                $material = $material->first();
            }
        }

        return view('masterData.materialInfo',compact(
            'material',
            'isEdit',
            'actionUrl'
        ));
    }

    public function save(Request $request){

        $validation = $request->validate([
            'material_code' => 'required|unique:material_master_data',
            'material_description' => 'required',
            'base_unit' => 'required',
        ]);

        #save
        $material = new Material();
        $material->material_code = $request->material_code;
        $material->material_description = $request->material_description;
        $material->base_unit = $request->base_unit;
        $material->save();

        alert()->success('New Material has been added.','');
        return redirect('/materials');
    }

    public function update(Request $request){

        $id = $request->id;
        $validation = $request->validate([
            'material_code' => 'required|unique:material_master_data,material_code,' . $id,
            'material_description' => 'required',
            'base_unit' => 'required',
        ]);

        #update
        $material = Material::find($id);
        $material->material_code = $request->material_code;
        $material->material_description = $request->material_description;
        $material->base_unit = $request->base_unit;
        $material->save();

        alert()->success('Material info has been updated.','');
        return redirect('/materials');
    }
}
