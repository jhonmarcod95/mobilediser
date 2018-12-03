<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Island extends Model
{
    protected $table = 'place_island_group';

    public static function showCodeAndName(){
        $result = Island::all()
            ->sortBy('description')
            ->pluck('description', 'island_group_code');

        return $result;
    }

}
