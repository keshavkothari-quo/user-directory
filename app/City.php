<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $table = 'cities';

    public function userCity(){
        return $this->hasMany('App\UserCity');
    }

    public function getCityFromStateId($stateId){
        return City::where("state_id",$stateId)->get(["name","id"]);
    }
}
