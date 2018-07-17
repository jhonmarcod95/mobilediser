<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer_master_data';
    protected $primaryKey = 'customer_id';

}
