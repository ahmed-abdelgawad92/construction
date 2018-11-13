<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyEmployee extends Model {

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
