<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MerchandiserIdRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if(count($value) === count(array_flip($value))){
            return true; //duplicate detected
        }
        return false;
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Duplicate merchandiser is not allowed.';
    }
}
