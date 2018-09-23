<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'material_master_data';

    public static function showCodeAndDesc(){
        $result = Material::get()
            ->pluck('material_description', 'material_code');

        return $result;
    }
}
