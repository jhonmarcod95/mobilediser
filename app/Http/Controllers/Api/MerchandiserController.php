<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class MerchandiserController extends Controller
{
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $merchandiser = User::where('username',$username)->first();

        if(!empty($merchandiser))
        {
            if (Hash::check($password,$merchandiser->password))
            {
                $result = ['items' => [$merchandiser]];
                return $result;
            }
            else
            {
                return "wrong_password";
            }
        }
        else
        {
            return "failed" ;
        }
    }

    public function updatecontactinformation(Request $request)
    {
        $merchandiser_id = $request->merchandiser_id;
        $email = $request->email;
        $contact_number = $request->contact_number;
        $address = $request->address;

        if(empty($merchandiser_id) || empty($email) || empty($contact_number) || empty($address))
        {
            return "failure";
        }
        else
        {
            $merchandiser = User::find($merchandiser_id);
            $merchandiser->email = $email;
            $merchandiser->contact_number = $contact_number;
            $merchandiser->address = $address;
            $saved = $merchandiser->save();

            if($saved)
            {
                return "success";
            }
            else
            {
                return "failed";
            }
        }
    }

    public function changepassword(Request $request)
    {
        $merchandiser_id = $request->merchandiser_id;
        $current_password = $request->current_password;
        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;
        $merchandiser = User::find($merchandiser_id);

        if (Hash::check($current_password,$merchandiser->password))
        {
            if(strlen($new_password) >= 6)
            {
                if ($confirm_password==$new_password)
                {
                    $merchandiser->password = Hash::make($new_password);
                    $saved = $merchandiser->save();

                    if($saved)
                    {
                        return "success";
                    }
                    else
                    {
                        return "failed";
                    }
                }
                else
                {
                    return "password_not_matched";
                }
            }
            else
            {
                return "short_password";
            }
        }
        else
        {
            return "wrong_password";
        }
    }
}
