<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MerchandiserSchedule extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'merchandiser_schedule';
}
