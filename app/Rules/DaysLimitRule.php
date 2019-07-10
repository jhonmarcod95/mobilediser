<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class DaysLimitRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $date_to;

    public function __construct($date_to)
    {
        $this->date_to = $date_to;
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
        $date_to = Carbon::parse($this->date_to);
        $date_from = Carbon::parse($value);

        $diff = $date_to->diffInDays($date_from);

        if ($diff > 31){
            return false;
        }
        else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Only 31 days filter allowed.';
    }
}
