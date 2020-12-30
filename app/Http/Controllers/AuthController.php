<?php

namespace App\Http\Controllers;
use App\Http\Contract\AuthContract;
use App\Http\Contract\ProfileContract;
use Egulias\EmailValidator\Exception\LocalOrReservedDomain;
use Illuminate\Http\JsonResponse;
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
                return redirect()->intended("contact-list/".$user['id'])->with('success', 'Thank you for signing up');
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

    public function apiLogin(Request $request){
        $requestData = $request->all();
        $errors = [];
        $data = [];
        $message = "";
        $status = true;
        $validator = Validator::make($requestData,[
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            $message = "Login Failed";
            return $this->sendResponse($message,$data,$errors,$status);
        }
        $credentials = $request->only("email", "password");
        if (! $token = auth('api')->attempt($credentials)) {
            $status = false;
            $errors = [
                "login" => "Invalid username or password",
            ];
            $message = "Login Failed";
        }else {
            $message = "Login Successfull";
            $data = [
                'token' =>  'bearer ' . $token,
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ];
        }
        return $this->sendResponse($message,$data,$errors,$status);
    }

    public function apiRegister(Request $request){
        $requestData = $request->all();
        $errors = [];
        $data = [];
        $message = "";
        $status = true;
        $validationArray  = $this->authContract->validateArray($requestData);
        $validator = Validator::make($requestData,$validationArray);
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            $message = "Registration Failed";
            return $this->sendResponse($message,$data,$errors,$status);
        }
        $user = $this->authContract->createUser($requestData);
        if($user){
            $credentials = $request->only("email", "password");
            if (! $token = auth('api')->attempt($credentials)) {
                $status = false;
                $errors = [
                    "login" => "Invalid username or password",
                ];
                $message = "Login Failed";
            }else {
                $message = "Registration Successfull";
                $data = [
                    'token' =>  'bearer ' . $token,
                    'expires_in' => auth('api')->factory()->getTTL() * 60
                ];
            }
        }else{
            $status = false;
            $errors = [
                "registration" => "Unable to create users",
            ];
            $message = "Registration Failed";
        }
        return $this->sendResponse($message,$data,$errors,$status);
    }

    protected function sendResponse($message,$data,$errors = [],$status = true): JsonResponse{
        $errorCode = $status ? 200 : 422;
        $result = [
            "message" => $message,
            "status" => $status,
            "data" => $data,
            "errors" => $errors
        ];
        return response()->json($result,$errorCode);

    }
}

