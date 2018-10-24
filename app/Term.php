<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Contractor;
use App\Contract;

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
	// public function contractors()
	// {
	// 	$contractors = $this->contracts;
	// }
	//get all contracted contractor ids
	public function getContractorsId()
	{
		$contractors = $this->contracts()->pluck("contracts.contractor_id")->toArray();
		return $contractors;
	}
	//Define the one to many relationship with contracts
	public function contracts()
	{
		return $this->hasMany('App\Contract');
	}
	//get contract of a contractor
	public function contract($contractor_id)
	{
		return $this->contracts()->where('contractor_id',$contractor_id)->where("deleted",0)->first();
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
	//1 to many with Payments
	public function payments()
	{
		$sql = "select sum(payments.payment_amount) as payment from payments where project_id=? and table_name='contracts' and table_id in (select id from contracts where term_id=?)";
		$payment = DB::select($sql,[$this->project_id, $this->id]);
		return $payment[0]->payment;
	}
	//1 to many with Notes
	public function notes()
	{
		return $this->hasMany('App\Note');
	}
	//get contractor_unit_price
	public function contractor_unit_price($id)
	{
		$price = $this->contracts()->where('contractor_id',$id)->where("deleted",0)->first();
		return $price->unit_price;
		dd($price);
	}
}
