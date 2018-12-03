<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Municipality extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'place_municipality';

    public static function showCodeAndName(){
        $result = Municipality::all()
            ->sortBy('description')
            ->pluck('description', 'municipality_code');

        return $result;
    }
}
