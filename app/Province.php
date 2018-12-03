<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'place_province';

    public static function showCodeAndName(){
        $result = Province::all()
            ->sortBy('description')
            ->pluck('description', 'provincial_code');

        return $result;
    }

}
