<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use OwenIt\Auditing\Contracts\Auditable;


class User extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasRoleAndPermission;

    protected $primaryKey = 'merchandiser_id';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function userImage()
    {
        return $this->hasOne(UserImage::class);
    }

    public function fma(){
        return $this->belongsToMany(Fma::class);
    }

    public function coordinator(){
        return $this->belongsToMany(Coordinator::class);
    }

    public static function getName($id){
        $result = User::select(DB::raw("CONCAT(last_name, ' ', first_name) AS fullname"))
            ->where('merchandiser_id', $id)
            ->pluck('fullname')
            ->first();

        return $result;
    }



}
