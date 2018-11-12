<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model {

	//Define the one to many relationship with Projects
	public function projects()
	{
		return $this->hasMany('App\Project')->orderBy('created_at','desc');
	}

	//extract log link
	public function extractLogLink()
	{
		if($this->deleted == 0){
			$link = '<a href="'.route('showpaper',['id'=>$this->id]).'" class="btn btn-primary">'.$this->name.'</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم العميل : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
}
