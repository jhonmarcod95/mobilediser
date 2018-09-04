<?php

namespace App\Rules;

use App\Http\Controllers\ScheduleController;
use App\MerchandiserSchedule;
use Illuminate\Contracts\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleUploadRule implements Rule
{

    private $monthYear;
    private $result;
    private $merchandiserSchedule;
    private $message;

    public function __construct($monthYear)
    {
        $this->monthYear = $monthYear;
    }

    /*
     *excel collumn checking
     *check if merchandiser id in excel is in db - x
     *check if customer code in excel is in db - x
     *check if has conflict to another - x
     *duplicate entries in excel
     *check if already added in db
     *
     *
     */

    public function passes($attribute, $value)
    {
        $this->result = true;


        Excel::load($value->getRealPath(), function ($reader) {
            $schedules = collect($reader->toArray());

            foreach ($schedules as $schedule){

                if( !array_key_exists('id',$schedule) ||
                    !array_key_exists('code',$schedule) ||
                    !array_key_exists('day',$schedule) ||
                    !array_key_exists('time',$schedule)){

                    $this->result = false;
                    $this->message = "Invalid Excel Format.";
                    return;
                }

                /*----------------- get time --------------------------*/
                $timeLog = explode('-', $schedule['time']); #split dashes to get timein and timeout
                $startTime = date("H:i", strtotime($timeLog[0]));
                $endTime = date("H:i", strtotime($timeLog[1]));
                /*-----------------------------------------------------*/

                $weekDays = explode('/', $schedule['day']); #split slashes to get days

                foreach ($weekDays as $weekDay){
                    $days = ScheduleController::getDates($this->monthYear, $weekDay); #get dates of weekdays
                    foreach ($days as $day){
                        #collects all schedule in date format
                        $this->merchandiserSchedule[] = (object)[
                            'merchandiser_id' => $schedule['id'],
                            'customer_code' => $schedule['code'],
                            'date' => $day,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                        ];

                        #check if has duplicate entry in excel
                        $merchandiserSchedule = collect($this->merchandiserSchedule);
                        if($merchandiserSchedule->where('merchandiser_id',$schedule['id'])
                            ->where('customer_code', $schedule['code'])
                            ->where('date',$day)
                            ->count() > 1){

                            $this->result = false;
                            $this->message = "Duplicate entry found in excel file.";
                            return;
                        }

                        #check if existing in db
                        else if(MerchandiserSchedule::where('merchandiser_id', $schedule['id'])
                            ->where('customer_code', $schedule['code'])
                            ->where('date', $day)
                            ->exists()){

                            $this->result = false;
                            $this->message = "Schedule is already added.";
                            return;
                        }
                    }
                }
            }
        });


        return $this->result;
    }

    public function message()
    {
        return $this->message;
    }
}
