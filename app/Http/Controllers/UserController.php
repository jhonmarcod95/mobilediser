<?php

namespace App\Http\Controllers;

use App\AccountType;
use App\Agency;
use App\Coordinator;
use App\User;
use App\UserImage;
use App\UserManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use jeremykenedy\LaravelRoles\Models\Role;


class UserController extends Controller
{

    public function index(){
        $accountTypes = AccountType::get()->pluck('type', 'id');
        $agencies = Agency::get()->pluck('name', 'agency_code');
        $roles = Role::get()->pluck('name', 'id');
        $coordinators = Coordinator::get()->pluck('name', 'id');

        $managers = User::whereHas('roles', function($q){
            $q->where('name', 'fma')
              ->orWhere('name', 'fms');
        })
        ->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'merchandiser_id')
        ->pluck('name', 'merchandiser_id');


        return view('masterData.user.index',compact(
            'accountTypes',
            'agencies',
            'roles',
            'coordinators',
            'managers'
        ));
    }

    public function indexData(Request $request){
        $paginate = $request->paginate;
        $search = $request->search;

        //it user
        if(Auth::user()->account_type == '1'){
            $users = User::leftJoin('merchandiser_picture', 'merchandiser_picture.user_merchandiser_id', 'users.merchandiser_id')
                ->leftJoin('agency_master_data', 'agency_master_data.agency_code', 'users.agency_code')
                ->leftJoin('role_user', 'role_user.user_merchandiser_id', 'users.merchandiser_id')
                ->leftJoin('roles', 'roles.id', 'role_user.role_id')
                ->leftJoin('coordinator_user', 'coordinator_user.user_merchandiser_id', 'users.merchandiser_id')
                ->leftJoin('coordinators', 'coordinator_user.coordinator_id', 'coordinators.id')
                ->where(function ($query) use ($search) {
                    $query->where('users.last_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.first_name', 'LIKE', '%' . $search . '%');
                })
                ->select([
                    'users.merchandiser_id AS merchandiser_id',
                    'users.last_name AS last_name',
                    'users.first_name AS first_name',
                    'users.agency_code AS agency_code',
                    'users.username AS username',
                    'users.gender AS gender',
                    'users.birth_date AS birth_date',
                    'users.address AS address',
                    'users.contact_number AS contact_number',
                    'users.account_status AS account_status',
                    'role_user.role_id AS role_id',
                    'roles.name AS role',
                    'coordinators.id AS coordinator_id',
                    'coordinators.name AS coordinator',
                    'merchandiser_picture.image_path AS image_path',
                    'agency_master_data.name AS agency_name',
                    'users.email AS email',
                    'users.account_type AS account_id',
                    'users.created_at AS created_at',
                    'users.updated_at AS updated_at'
                ])
                ->paginate($paginate);
        }
        //admin user
        else{
            $users = User::leftJoin('merchandiser_picture', 'merchandiser_picture.user_merchandiser_id', 'users.merchandiser_id')
                ->leftJoin('agency_master_data', 'agency_master_data.agency_code', 'users.agency_code')
                ->leftJoin('role_user', 'role_user.user_merchandiser_id', 'users.merchandiser_id')
                ->leftJoin('roles', 'roles.id', 'role_user.role_id')
                ->leftJoin('coordinator_user', 'coordinator_user.user_merchandiser_id', 'users.merchandiser_id')
                ->leftJoin('coordinators', 'coordinator_user.coordinator_id', 'coordinators.id')
                ->where(function ($query) use ($search) {
                    $query->where('users.last_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.first_name', 'LIKE', '%' . $search . '%');
                })
                ->where('users.account_type', '<>', '1')
                ->select([
                    'users.merchandiser_id AS merchandiser_id',
                    'users.last_name AS last_name',
                    'users.first_name AS first_name',
                    'users.agency_code AS agency_code',
                    'users.username AS username',
                    'users.gender AS gender',
                    'users.birth_date AS birth_date',
                    'users.address AS address',
                    'users.contact_number AS contact_number',
                    'users.account_status AS account_status',
                    'role_user.role_id AS role_id',
                    'roles.name AS role',
                    'coordinators.id AS coordinator_id',
                    'coordinators.name AS coordinator',
                    'merchandiser_picture.image_path AS image_path',
                    'agency_master_data.name AS agency_name',
                    'users.email AS email',
                    'users.account_type AS account_id',
                    'users.created_at AS created_at',
                    'users.updated_at AS updated_at'
                ])
                ->paginate($paginate);
        }

        return $users;
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

        $request->validate([
            'last_name' => 'required|max:191',
            'first_name' => 'required|max:191',
            'role' => 'required',
            'agency' => 'required',
            'password' => 'required|min:6',
            'gender' => 'required',
            'birthday' => 'required',
            'address' => 'required|max:191',
            'account_status' => 'required',
            'username' => 'required|unique:users|max:30',
            'email' => 'required|unique:users|email|max:191',
        ]);

        // contact number validation (scenario: this will allow to blank contact number of inactive user then will assigned to new one.
        if($request->account_status == 'ACTIVE'){
            $request->validate([
                'contact_number' => 'required|unique:users|digits:11',
            ]);
        }

        // merchandiser role additional validation
        if($request->role == '3'){
            $request->validate([
                'coordinator' => 'required',
                'managers' => 'required'
            ]);
        }

        // store image in folder
        if(empty($request->file('img_user'))){
            $path = "avatars/avatar.png";
        }
        else{
            $path = $request->file('img_user')->store('avatars','public');
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
        $user->account_type = $this->getAccountType($request->role);
        $user->account_status = $request->account_status;

        if($user->save()){

            // Assigning of role
            $user->syncRoles($request->role);

            // Assigning of fma & coordinator if merchandiser role
            if($request->role == '3'){
//                $user->coordinator()->sync((array) $request->coordinator);
//                $user->fma()->sync((array) $request->fma);
                $this->storeUserManagers($user->merchandiser_id,$request->managers);
            }

            #image
            $userImage = new UserImage();
            $userImage->image_path = $path;
            $userImage->user()->associate($user);
            $userImage->save();
        }

        return $user;
    }

    public function update(Request $request){

        $id = $request->merchandiser_id;

        $request->validate([
            'last_name' => 'required|max:191',
            'first_name' => 'required|max:191',
            'role' => 'required',
            'agency' => 'required',
            'gender' => 'required',
            'birthday' => 'required',
            'address' => 'required|max:191',
            'account_status' => 'required',
            'username' => 'required|unique:users,username,' . $id . ',merchandiser_id|max:30',
            'email' => 'required|unique:users,email,' . $id . ',merchandiser_id|max:191',
        ]);

        // contact number validation (scenario: this will allow to blank contact number of inactive user then will assigned to new one.
        if($request->account_status == 'ACTIVE'){
            $request->validate([
                'contact_number' => 'required|unique:users,contact_number,' . $id . ',merchandiser_id|digits:11',
            ]);
        }

        // merchandiser role additional validation
        if($request->role == '3'){
            $request->validate([
                'coordinator' => 'required',
                'managers' => 'required'
            ]);
        }

        #user
        $user = User::find($id);
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->agency_code = $request->agency;
        $user->username = $request->username;

        #update password if password field is inputted
        if(!empty($request->password)){
            $user->password = bcrypt($request->password);
        }

        $user->gender = $request->gender;
        $user->birth_date = $request->birthday;
        $user->address = $request->address;
        $user->email = $request->email;
        $user->contact_number = $request->contact_number;
        $user->account_type = $this->getAccountType($request->role);
        $user->account_status = $request->account_status;

        if($user->save()){

            // Assigning of role
            $user->syncRoles($request->role);

            // Assigning of fma & coordinator if merchandiser role
            if($request->role == '3'){
                $this->storeUserManagers($id,$request->managers);
            }

            #image
            if(!empty($request->file('img_user'))){ #will not update image path if no upload
                $userImage = UserImage::find($id);
                $path = $request->file('img_user')->store('avatars','public');
                $userImage->image_path = $path;
                $userImage->save();
            }
        }

        return $user;
    }

    private function getAccountType($role_id){
        if ($role_id == 1 || $role_id == 2){
            $result = 2;
        }
        else if($role_id == 3){
            $result = 3;
        }
        else{
            $result = 4;
        }
        return $result;
    }

    private function storeUserManagers($user_id, $manager_ids){

        DB::beginTransaction();

        $user_manager = UserManager::where('user_id', $user_id);
        $user_manager->delete();

        foreach ($manager_ids as $manager_id){
            $user_manager = new UserManager();
            $user_manager->manager_id = $manager_id;
            $user_manager->user_id = $user_id;
            $user_manager->save();
        }

        DB::commit();
    }

}
