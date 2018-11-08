<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model {
	//get type of taxes
	public function getType()
	{
		if ($this->type == 1) {
			return "%";
		}else {
			return "جنيه";
		}
	}
	//Define the one to many relationship with project
	public function project()
	{
		return $this->belongsTo('App\Project');
	}

}
