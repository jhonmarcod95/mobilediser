<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ScheduleConflictRule implements Rule
{
    private $merchandiser_id;
    private $date;
    private $startTime;
    private $endTime;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($merchadiser_id, $date, $starTime, $endTime)
    {
        $this->merchandiser_id = $merchadiser_id;
        $this->date = $date;
        $this->startTime = $starTime;
        $this->endTime = $endTime;
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
        $schedules = DB::select("SELECT * FROM vw_schedules WHERE date = '$this->date' AND (merchandiser_id = '$this->merchandiser_id' OR customer_code = '$value')");
//            ->where('date', $this->date)
//            ->orWhere('merchandiser_id', $this->merchandiser_id)
//            ->orWhere('customer_code', $value)
//            ->get();

        foreach ($schedules as $schedule){
            if(($this->startTime <= $schedule->time_in && $this->endTime >= $schedule->time_in) ||
               ($this->startTime <= $schedule->time_out && $this->endTime <= $schedule->time_out)){

                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Unable to add schedule due to conflict to another.';
    }
}
