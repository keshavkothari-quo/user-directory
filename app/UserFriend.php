<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFriend extends Model
{
    //
    protected $table = 'user_friend_list';

    public function user(){
        return $this->belongsTo(User::class);
    }

}
