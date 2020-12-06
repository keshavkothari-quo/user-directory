<?php

namespace App\Http\Controllers;

use App\Http\Contract\ProfileContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Validator,Response;
use Session;
class ProfileDetailController extends Controller
{
    // contract object
    private $profileContract;

    public function __construct(ProfileContract $profileContract){
        $this->profileContract = $profileContract;
    }

    public function dashboard()
    {
        $user = $this->profileContract->dashboard(Auth::user()->id);
        if(!empty($user)) {
            return view('dashboard',['userId' => $user]);
        }
        return Redirect('login');
    }

    public function saveProfileDetail(Request $request){
        $data = $request->all();
        $user = $this->profileContract->saveProfileData($data);
        return redirect()->intended('dashboard')->with('profileUpdate', "Your profile detail is added");
    }

    public function getProfileDetail($userId)
    {
        // Check to validate user can't edit other user
        if(Auth::user()->id != $userId){
            return Redirect::to("login")->withErrors(['authError' => 'Opps! You do not have access. Please login']);
        }
        $user = $this->profileContract->getProfileDetail($userId);
        return view('edit-profile',['userId' => $user]);
    }

    public function editProfileDetail(Request $request){
        $data = $request->all();
        $this->profileContract->saveProfileData($data);
        return redirect()->intended('dashboard')->with('profileUpdate', "Your profile is update");
    }

    public function resetPassword($userId){
        return view('reset-password',['userId' => $userId]);
    }

    public function updatePassword(Request $request){
        $user = $this->profileContract->updatePassword($request->all());
        if(is_null($user)){
            return redirect()->back()->with('passwordMisMatched',"Please enter current correct");
        }
        return redirect()->intended('dashboard')->with('passwordUpdate', 'Your Password is updated');
    }

}
