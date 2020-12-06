<?php


namespace App\Http\Container;

use App\Http\Contract\ContactsContract;
use App\User;
use App\UserCity;
use App\UserFriend;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Array_;

class ContactsContainer implements ContactsContract
{

    public function getContactList($userId)
    {
        $userData = $this->getFriendList($userId);
        $user = new User();
        if(!is_null($userData) && !empty($userData)) {
            $users = $user->userListWithOutFriend($userData,5);
        }else{
            dd('in else');
            $users = $user->userList(5);
        }
        return $users;
    }

    public function searchContactList($data)
    {
        $userFriend = $this->getFriendList($data['userId']);
        $userObj = new User();
        if(!is_null($userFriend) && !empty($userFriend)) {
            $user = $userObj->userSearchWithOutFriend($userFriend,$data['search'],5);
        }else{
            dd('in else');
            $user = $userObj->userSeach($data['search'], 5);
        }

        return $user;
    }

    public function addUserFriend($data){
        $userFriend = new UserFriend();
        $userFriend->user_id = $data['userId'];
        $userFriend->friend_id = $data['friendId'];
        $userFriend->save();
        $user = User::paginate(5);
        return $user;
    }

    private function getFriendList($userId){
        $userObj = new User();
        $user = $userObj->findById($userId);
        $userData[] = $user->id;
        foreach ($user->userFriend as $userFriend){
            $userData[] = $userFriend->friend_id;
        }
        return $userData;
    }


}
