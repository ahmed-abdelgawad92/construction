<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Str;
use Auth;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'type', 'privilege'
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
    // public function setUsernameAttribute($value)
    // {
    //   $this->username = mb_strtolower($value);
    // }
    //replace arabic letters during setting
    // public function setNameAttribute($value)
    // {
    //   $this->name = \Str::arabic_replace(mb_strtolower($value));
    // }
    // //escape html entities while getting name
    // public function getNameAttribute($value)
    // {
    //   return htmlspecialchars($value);
    // }
    // get  enabled or not
    public function is_enabled()
    {
      return ($this->enable == 0) ? true : false;
    }
    // get  enabled or not
    public function getActiveOrNot()
    {
      return ($this->is_enabled()) ? 'مُفعل' : "معطل";
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
    //extract a template html for every user
    public function getHtmlTemplate()
    {
      $template = '<div class="col-md-12 col-lg-6">
        <div class="card mt-4">
          <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-4">
              <a href="'.route('showuser',['id'=>$this->id]).'"><img src="'.asset('images/logo_3omad_sm.png').'" class="w-100" alt=""></a>
            </div>
            <div class="col-xs-8 col-sm-8 col-md-9 col-lg-8">
                <h3 class="mb-2">
                  <span class="label label-default min-w-150">الاسم بالكامل</span>
                  <a href="'.route('showuser',['id'=>$this->id]).'">'.$this->name.'</a>
                </h3>
                <h3 class="mb-2">
                  <span class="label label-default min-w-150">اسم المستخدم</span>
                  '.htmlspecialchars($this->username).'
                </h3>
                <h3 class="mb-2">
                  <span class="label label-default min-w-150">نوع المستخدم</span>
                  '.htmlspecialchars($this->getType()).'
                </h3>
                <h3 class="mb-2">
                  <span class="label label-default min-w-150">الحالة</span>
                  '.htmlspecialchars($this->getActiveOrNot()).'
                </h3>
                <h3 class="mb-2">
                  <span class="label label-default min-w-150">تاريخ أنشاء الحساب</span>
                  '.htmlspecialchars(date('d/m/Y',strtotime($this->created_at))).'
                </h3>
                <div class="mt-3">';
      if(Auth::user()->privilege > 2){
        $template .= '<a href="'.route('showuserlogs',['id'=>$this->id]).'" class="btn btn-primary">سجل التعاملات على النظام</a>';
      }
      if (!$this->is_enabled()){
				$template .='<form class="float" method="post" action="'.route('enableuser',$this->id).'">
					<button type="button" data-toggle="modal" data-target="#enable'.$this->id.'" class="btn width-100 btn-success">تفعيل</button>
					<div class="modal fade" id="enable'.$this->id.'" tabindex="-1" role="dialog">
						<div class="modal-dialog modal-sm">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">هل تريد تفعيل الحساب '.$this->name.' ؟</h4>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">لا
									</button>
									<button class="btn btn-success">نعم</button>
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<input type="hidden" name="_method" value="PUT">
				</form>';
			}else{
				$template .='<form class="float" method="post" action="'.route('disableuser',$this->id).'">
					<button type="button" data-toggle="modal" data-target="#disable'.$this->id.'" class="btn width-100 btn-dark">تعطيل</button>
					<div class="modal fade" id="disable'.$this->id.'" tabindex="-1" role="dialog">
						<div class="modal-dialog modal-sm">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">هل تريد تعطيل الحساب '.$this->name.' ؟</h4>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">لا
									</button>
									<button class="btn btn-dark">نعم</button>
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<input type="hidden" name="_method" value="PUT">
				</form>';
      }
      $template .='</div></div></div></div></div>';
      return $template;
    }
}
