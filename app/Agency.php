<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class Agency extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'agency_master_data';
    protected $primaryKey = 'agency_code';


    /*
     * returns an array to be used in select tags
     * if third party user : filter only with same agency code
     */
    public static function filterByAgency(){
        if(Auth::user()->hasRole('third.party')){
            $result = Agency::where('agency_code', Auth::user()->agency_code);
        }
        else{
            $result = Agency::all();
        }

        return $result;
    }
}
