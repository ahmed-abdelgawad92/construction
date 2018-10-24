<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Payment;
class Expense extends Model {

	//Define the one to many relationship with project
	public function project()
	{
		return $this->belongsTo('App\Project');
	}
	//get payment of the expense
	public function payment()
	{
		return Payment::where('project_id',$this->project_id)->where('table_name','expenses')->where('table_id',$this->id)->first();
	}

}
