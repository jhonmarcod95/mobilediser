<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MessageHeader extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'merchandiser_message_header';
    protected $primaryKey = 'message_id';
}
