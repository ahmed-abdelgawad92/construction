<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Contractor;
use App\Payment;
use App\Contract;

class Term extends Model {
	public $dates = ['created_at','updated_at','started_at'];
	//replace arabic letter
  public function setTypeAttribute($value)
  {
    $this->type = \Str::arabic_replace($value);
  }
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
		return $this->hasMany('App\TransactionTerm');
	}
	//get all Payments
	public function getPayments()
	{
		$payments = Payment::whereRaw("project_id=? and table_name='transactions' and table_id in (select id from contracts where term_id=?)",[$this->project_id, $this->id])
									->orderBy('created_at','desc')
									->get();
		return $payments;
	}
	//Sum of Payments
	public function payments()
	{
		$sql = "select sum(payments.payment_amount) as payment from payments where project_id=? and table_name='transactions' and table_id in (select id from contracts where term_id=?)";
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

	//get summary production per term
	public function getTotalTransaction()
	{
		$total = DB::select("
		select sum(transaction_term.payment) as total from transaction_term 
		where term_id = ? AND deleted = 0", [$this->id]);

		return $total[0]->total;
	}

	//extract log link
	public function extractLogLink()
	{
		if($this->deleted == 0){
			$link = '<a href="'.route('showterm',['id'=>$this->id]).'" class="btn btn-primary">رابط البند : '.$this->code.'</a>';
		}
		else{
			$link = '<p class="alert alert-info">كود البند : '.$this->code.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
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
	          <a href="'.route('showproject',['id'=>$this->id]).'"><img src="'.asset('images/admin.png').'" class="w-100" alt=""></a>
	        </div>
	        <div class="col-xs-8 col-sm-8 col-md-9 col-lg-8">
	            <h3 class="mb-2">
	              <span class="label label-default min-w-150">كود البند</span>
	              <a href="'.route('showterm',['id'=>$this->id]).'">'.$this->code.'</a>
	            </h3>
	            <h3 class="mb-2">
	              <span class="label label-default min-w-150">نوع البند</span>
	              '.htmlspecialchars(str_replace(',',' , ',$this->type)).'
	            </h3>
	            <h3 class="mb-2">
	              <span class="label label-default min-w-150">الكمية</span>
	              '.htmlspecialchars($this->amount).' '.htmlspecialchars($this->unit).'
	            </h3>
							<h3 class="mb-2">
							<span class="label label-default min-w-150">أسم المشروع</span>
							<a href="'.route('showproject',['id'=>$this->id]).'">'.$this->project->name.'</a>
							</h3>
	            <div class="mt-3">
	              <a href="'.route('updateterm',$this->id).'" class="btn btn-default">تعديل</a>
	            </div>
	        </div>
	      </div>
	    </div>
	  </div>';

	  return $template;
	}

}
