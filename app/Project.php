<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Term;
use App\Production;

class Project extends Model {
	// protected $dates = ['created_at','updated_at','started_at'];
	//replace arabic letter
  public function setNameAttribute($value)
  {
    $this->attributes['name'] = \Str::arabic_replace($value);
  }
	//replace arabic letter
  public function setCityAttribute($value)
  {
    $this->attributes['city'] = \Str::arabic_replace($value);
  }
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
	//Define the one to many relationship with inventories
	public function inventories()
	{
		return $this->hasMany('App\Inventory');
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
	public function supplierDetails(bool $flag=true,int $limit=10)
	{
		$sql="
		select suppliers.id as sup_id, suppliers.name as name , stores.amount as amount,stores.value as unit_price,(stores.amount * stores.value) as total_price , stores.created_at as created_at ,stores.type as type ,stores.amount_paid as paid from stores
		join suppliers on suppliers.id = stores.supplier_id and suppliers.deleted = 0
		where stores.project_id=?
		and stores.deleted=0
		order by name , stores.created_at desc
		";
		if ($flag) {
			$sql.=" limit $limit";
		}
		$suppliers = DB::select($sql,[$this->id]);

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
  		and consumptions.type = store_type and consumptions.deleted=0 group by consumptions.type
		) as consumed_amount
  	from stores where stores.project_id =? and stores.deleted=0 group by store_type
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
		return $this->belongsToMany('App\Employee')->withPivot('id','salary','done','ended_at')->withTimestamps();
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
	//get summary production per project
	public function getTotalTransaction()
	{
		$total = DB::select("
		select sum(transaction_term.payment) as total from transaction_term 
		where transaction_id in (SELECT id FROM transactions WHERE project_id = ? AND deleted = 0)
		AND deleted = 0
		",[$this->id]);

		return $total[0]->total;
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
		return $this->hasMany('App\Transaction');
	}

	//extract log link
	public function extractLogLink()
	{
		if($this->deleted == 0){
			$link = '<a href="'.route('showproject',['id'=>$this->id]).'" class="btn btn-primary">'.$this->name.'</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم المشروع : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
  //extract a template html for every
  public function getHtmlTemplate()
  {
    $template = '<div class="col-md-12 col-lg-6">
      <div class="card mt-4">
        <div class="row">
          <div class="col-xs-4 col-sm-4 col-md-3 col-lg-4">
            <a href="'.route('showproject',['id'=>$this->id]).'"><img src="'.asset('images/project_img.png').'" class="w-100" alt=""></a>
          </div>
          <div class="col-xs-8 col-sm-8 col-md-9 col-lg-8">
              <h3 class="mb-2">
                <span class="label label-default min-w-150">أسم المشروع</span>
                <a href="'.route('showproject',['id'=>$this->id]).'">'.$this->name.'</a>
              </h3>
              <h3 class="mb-2">
                <span class="label label-default min-w-150">المدينة</span>
                '.htmlspecialchars($this->city).'
              </h3>
              <h3 class="mb-2">
                <span class="label label-default min-w-150">أسم العميل</span>
                '.htmlspecialchars($this->organization->name).'
              </h3>
              <div class="mt-3">
                <a href="'.url('term/all',$this->id).'" class="btn btn-primary">جميع البنود</a>
                <a href="'.route('updateproject',$this->id).'" class="btn btn-default">تعديل</a>
              </div>
          </div>
        </div>
      </div>
    </div>';

    return $template;
  }

}
