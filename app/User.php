<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class User extends Authenticatable implements CommonRepo
{
    use Notifiable;
    use Sortable;

    public $sortable = ['name','email','mobile','dob'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','mobile'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userCity(){
        return $this->hasOne('App\UserCity');
    }

    public function userFriend(){
        return $this->hasMany(UserFriend::class);
    }

    public function findById($id)
    {
        return User::find($id);
    }

    // Fetch User List without its friend list
    public function userListWithOutFriend($userData,$pageLimit){
        return User::select('*')->whereNotIn('id', $userData)->sortable()->paginate($pageLimit);
    }
    //
    public function userList($pageLimit){
        return User::sortable()->paginate($pageLimit);
    }

    public function userSeach($search, $pageLimit){
        return User::where('name','like','%'.$search.'%')->paginate($pageLimit);
    }

    public function userSearchWithOutFriend($userData,$search,$pageLimit){
        return User::where('name','like','%'.$search.'%')->
                        whereNotIn('id', $userData)->sortable()->paginate($pageLimit);
    }

    public function getOneUser($col,$data){
        return User::select("*")
            ->where($col, $data)
            ->get()->first();
    }

    public function getUserEmailCount($userEmail)
    {
        return DB::select("select count(1) as count  from users where email = '$userEmail' ");
    }

    public function findByEmail($email)
    {
        return User::where('email','=',$email)->first();

    }

    public function getAllData()
    {
        return User::all();
    }
}
