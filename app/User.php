<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
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
        return $this->belongsToMany(User::class);
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

    /*
    * returns an array to be used in select tags
    * if third party user : filter only with same agency code
    */
    public static function filterByAgency(){
        if(Auth::user()->hasRole('third.party')){
            $result = User::select(
                DB::raw("CONCAT(first_name, ' ', last_name) AS fullname"), 'merchandiser_id')
                ->where('account_type', 3)
                ->where('agency_code', Auth::user()->agency_code);
        }
        else{
            $result = User::select(
                DB::raw("CONCAT(first_name, ' ', last_name) AS fullname"), 'merchandiser_id')
                ->where('account_type', 3);
        }
        return $result;
    }

}
