<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Agency extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'agency_master_data';
    protected $primaryKey = 'agency_code';
}
