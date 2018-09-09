<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Consumption extends Model {

	//Define the one to many relationship with consumption
	public function term()
	{
		return $this->belongsTo('App\Term');
	}

}
