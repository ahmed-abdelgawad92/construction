<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Payment;

class Supplier extends Model {
	//replace arabic letter
  public function setNameAttribute($value)
  {
    $this->name = Str::arabic_replace($value);
  }
	//replace arabic letter
  public function setTypeAttribute($value)
  {
    $this->type = Str::arabic_replace($value);
  }
	//replace arabic letter
  public function setCityAttribute($value)
  {
    $this->city = Str::arabic_replace($value);
  }
	//1 to many with the Store
	public function stores()
	{
		return $this->hasMany('App\Store');
	}
	//get all payments of a supplier
	public function payments()
	{
		$payments = Payment::where("table_name","stores")->join("stores",function($join){
				$join->on("stores.id","=","payments.table_id")->whereIn("table_id",$this->store_ids());
			})
			->select("stores.type as raw_type","stores.amount as amount","stores.unit as unit","payments.payment_amount as payment_amount","payments.created_at as created_at","payments.table_id as table_id","stores.created_at as store_created_at","payments.type as type")
			->orderBy("created_at","desc")->paginate(30);
		return $payments;
	}
	// get all stores ids
	public function store_ids()
	{
		return $this->stores()->where("stores.deleted",0)->pluck("stores.id")->toArray();
	}

	//extract log link
	public function extractLogLink()
	{
		if($this->deleted == 0){
			$link = '<a href="'.route('showsupplier',['id'=>$this->id]).'" class="btn btn-primary">'.$this->name.'</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم المورد : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
}
