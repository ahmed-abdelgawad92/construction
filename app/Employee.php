<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model {

	//Define the one to many relationship with projects
	public function projects()
	{
		return $this->belongsToMany('App\Project')->withPivot('salary','done','ended_at')->withTimestamps();
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
}
