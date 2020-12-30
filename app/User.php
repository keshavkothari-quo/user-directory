<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements CommonRepo,JWTSubject
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
    // JWT function
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

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
        return User::select('id','name','email','mobile','dob')
            ->where($col, $data)
            ->get()->first();

        return 1;
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
