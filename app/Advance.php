<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Advance extends Model {
	protected $dates = ['created_at','updated_at','payment_at'];

	//check if it's for a company employee or employee
	public function is_employee()
	{
		return ($this->employee_id != null) ? true : false;
	}

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

	//get type
	public function getType()
	{
		return ($this->active) ? 'مدفوعة':'لم تُسترد';
	}

	//extract log link
	public function extractLogLink()
	{
		if($this->deleted == 0){
			$link = '<p>قيمة السلفة : '.$this->advance.'</p><p>حالة السلفة : '.$this->getType().'</p>';
			if ($this->is_employee()) {
				$link .= '<a href="'.route('showemployee',['id'=>$this->employee_id]).'" class="btn btn-primary">الموظف المنتدب '.$this->employee->name.'</a>';
			}else{
				$link .= '<a href="'.route('showcompanyemployee',['id'=>$this->company_employee_id]).'" class="btn btn-primary">الموظف '.$this->company_employee->name.'</a>';
			}
		}
		else{
			$link = '<p>اسم الموظف : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return null;
	}
}
