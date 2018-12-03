<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'place_region';

    public static function showCodeAndName(){
        $result = Region::all()
            ->sortBy('description')
            ->pluck('description', 'region_code');

        return $result;
    }
}
