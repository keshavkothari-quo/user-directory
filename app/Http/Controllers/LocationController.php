<?php

namespace App\Http\Controllers;

use App\Http\Contract\LocationContract;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function getState(LocationContract $locationContract){
        $data = $locationContract->getStates();
        return response()->json(['states' => $data ]);
    }

    public function getCity(LocationContract $locationContract,Request $request){
        $data = $locationContract->getCities($request);
        return response()->json($data);
    }

    public function getUserState(LocationContract $locationContract,Request $request){
        $data = $locationContract->getStates();
        $data['userState'] = $locationContract->userState($request->cityId);
        return response()->json(['states' => $data ]);
    }
}
