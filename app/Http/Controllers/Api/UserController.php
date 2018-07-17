<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    public function show()
    {
        $users = User::all();
        $items = ['items' => $users];

        return $items;
    }

    public function search(Request $request)
    {
        $id = $request->id;

        $user = User::where('id', $id)->get();
        $items = ['items' => $user];

        return $items;
    }

    public function insert(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        if(empty($name) || empty($email) || empty($password)){
            return "failure";
        }
        else{

            $api_token = str_random(60);

            $user = new User;
            $user->name = $name;
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->api_token = $api_token;

            $saved = $user->save();

            if($saved){
                return "success";
            }
            else{
                return "failed";
            }
        }
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        if(empty($id) || empty($name) || empty($email) || empty($password)){
            return "failure";
        }
        else{
            $user = User::find($id);
            $user->name = $name;
            $user->email = $email;
            $user->password = bcrypt($password);
            $saved = $user->save();

            if($saved){
                return "success";
            }
            else{
                return "failed";
            }
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id;

        if(empty($id)){
            return "failure";
        }

        $remarks = User::find($id);
        $deleted = $remarks->delete();

        if($deleted){
            return "success";
        }
        else{
            return "failed";
        }
    }




}
