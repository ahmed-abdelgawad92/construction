<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model {

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
			$link = '<a href="'.route('showemployee',['id'=>$this->id]).'" class="btn btn-primary">'.$this->name.'</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم الموظف : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
}
