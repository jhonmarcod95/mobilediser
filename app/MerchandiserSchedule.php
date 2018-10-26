<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MerchandiserSchedule extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'merchandiser_schedule';

    //excel column names
    public static $ID = 'id';
    public static $BRANCH_CODE = 'branch_code';
    public static $SCHEDULE = 'schedule';
    public static $TIME = 'time';

    public static function parseExcelWeekDay($schedule){
        $result = explode('/', $schedule[static::$SCHEDULE]); #split slashes to get days
        return $result;
    }

    public static function parseExcelTime($schedule){
        $result = explode('-', $schedule[static::$TIME]); #split dashes to get timein and timeout
        return $result;
    }

    public static function parseDates($monthYear, $dayDesc){

        $day = date('N', strtotime($dayDesc));
        /*---- to format 1 = sunday, 7 = saturday ----*/
        $day = $day + 1;
        if($day > 7) $day = 1;
        /*--------------------------------------------*/

        $date = "$monthYear-01";
        $first_day = date('N',strtotime($date));
        $first_day = $day -  $day - $first_day + $day;
        $last_day =  date('t',strtotime($date));
        $days = array();
        for($i=$first_day; $i<=$last_day; $i=$i+7 ){
            if($i > 0){ //avoid negative values
                $days[] = "$monthYear-" . sprintf('%02d', $i);
            }
        }
        return  $days;
    }
}
