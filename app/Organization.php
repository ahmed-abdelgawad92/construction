<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model {

	//Define the one to many relationship with Projects
	public function projects()
	{
		return $this->hasMany('App\Project')->orderBy('created_at','desc');
	}

}
