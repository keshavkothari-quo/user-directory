<?php

namespace App\Http\Controllers;
use App\Http\Contract\AuthContract;
use App\Http\Contract\ProfileContract;
use Egulias\EmailValidator\Exception\LocalOrReservedDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\JWTAuth;
use Validator,Redirect,Response;
Use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use Exception;

class AuthController extends Controller
{
    // gobal object for Contract
    protected $authContract;

    public function __construct(AuthContract $authContract) {
        $this->authContract = $authContract;
    }
    public function index()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function postLogin(Request $request)
    {
        try {
            $user = $this->authContract->postLogin($request);
            if (!empty($user)) {
                return redirect()->intended('dashboard')->with('userId', $user['id']);
            }
            return redirect()->back()->with('failLogin', 'email/phone number or the password is incorrect.');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with('exceptionLogin', $exception->getMessage());
        }
    }

    public function postRegister(Request $request)
    {
        try {
            $user = $this->authContract->postRegister($request);
            if (!empty($user)) {
                // Authentication passed...
                return redirect()->intended('dashboard')->with('userId', $user['id']);
            }
            return redirect()->back()->with('failLogin', 'email/phone number or the password is incorrect.');
        }
        catch (Exception $exception){
            Log::error($exception);
            return redirect()->back()->with('exceptionLogin', $exception->getMessage());
        }
    }

    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }

    public function forgetPassword(){
        return view('forget-password');
    }

    public function sendForgetPassword(Request $request){
        try {
                $userExist = $this->authContract->forgetPassword($request->all());
                if($userExist == 0){
                    return redirect()->back()->with('exception',"User's email not found");
                }
                return redirect()->back()->with('sendEmail',"Reset password link is send on your email");
        }
        catch (Exception $exception){
            return redirect()->back()-with('exception', $exception->getMessage());
        }
    }

    public function resetPassword(Request $request,ProfileContract $profileContract){
        $userEmail = $this->authContract->checkToken($request->token);
        if($userEmail) {
            return view('reset-password-link',compact('userEmail'));
        }
        return redirect()->to('login')->withErrors(['authError' => 'Reset password link is invaild/expired']);
    }

    public function updatePassword(Request $request){
        $this->authContract->updatePassword($request->all());
        return redirect()->to('login')->with('resetSuccess','Your password reset succussfully');
    }
}

