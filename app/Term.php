<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model {
	protected $dates = ['created_at','updated_at','started_at'];
	//Define the one to many relationship with project
	public function project()
	{
		return $this->belongsTo('App\Project');
	}
	//Define the one to many relationship with production
	public function productions()
	{
		return $this->hasMany('App\Production');
	}
	//Define the one to many relationship with labor_supplier
	public function contractor()
	{
		return $this->belongsTo('App\Contractor');
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

}
