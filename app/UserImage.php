<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserImage extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'merchandiser_picture';
    protected $primaryKey = 'user_merchandiser_id';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
