<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerCategory extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'customer_accounts';

    public static function showCodeAndName(){
        $result = CustomerCategory::all()
            ->sortBy('description')
            ->pluck('description', 'account_code');

        return $result;
    }
}
