<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model {
	//replace arabic letter
  public function setNameAttribute($value)
  {
    $this->name = Str::arabic_replace($value);
  }
  //escape html entities while getting name
  public function getNameAttribute($value)
  {
    return htmlspecialchars($value);
  }
	//replace arabic letter
  public function setJobAttribute($value)
  {
    $this->job = Str::arabic_replace($value);
  }
	//replace arabic letter
  public function setCityAttribute($value)
  {
    $this->city = Str::arabic_replace($value);
  }
	//Define the one to many relationship with projects
	public function projects()
	{
		return $this->belongsToMany('App\Project')->withPivot('id','salary','done','ended_at')->withTimestamps();
	}
	//Get Count of current projects
	public function countCurrentProjects()
	{
		return $this->projects()->wherePivot("done",0)->count();
	}
	//relation with advances 1 to many
	public function advances()
	{
		return $this->hasMany('App\Advance');
	}

	//extract log link
	public function extractLogLink()
	{
		if($this->deleted == 0){
			$link = '<a href="'.route('showemployee',['id'=>$this->id]).'" class="btn btn-primary">بيانات الموظف '.$this->name.'</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم الموظف : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
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
            <a href="'.route('showemployee',['id'=>$this->id]).'"><img src="'.asset('images/client.jpg').'" class="w-100" alt=""></a>
          </div>
          <div class="col-xs-8 col-sm-8 col-md-9 col-lg-8">
              <h3 class="mb-2">
                <span class="label label-default min-w-150">أسم الموظف</span>
                <a href="'.route('showemployee',['id'=>$this->id]).'">'.$this->name.'</a>
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
                <span class="label label-default min-w-150">الوظيفة</span>
                '.htmlspecialchars($this->job).'
              </h3>
              <div class="mt-3">
                <a href="'.route('assignjob',$this->id).'" class="btn btn-primary">تعيين الموظف</a>
              </div>
          </div>
        </div>
      </div>
    </div>';

    return $template;
  }

}
