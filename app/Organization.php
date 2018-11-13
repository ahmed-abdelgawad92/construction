<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model {

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
}
