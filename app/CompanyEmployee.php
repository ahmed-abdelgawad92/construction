<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyEmployee extends Model {
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
	//relation with advances 1 to many
	public function advances()
	{
		return $this->hasMany('App\Advance');
	}

	//extract log link
	public function extractLogLink()
	{
		if($this->deleted == 0){
			$link = '<a href="'.route('showcompanyemployee',['id'=>$this->id]).'" class="btn btn-primary">بيانات الموظف '.$this->name.'</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم الموظف : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
}
