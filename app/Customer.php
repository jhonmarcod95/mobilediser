<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    protected $table = 'customer_master_data';
    protected $primaryKey = 'customer_id';


    public static function showCodeAndName(){
        $result = Customer::select(DB::raw("CONCAT(name, ' - ', branch) AS name"), 'customer_code')
            ->get()
            ->pluck('name', 'customer_code');

        return $result;
    }
}
