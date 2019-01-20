<?php

namespace App\Rules;

use App\Customer;
use App\MerchandiserSchedule;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleUploadRule implements Rule
{

    private $monthYear;
    private $result;
    private $message;
    private $rowNumber;
    private $defaultRowNumber = 2; //2 because header is included (1)

    public function __construct($monthYear)
    {
        $this->monthYear = $monthYear;
    }

    /*
     *duplicate entries in excel
     */

    public function passes($attribute, $value)
    {
        $this->result = true;

        Excel::load($value->getRealPath(), function ($reader) {

            $schedules = collect($reader->toArray());

            //check if excel columns are valid
            if(!$this->isValidFields($schedules->first())) return;

            //check if ids exist
            if(!$this->isIdsExistAndValid($schedules)) return;

            //check if branch code exist
            if(!$this->isBranchCodesExistAndValid($schedules)) return;

            //check if days is valid format
            if(!$this->isValidDays($schedules)) return;

            //check if valid time format
            if(!$this->isValidTime($schedules)) return;

            //check if already exist
            if(!$this->isAlreadyExist($schedules)) return;


        });


        return $this->result;
    }

    public function message()
    {
        return $this->message;
    }

    private function setMsgText($text){
        $this->message = $text;
        $this->result = false;
    }

    //validation functions

    private function isValidFields($schedule){
        if( !array_key_exists(MerchandiserSchedule::$ID,$schedule)){
            $this->setMsgText('Column `ID` not found.');
            return false;
        }
        else if(!array_key_exists(MerchandiserSchedule::$BRANCH_CODE,$schedule)){
            $this->setMsgText('Column `Branch Code` not found.');
            return false;
        }
        else if(!array_key_exists(MerchandiserSchedule::$SCHEDULE,$schedule)){
            $this->setMsgText('Column `Schedule` not found.');
            return false;
        }
        else if(!array_key_exists(MerchandiserSchedule::$TIME,$schedule)){
            $this->setMsgText('Column `Time` not found.');
            return false;
        }
        return true;
    }

    private function isIdsExistAndValid($schedules){
        $this->rowNumber = $this->defaultRowNumber;

        $schedules = $schedules->unique(MerchandiserSchedule::$ID);

        foreach ($schedules as $schedule) {
            $id = $schedule[MerchandiserSchedule::$ID];

            //check if contains spaces
            if($this->hasWhiteSpaces($id)){
                $this->setMsgText('Column ID must not contain whitespaces near row ' . $this->rowNumber . '.');
                return false;
            }

            //check if exist
            if(!User::where('merchandiser_id', $id)->exists()){
                $this->setMsgText('ID ' . $id .  ' not found near row ' . $this->rowNumber . '.');
                return false;
            }

            //check if inactive (resign)
            if(User::where('merchandiser_id', $id)
                ->where('account_status', 'INACTIVE')
                ->exists()){
                $this->setMsgText('ID ' . $id .  ' is inactive near row ' . $this->rowNumber . '.');
                return false;
            }


            $this->rowNumber++;
        }
        return true;
    }

    private function isBranchCodesExistAndValid($schedules){
        $this->rowNumber = $this->defaultRowNumber;

        $schedules = $schedules->unique(MerchandiserSchedule::$BRANCH_CODE);

        foreach ($schedules as $schedule) {
            $customer_code = $schedule[MerchandiserSchedule::$BRANCH_CODE];

            //check if contains spaces
            if($this->hasWhiteSpaces($customer_code)){
                $this->setMsgText('Column Branch Code must not contain whitespaces at row ' . $this->rowNumber . '.');
                return false;
            }

            //check if exist
            if(!Customer::where('customer_code', $customer_code)->exists()){
                $this->setMsgText('Branch Code ' . $customer_code .  ' not found at row ' . $this->rowNumber . '.');
                return false;
            }
            $this->rowNumber++;
        }
        return true;
    }

    private function isValidDays($schedules){
        $result = true;

        //remove duplicates per line
        $schedules = $schedules->unique(MerchandiserSchedule::$SCHEDULE)->pluck(MerchandiserSchedule::$SCHEDULE);
        foreach ($schedules as $schedule) {
            $days = explode('/', $schedule);

            foreach ($days as $day){
                //remove whitespaces
                $day =  str_replace(' ', '',$day);
                $d[] = strtolower($day);
            }
        }

        $dayFormats = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
        $days = array_unique($d);

        //compare days in excel vs validformats
        $invalidDays = array_diff($days, $dayFormats);

        //check if has mismatch
        if(count($invalidDays) > 0){
            $this->message = 'Invalid day format: ' . implode(', ', $invalidDays) . '.';
            $result = false;
        }

        $this->result = $result;
        return $result;
    }

    private function isValidTime($schedules){
        $schedules = $schedules->unique(MerchandiserSchedule::$TIME);

        foreach ($schedules as $schedule) {
            $time_range = $schedule[MerchandiserSchedule::$TIME];
            $timeLogs = explode('-', $time_range); #split dashes to get timein and timeout

            //check if timerange has dash
            if (strpos($time_range, '-') !== false) {
                $times[] = $timeLogs[0];
                $times[] = $timeLogs[1];
            }
            else{
                $this->result = false;
                $this->message = 'Time schedule: ' . $time_range .  ' must have dash(-).';
                return false;
            }
        }

        $times = array_unique($times);
        foreach ($times as $time){

            //check if contains spaces
            if ( preg_match('/\s/',$time) ){
                $this->result = false;
                $this->message = 'Time must not contain whitespaces.';
                return false;
            }

            //check if valid format (pattern: 05:00pm)
            if(!preg_match("/^(?:1[012]|0[0-9]|[0-9]):[0-5][0-9](am|pm|AM|PM)$/", $time)){
                $this->result = false;
                $this->message = 'Time: ' . $time .  ' is not valid format';
                return false;
            }
        }
        return true;
    }

    private function isAlreadyExist($schedules){
        $this->rowNumber = $this->defaultRowNumber;

        foreach ($schedules as $schedule){
            $weekDays = MerchandiserSchedule::parseExcelWeekDay($schedule);
            foreach ($weekDays as $weekDay){
                $days =  MerchandiserSchedule::parseDates($this->monthYear, $weekDay); #get dates of weekdays
                foreach ($days as $day){

                    $merchandiser_id = $schedule[MerchandiserSchedule::$ID];
                    $customer_code = $schedule[MerchandiserSchedule::$BRANCH_CODE];

                    if(MerchandiserSchedule::where('merchandiser_id', $merchandiser_id)
                        ->where('customer_code', $customer_code)
                        ->where('date', $day)
                        ->exists()){
                        $this->result = false;
                        $this->message = 'Id ' . $merchandiser_id .  ', branch code ' . $customer_code . ', date ' .  Carbon::parse($day)->format('M d, Y (D)') . ' at row ' . $this->rowNumber .  '  is already exist.';
                        return true;
                    }
                }
            }
            $this->rowNumber++;
        }
        return false;
    }

    private function hasWhiteSpaces($text){
        if ( preg_match('/\s/',$text) ){
            return true;
        }
        return false;
    }
}
