<?php


namespace App\Http\Container;


use App\Http\Contract\AuthContract;
use App\ResetPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthContainer implements AuthContract{
    public function postLogin($request)
    {
        $data = $request->all();
        // Create validation array
        $validationArray = $this->validateArrayLogin($data);
        request()->validate($validationArray);
        // Create credentials to login
        $credentials = $this->createCredentialForLogin($data);
        try {
            if (Auth::attempt($credentials)) {
                // Authentication passed...
                if (!empty($credentials['email'])) {
                    $col = 'email';
                }
                if (!empty($credentials['mobile'])) {
                    $col = 'mobile';
                }
                $userObj = new User();
                $user = $userObj->getOneUser($col, $data[$col]);
                return $user;
            }
            return null;
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }

    public function postRegister(Request $request)
    {
        $data = $request->all();
        // Create validation array
        $validateArray = $this->validateArray($data);
        request()->validate($validateArray);
        try {
            // Create user
            $user = $this->create($data);
            // Create credential array
            $credentials = $this->createCredentialForLogin($data);
            if (Auth::attempt($credentials)) {
                // Authentication passed...
                return $user;
            }
            return null;
        }
        catch (Exception $exception){
            Log::error($exception);
        }
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function create(array $data)
    {
        $user = new User();
        if(!empty($data['email'])) {
            $user->email = $data['email'];
        }
        if(!empty($data['mobile'])){
            $user->mobile = $data['mobile'];
        }
        $user->password = Hash::make($data['password']);
        $user->save();
        return $user;
    }

    public function createCredentialForLogin(array $data){
        if(!empty($data['email'])) {
            $credentials['email'] = $data['email'];
        }
        if(!empty($data['mobile'])){
            $credentials['mobile'] = $data['mobile'];
        }
        $credentials['password'] = $data['password'];
        return $credentials;
    }

    public function validateArray($data){
        if(!empty($data['email'])){
            $validateArray = [
                'email' => 'required|email|unique:users',
            ];
        }
        if(!empty($data['mobile'])){
            $validateArray = [
                'mobile' => 'required|numeric|min:1000000000|max:100000000000000|unique:users',
            ];
        }
        $validateArray['password'] = ['required','min:6','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'];
        return $validateArray;
    }

    public function validateArrayLogin($data){
        if(!empty($data['email'])){
            $validateArray = [
                'email' => 'required|email',
            ];
        }
        if(!empty($data['mobile'])){
            $validateArray = [
                'mobile' => 'required|numeric|min:1000000000|max:100000000000000',
            ];
        }
        $validateArray['password'] = ['required','min:6','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'];
        return $validateArray;
    }

    public function logout() {
//        Session::flush();
        Auth::logout();
        return Redirect('login');
    }

    public function forgetPassword($data){
        $userEmail = $data['email'];
        request()->validate(['email'=>'required|email']);
        $userObj = new User();
        $user = $userObj->getUserEmailCount($userEmail);

        if($user[0]->count){
            $token = Hash::make(rand(0,999999));
            $resetPassword = new ResetPassword();
            $resetPassword->email = $userEmail;
            $resetPassword->token = $token;
            $resetPassword->save();
        }
        else{
            return 0;
        }
        $body = "Click on this url to reset your password ". url('/reset-password/') . "?token=$token";
        $data = array('name'=>"Keshav", 'body' => $body, 'link' => url('/reset-password/') . "?token=$token");
        $subject = 'Reset Password';
        $this->sendEmail($data,$userEmail,$subject);
        return 1;
    }

    public function sendEmail($data,$userEmail,$subject){
        $name = 'Test Mail';
        $userEmail = 'keshav@mailinator.com';
        Mail::send('emails.mail', $data, function($message) use ($userEmail, $name,$subject) {
            $message->to($userEmail, $name)
                ->subject($subject);
            $message->from('keshavtestemail.com@gmail.com','Forget Password Mail');
        });
    }

    public function checkToken($data){
        $resetPassword = new ResetPassword();
        $validateToken = $resetPassword->checkToken($data);
        if($validateToken){
            return $validateToken->email;
        }
        return false;
    }

    public function updatePassword($data){
        $validateAarray = $this->validatePassword();
        request()->validate($validateAarray);
        $userObj = new User();
        $user = $userObj->findByEmail($data['userId']);
        if($user){
            $resetPassword = new ResetPassword();
            $user->password = Hash::make($data['newPassword']);
            $user->save();
            $resetPassword->deleteEmail($user->email);
            return $user;
        }
        return null;
    }

    public function validatePassword(){
        $validateArray['newPassword'] = ['required_with:confirmPassword','same:confirmPassword','min:6','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'];
        return $validateArray;
    }
}
