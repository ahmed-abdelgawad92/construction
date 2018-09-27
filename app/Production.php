<?php namespace App;

use Illuminate\Database\Eloquent\Model;
class Production extends Model {

	//Define the one to many relationship with production
	public function contract()
	{
		return $this->belongsTo('App\Contract');
	}
	//Get the releated term
	public function term()
	{
		return $this->contract->term;
	}
	//Get the releated term
	public function contractor()
	{
		return $this->contract->contractor;
	}
}
