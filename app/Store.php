<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model {

	//Define the one to many relationship with project
	public function project()
	{
		return $this->belongsTo('App\Project');
	}
	//1 to many with Contractor
	public function supplier()
	{
		return $this->belongsTo('App\Supplier');
	}
}
