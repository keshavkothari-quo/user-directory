<?php


namespace App\Http\Container;

use App\CommonRepoFactrory;
use App\Http\Contract\LocationContract;
use App\State;
use App\City;
use Illuminate\Support\Facades\DB;

class LocationContainer implements LocationContract
{


    public function getStates(){
        $stateData = [];
        $common = new CommonRepoFactrory('State');
        $states = $common->getAllData();
        $i = 0;
        foreach ($states as $state){
            $stateData[$i]['id'] = $state->id;
            $stateData[$i]['name'] = $state->name;
            $i++;
        }
        return $stateData;
    }

    public function getCities($request){
        $city = new City();
        $data['cities'] = $city->getCityFromStateId($request->state_id);
        return $data;
    }

    public function userState($cityId){
        $state = new State();
        $data = $state->getStateFromCityId($cityId);
        return $data[0];
    }
}
