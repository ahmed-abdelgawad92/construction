<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Contractor extends Model {

	//1 to 1 with user
	public function user()
	{
		return $this->belongsTo('App\User');
	}
	//Define the one to many relationship with terms
	public function terms()
	{
		return $this->hasMany('App\Term');
	}
}
