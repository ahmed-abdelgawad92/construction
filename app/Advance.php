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
	//extract log link
	public function extractLogLink()
	{
		// if($this->deleted == 0){
		// 	$link = '<a href="'.route('showtermconsumption',['id'=>$this->term_id]).'" class="btn btn-primary">استهلاك البند '.$this->term->code.'</a>';
		// }
		// else{
		// 	$link = '<p>اسم الموظف : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		// }
		return null;
	}
}
