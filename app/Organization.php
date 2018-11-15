<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model {
	//replace arabic letter
  public function setNameAttribute($value)
  {
    $this->name = Str::arabic_replace($value);
  }
	//replace arabic letter
  public function setCityAttribute($value)
  {
    $this->city = Str::arabic_replace($value);
  }
	//Define the one to many relationship with Projects
	public function projects()
	{
		return $this->hasMany('App\Project')->orderBy('created_at','desc');
	}
	//get type
	public function getType(){
		return ($this->type==1) ? 'مقاول':'عميل';
	}
	//extract log link
	public function extractLogLink()
	{
		if($this->deleted == 0){
			$link = '<p>أسم العميل : '.$this->name.'</p><p>نوع العميل : '.$this->getType().'</p><a href="'.route('showorganization',['id'=>$this->id]).'" class="btn btn-primary mr-2">رابط العميل</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم العميل : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
  //extract a template html for every
  public function getHtmlTemplate()
  {
    $template = '<div class="col-md-12 col-lg-6">
      <div class="card mt-4">
        <div class="row">
          <div class="col-xs-4 col-sm-4 col-md-3 col-lg-4">
            <a href="'.route('showorganization',['id'=>$this->id]).'"><img src="'.asset('images/client.png').'" class="w-100" alt=""></a>
          </div>
          <div class="col-xs-8 col-sm-8 col-md-9 col-lg-8">
              <h3 class="mb-2">
                <span class="label label-default min-w-150">أسم العميل</span>
                <a href="'.route('showorganization',['id'=>$this->id]).'">'.$this->name.'</a>
              </h3>
              <h3 class="mb-2">
                <span class="label label-default min-w-150">التليفون</span>
                '.htmlspecialchars(str_replace(',',' , ',$this->phone)).'
              </h3>
              <h3 class="mb-2">
                <span class="label label-default min-w-150">المدينة</span>
                '.htmlspecialchars($this->city).'
              </h3>
              <h3 class="mb-2">
                <span class="label label-default min-w-150">نوع العميل</span>
                '.htmlspecialchars($this->getType()).'
              </h3>
              <div class="mt-3">
                <a href="'.action('ProjectController@create',['id'=>$this->id]).'" class="btn btn-primary">أضافة مشروع</a>
              </div>
          </div>
        </div>
      </div>
    </div>';

    return $template;
  }
}
