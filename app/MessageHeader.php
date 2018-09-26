<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class MessageHeader extends Model
{
    protected $table = 'merchandiser_message_header';
    protected $primaryKey = 'message_id';
}
