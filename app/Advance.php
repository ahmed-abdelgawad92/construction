<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Advance extends Model {
	protected $dates = ['created_at','updated_at','payment_at'];
	//Define one to many relationship with employee_project
	public function employee()
	{
		return $this->belongsTo('App\Employee');
	}

	//with company employee
	public function company_employee()
	{
		return $this->belongsTo('App\CompanyEmployee');
	}

}
