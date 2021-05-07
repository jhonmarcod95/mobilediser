<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class Customer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'customer_master_data';
    protected $primaryKey = 'customer_id';


    public static function showCodeAndName(){
        $result = Customer::select(DB::raw("CONCAT(name, ' - ', branch) AS name"), 'customer_code')
            ->get()
            ->sortBy('name')
            ->pluck('name', 'customer_code');

        return $result;
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function chain(){
        return $this->belongsTo(Chain::class, 'chain_code', 'chain_code');
    }
}
