<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class State extends Model
{
    public function findById($id)
    {
        // TODO: Implement findById() method.
    }

    public function getStateFromCityId($cityId)
    {
        return DB::select("select s.id,c.name from states as s inner join cities as c on s.id = c.state_id where c.id = $cityId");
    }
}
