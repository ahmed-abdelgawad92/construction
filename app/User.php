<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    //relationship between users and logs
    public function logs()
    {
      return $this->hasMany('App\Log');
    }
    // set username in lowercase
    public function setUsernameAttribute($value)
    {
      $this->username = mb_strtolower($value);
    }
    // get  type
    public function getType(){
      if($this->type == 1){
        return "User";
      }else if($this->type == 2){
        return "Organizer";
      }else{
        return "Admin";
      }
    }
}
