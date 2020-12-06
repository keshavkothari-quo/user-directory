<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResetPassword extends Model
{
    //
    protected $table = "password_resets";
    public $timestamps = false;

    public function checkToken($data){
        $validate = ResetPassword::select('email')->where('token','=',$data)->get()->first() ;
        return $validate;
    }

    public function deleteEmail($email)
    {
        DB::table('password_resets')->where('email',$email)->delete();
    }
}
