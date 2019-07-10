<?php

namespace App\Http\Requests;

use App\Rules\DaysLimitRule;
use Illuminate\Foundation\Http\FormRequest;

class FilterOfftake extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $report_type = $this->report_type;

        if($report_type == '1'){
            return [
                'customer_account' => 'required',
                'date_from' => [new DaysLimitRule($this->date_to), 'required', 'date'],
                'date_to' => 'required|date|after_or_equal:date_from',
            ];
        }
        else if($report_type == '2'){
            return [
                'chain_codes' => 'required',
                'date_from' => [new DaysLimitRule($this->date_to), 'required', 'date'],
                'date_to' => 'required|date|after_or_equal:date_from',
            ];
        }

        else if($report_type == '3'){
            return [
                'customer_codes' => 'required',
                'date_from' => [new DaysLimitRule($this->date_to), 'required', 'date'],
                'date_to' => 'required|date|after_or_equal:date_from',
            ];
        }

    }
}
