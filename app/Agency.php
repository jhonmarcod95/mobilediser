<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $table = 'agency_master_data';
    protected $primaryKey = 'agency_code';
}
