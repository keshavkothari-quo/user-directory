<?php


namespace App\Http\Container;

use App\Http\Contract\LocationContract;
use App\State;
use App\City;
use Illuminate\Support\Facades\DB;

class LocationContainer implements LocationContract
{


    public function getStates(){
        $stateData = [];
        $states = State::all();
        $i = 0;
        foreach ($states as $state){
            $stateData[$i]['id'] = $state->id;
            $stateData[$i]['name'] = $state->name;
            $i++;
        }
        return $stateData;
    }

    public function getCities($request){
        $data['cities'] = City::where("state_id",$request->state_id)
            ->get(["name","id"]);
        return $data;
    }

    public function userState($cityId){
        $data = DB::select("select s.id,c.name from states as s inner join cities as c on s.id = c.state_id where c.id = $cityId");
        return $data[0];
    }
}
