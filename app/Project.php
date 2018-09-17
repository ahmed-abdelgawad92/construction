<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Term;
use App\Production;

class Project extends Model {
	// protected $dates = ['created_at','updated_at','started_at'];
	//Define the one to many relationship with Organizations
	public function organization()
	{
		return $this->belongsTo('App\Organization');
	}
	//Define the one to many relationship with terms
	public function terms()
	{
		return $this->hasMany('App\Term');
	}
	//Define the one to many relationship with expenses ekramyat
	public function expenses()
	{
		return $this->hasMany('App\Expense');
	}
	//Define the one to many relationship with graphs
	public function graphs()
	{
		return $this->hasMany('App\Graph');
	}

	//Define the one to many relationship with stores
	public function stores()
	{
		return $this->hasMany('App\Store');
	}

	//has many suppliers through terms
	public function suppliers()
	{
		return $this->hasManyThrough('App\Supplier','App\Term');
	}

	//Define the one to many relationship with taxes
	public function taxes()
	{
		return $this->hasMany('App\Tax');
	}
	//Define the many to many relationship with employees
	public function employees()
	{
		return $this->belongsToMany('App\Employee')->withPivot('salary','done','ended_at')->withTimestamps();
	}

	//has many consumptions through terms
	public function consumptions()
	{
		return $this->hasManyThrough('App\Consumption','App\Term');
	}

	//has many contracts through terms
	public function contracts()
	{
		return $this->hasManyThrough('App\Contract','App\Term');
	}

	//has many productions through terms
	public function productions()
	{
		// $terms = $this->terms();
		// $productions=
	}
	//has many papers
	public function papers()
	{
		return $this->hasMany('App\Paper');
	}

	//has many payments
	public function payments()
	{
		return $this->hasMany('App\Payment');
	}

	//has many transactions through terms
	public function transactions()
	{
		return $this->hasManyThrough('App\Transaction','App\Term');
	}
}
