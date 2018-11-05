<?php

namespace App\Rules;

use App\MerchandiserSchedule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ScheduleConflictRule implements Rule
{
    private $schedule_id;
    private $merchandiser_id;
    private $dates;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($schedule_id, $merchadiser_id, $dates)
    {
        $this->schedule_id = $schedule_id;
        $this->merchandiser_id = $merchadiser_id;
        $this->dates = $dates;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $schedule_ids = $this->schedule_id;
        $dates = $this->dates;
        $merchandiser_id = $this->merchandiser_id;

        //for saving
        if(empty($schedule_ids)){
            foreach ($dates as $date){
                if(MerchandiserSchedule::where('merchandiser_id', $merchandiser_id)
                    ->where('customer_code', $value)
                    ->where('status', '002')
                    ->whereDate('date', $date)
                    ->exists()){
                    return false;
                }
            }
        }
        //for updating
        else{
            if(MerchandiserSchedule::where('merchandiser_id', $merchandiser_id)
                ->where('customer_code', $value)
                ->where('status', '002')
                ->whereIn('date', $dates)
                ->whereNotIn('id', $schedule_ids)
                ->exists()){
                return false;
            }
            return true;
        }

//        $schedules = DB::select("SELECT * FROM vw_schedules WHERE date = '$this->date' AND (merchandiser_id = '$this->merchandiser_id' OR customer_code = '$value')");
//
//        foreach ($schedules as $schedule){
//            if(($this->startTime <= $schedule->time_in && $this->endTime >= $schedule->time_in) ||
//               ($this->startTime <= $schedule->time_out && $this->endTime <= $schedule->time_out)){
//
//                return false;
//            }
//        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Schedule already exists.';
    }
}
