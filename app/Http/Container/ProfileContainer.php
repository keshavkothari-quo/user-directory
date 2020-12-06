<?php


namespace App\Http\Container;

use App\Http\Contract\ProfileContract;
use App\User;
use App\UserCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileContainer implements ProfileContract
{

    public function dashboard($userId){
        $user = new User();
        return $user->findById($userId);
    }

    public function getProfileDetail($userId)
    {
        $user = new User();
        return $user->findById($userId);
    }

    public function saveProfileData($data){
        $validationArray = $this->validateArray($data);
        request()->validate($validationArray);
        $userObj = new User();
        $user = $userObj->findById($data['userId']);
        $user->name = $data['name'];
        $user->dob = $data['dob'];
        if(!empty($data['email'])) {
            $user->email = $data['email'];
        }
        if(!empty($data['mobile'])) {
            $user->mobile = $data['mobile'];
        }
        $userCity = new UserCity();
        // TODO with update or create
//        $userCity->updateOrCreate($data['userId'],$data['city']);
        $userCity = new UserCity();
        if(!$user->userCity) {
            $userCity->user_id = $data['userId'];
            $userCity->city_id = $data['city'];
            $userCity->save();
        }else{
            $userCity->updateUserCity($data['userId'],$data['city']);
        }
        $user->save();
        return $user;
    }

    public function validateArray($data){

        $validateArray = ['name' => 'required','email' => 'required|email','mobile' => 'required|numeric|min:10','dob'=>'required','city'=>'required'];
        return $validateArray;
    }

    public function updatePassword($data)
    {
        $validationArray = $this->validatePasswordFieldArray();
        request()->validate($validationArray);
        $userObj = new User();
        $user = $userObj->findById($data['userId']);
        if(Hash::check($data['password'],$user->password)){
            $user->password = Hash::make($data['newPassword']);
            $user->save();
            return $user;
        }
        // ToDO retrun in current password not matching
        return null;
    }
    public function validatePasswordFieldArray(){
        $validateArray['password'] = ['required','min:6','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'];
        $validateArray['newPassword'] = ['required_with:confirmPassword','same:confirmPassword','min:6','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'];
        return $validateArray;
    }
}
