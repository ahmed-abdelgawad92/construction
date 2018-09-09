<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Production extends Model {

	//Define the one to many relationship with production
	public function term()
	{
		return $this->belongsTo('App\Term');
	}
}
