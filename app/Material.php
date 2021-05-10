<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Material extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'material_master_data';

    public static function showCodeAndDesc(){
        $result = Material::get()
            ->pluck('material_description', 'material_code');

        return $result;
    }

    public function mainGroup(){
        return $this->belongsTo(MaterialGroupMain::class, 'main_group', 'code');
    }

    public function subGroup(){
        return $this->belongsTo(MaterialGroupSub::class, 'sub_group', 'code');
    }
}
