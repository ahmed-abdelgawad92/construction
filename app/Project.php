<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;;
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
		return $this->hasManyThrough('App\Supplier','App\Store');
	}

	//has many suppliers through terms
	public function supplierDetails()
	{
		$suppliers = DB::select("
		select suppliers.id as sup_id, suppliers.name as name , stores.amount as amount,stores.value as unit_price,(stores.amount * stores.value) as total_price , stores.created_at as created_at ,stores.type as type ,stores.amount_paid as paid from stores
		join suppliers on suppliers.id = stores.supplier_id and suppliers.deleted = 0
		where stores.project_id=?
		and stores.deleted=0
		order by name , stores.created_at desc
		limit 10
		",[$this->id]);

		return $suppliers;
	}
	//has many suppliers through terms
	public function stockReport()
	{
		$stock = DB::select("
		select  stores.type as store_type , SUM(stores.amount) as store_amount , stores.unit as unit,
		(
			SELECT SUM(consumptions.amount) from consumptions
			where consumptions.term_id in (select id from terms where terms.project_id=? and terms.deleted=0)
  		and consumptions.type = store_type group by consumptions.type
		) as consumed_amount
  	from stores where stores.project_id =?  group by store_type
		ORDER BY `store_type`  ASC
		",[$this->id,$this->id]);

		return $stock;
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

	//get summary production per term
	public function productionDetails()
	{
		$productions = DB::select("
		select sum(productions.amount) as amount, avg(productions.rate) as rate , terms.id as term_id , terms.code as code , terms.amount as term_amount, terms.unit as unit from terms
		left join contracts on terms.id = contracts.term_id and contracts.deleted=0
		left join productions on contracts.id= productions.contract_id and productions.deleted=0
		where terms.project_id=?
		and terms.deleted=0
		group by terms.id
		",[$this->id]);

		return $productions;
	}
	//get summary production per project
	public function productionReport()
	{
		$productions = DB::select("
		select sum(productions.amount) as amount, avg(productions.rate) as rate , (select distinct sum(terms.amount) from terms where terms.project_id=? and terms.deleted =0 ) as total_amount   from terms
		left join contracts on terms.id = contracts.term_id and contracts.deleted=0
		left join productions on contracts.id= productions.contract_id and productions.deleted=0
		where terms.project_id=?
    and terms.deleted = 0
		group by terms.project_id
		",[$this->id,$this->id]);

		return $productions;
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
