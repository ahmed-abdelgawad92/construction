<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contractor extends Model {

	//1 to 1 with user
	public function user()
	{
		return $this->belongsTo('App\User');
	}
	//Define the one to many relationship with contracts
	public function contracts()
	{
		return $this->hasMany('App\Contract');
	}
	//Define the one to many relationship with terms
	public function terms()
	{
		$terms = Term::whereRaw("terms.id in (select contracts.term_id from contracts where contracts.contractor_id=? and contracts.deleted=0)",[$this->id])
									 ->where("deleted",0)
									 ->orderBy("created_at","desc")
									 ->get();
		return $terms;
	}
	//get avg rate of the contractor
	public function rate()
	{
		$sql = "select avg(productions.rate) as rate , count(*) as length from productions
						join contracts on contracts.id=productions.contract_id
						join contractors on contractors.id=contracts.contractor_id
						where contractors.id = ?
						and contracts.deleted=0
						and contractors.deleted=0
						and productions.deleted=0";
		$rate = DB::select($sql,[$this->id]);
		return $rate;
	}

	//get all notes on production
	public function productionNotes()
	{
		$sql = "select contracts.term_id, productions.note as note , productions.created_at as date, productions.rate as rate from productions
						join contracts on contracts.id=productions.contract_id
						join contractors on contractors.id=contracts.contractor_id
						where contractors.id = 2
        				and productions.note is NOT NULL
						and contracts.deleted=0
						and contractors.deleted=0
						and productions.deleted=0";
		$notes = DB::select($sql,[$this->id]);
		return $notes;
	}
	public function productions()
	{
		return $this->hasManyThrough('App\Production','App\Contract');
	}

}
