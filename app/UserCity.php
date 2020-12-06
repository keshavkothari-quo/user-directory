<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserCity extends Model
{
    //
    protected $table = "users_city";

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function city(){
        return $this->belongsTo('App\City');
    }

    public function updateOrCreate($userId,$cityId)
    {
        return UserCity::updateOrCreate(['user_id'=>$userId],['city_id'=>$cityId]);
    }

    public function updateUserCity($userId,$city)
    {
       $query = "UPDATE `users_city` SET `city_id` = '".$city."' WHERE (`user_id` = $userId)";
       return DB::update($query);
    }


}
