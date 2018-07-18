<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    protected $table = 'merchandiser_picture';
    protected $primaryKey = 'user_merchandiser_id';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
