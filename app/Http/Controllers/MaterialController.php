<?php

namespace App\Http\Controllers;

use App\Material;
use App\MaterialGroupMain;
use App\MaterialGroupSub;
use Illuminate\Http\Request;

class MaterialController extends Controller
{

    public function index(){
        $materialMainGroups = MaterialGroupMain::get()->pluck('description', 'code');
        $materialSubGroups = MaterialGroupSub::get()->pluck('description', 'code');

        return view('masterData.material.index', compact(
            'materialMainGroups',
            'materialSubGroups'
        ));
    }

    public function indexData(Request $request){
        $paginate = $request->paginate;
        $search = $request->search;

        return Material::leftjoin('material_group_main', 'material_group_main.code', 'material_master_data.main_group')
            ->leftjoin('material_group_sub', 'material_group_sub.code', 'material_master_data.sub_group')
            ->where('material_description', 'LIKE', '%' . $search . '%')
            ->orWhere('material_code', 'LIKE', '%' . $search . '%')
            ->select([
                'material_master_data.id',
                'material_master_data.material_code',
                'material_master_data.material_description',
                'material_master_data.base_unit',
                'material_master_data.main_group',
                'material_group_main.description AS main_group_description',
                'material_master_data.sub_group',
                'material_group_sub.description AS sub_group_description',
            ])
            ->paginate($paginate);
    }

    public function save(Request $request){

        $request->validate([
            'material_code' => 'required|unique:material_master_data',
            'material_description' => 'required',
            'base_unit' => 'required',
            'main_category' => 'required',
            'sub_category' => 'required',
        ]);

        #save
        $material = new Material();
        $material->material_code = $request->material_code;
        $material->material_description = $request->material_description;
        $material->base_unit = $request->base_unit;
        $material->main_group = $request->main_category;
        $material->sub_group = $request->sub_category;
        $material->save();

        return $material;
    }

    public function update(Request $request){

        $request->validate([
            'material_code' => 'required|unique:material_master_data,material_code,' . $request->id,
            'material_description' => 'required',
            'base_unit' => 'required',
            'main_category' => 'required',
            'sub_category' => 'required',
        ]);

        #update
        $material = Material::find($request->id);
        $material->material_code = $request->material_code;
        $material->material_description = $request->material_description;
        $material->base_unit = $request->base_unit;
        $material->main_group = $request->main_category;
        $material->sub_group = $request->sub_category;
        $material->save();

        return $material;
    }
}
