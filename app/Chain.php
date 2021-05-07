<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chain extends Model
{
    protected $table = 'chain';

    public static function showCodeAndName(){
        $result = Chain::all()
            ->sortBy('description')
            ->pluck('description', 'chain_code');

        return $result;
    }

    public function account(){
        return $this->belongsTo(CustomerAccount::class, 'account_code', 'account_code');
    }
}
