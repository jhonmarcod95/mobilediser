<?php

namespace App\Http\Controllers;

use App\AccountType;
use App\Agency;
use App\User;
use App\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use jeremykenedy\LaravelRoles\Models\Role;

class UserController extends Controller
{
    public function show(){

        $users = DB::table('vw_merchandiser')
            ->get();

        return view('masterData.user',compact(
            'users'
        ));
    }

    public function register(Request $request){
        $id = $request->id;
        $isEdit = false;
        $actionUrl = "save";

        if(!empty($id)){
            $user = User::where('merchandiser_id', $id)->get();
            #if id exist will edit record
            if(count($user)){
                $isEdit = true;
                $actionUrl = "update";
                $user = $user->first();
            }
        }

        $accountType = AccountType::get()->pluck('type', 'id');
        $agency = Agency::get()->pluck('name', 'agency_code');

        return view('masterData.userRegister',compact(
            'accountType',
            'agency',
            'isEdit',
            'actionUrl',
            'user'
        ));
    }

    public function save(Request $request){

        $validation = $request->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'agency' => 'required',
            //'contact_number' => 'required|unique:users|digits:11',
            'password' => 'required',
            'gender' => 'required',
            'birthday' => 'required',
            'address' => 'required',
            'accountType' => 'required',
            'accountStatus' => 'required',
            'username' => 'required|unique:users',
            //'email' => 'required|unique:users|email',
        ]);


        if(empty($request->file('img'))){
            $path = "avatars/avatar.png";
        }
        else{
            $path = $request->file('img')->store('avatars','public');
        }


        #user
        $user = new User();
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->agency_code = $request->agency;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->api_token = Hash::make(str_random(8));
        $user->gender = $request->gender;
        $user->birth_date = $request->birthday;
        $user->address = $request->address;
        $user->email = $request->email;
        $user->contact_number = $request->contact_number;
        $user->account_type = $request->accountType;
        $user->account_status = $request->accountStatus;

        #image
        $userImage = new UserImage();
        $userImage->image_path = $path;
        $userImage->user()->associate($user);
        $userImage->save();

        alert()->success('New user has been registered.','');
        return redirect('/users');
    }

    public function update(Request $request){

        $id = $request->merchandiser_id;
        $validation = $request->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'agency' => 'required',
            'contact_number' => 'required|unique:users,contact_number,' . $id . ',merchandiser_id|digits:11',
            'password' => 'required',
            'gender' => 'required',
            'birthday' => 'required',
            'address' => 'required',
            'accountType' => 'required',
            'accountStatus' => 'required',
            'username' => 'required|unique:users,username,' . $id . ',merchandiser_id',
            'email' => 'required|unique:users,email,' . $id . ',merchandiser_id',
        ]);


        #user
        $user = User::find($id);
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->agency_code = $request->agency;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->api_token = Hash::make(str_random(8));
        $user->gender = $request->gender;
        $user->birth_date = $request->birthday;
        $user->address = $request->address;
        $user->email = $request->email;
        $user->contact_number = $request->contact_number;
        $user->account_type = $request->accountType;
        $user->account_status = $request->accountStatus;
        $user->save();

        #image
        if(!empty($request->file('img'))){ #will not update image path if no upload
            $userImage = UserImage::find($id);
            $path = $request->file('img')->store('avatars','public');

//            $path = $request->file('img')->storeAs(
//                'avatars', 'kanor','public'
//            );

            $userImage->image_path = $path;
            $userImage->save();
        }

        alert()->success('User has been updated.','');
        return redirect('/users');
    }
}
