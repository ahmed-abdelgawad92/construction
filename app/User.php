<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Str;
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
    //replace arabic letters during setting
    public function setNameAttribute($value)
    {
      $this->name = Str::arabic_replace($value);
    }
    // get  type
    public function getType(){
      if($this->privilege == 1){
        return "User";
      }else if($this->privilege == 2){
        return "Organizer";
      }else{
        return "Admin";
      }
    }

    //extract log link
    public function extractLogLink()
    {
      if($this->deleted == 0){
        $link = '<a href="'.route('showuser',['id'=>$this->id]).'" class="btn btn-primary">'.$this->name.'</a>';
      }
      else{
        $link = '<p class="alert alert-info">اسم المستخدم : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
      }
      return $link;
    }
}
