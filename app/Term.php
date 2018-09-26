<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model {
	public $dates = ['created_at','updated_at','started_at'];
	//Define the one to many relationship with project
	public function project()
	{
		return $this->belongsTo('App\Project');
	}
	//Define the one to many relationship with production
	public function productions()
	{
		return $this->hasManyThrough('App\Production','App\Contract');
	}
	//Define the many to many relationship with labor_supplier
	public function contractors()
	{
		return $this->hasManyThrough('App\Contractor','App\Contract');
	}
	//Define the one to many relationship with contracts
	public function contracts()
	{
		return $this->hasMany('App\Contract');
	}
	//Define the one to many relationship with consumption
	public function consumptions()
	{
		return $this->hasMany('App\Consumption');
	}
	//1 to many with Transactions
	public function transactions()
	{
		return $this->hasMany('App\Transaction');
	}
	//1 to many with Notes
	public function notes()
	{
		return $this->hasMany('App\Note');
	}

}
