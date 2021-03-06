<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerMaterial extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'customer_material';
}
