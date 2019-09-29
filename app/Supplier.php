<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Payment;

class Supplier extends Model {
	//replace arabic letter
  public function setNameAttribute($value)
  {
    $this->name = \Str::arabic_replace($value);
  }
  //escape html entities while getting name
  public function getNameAttribute($value)
  {
    return htmlspecialchars($value);
  }
	//replace arabic letter
  public function setTypeAttribute($value)
  {
    $this->type = \Str::arabic_replace($value);
  }
	//replace arabic letter
  public function setCityAttribute($value)
  {
    $this->city = \Str::arabic_replace($value);
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
  // get last store imported
  public function lastStore()
  {
    return Store::where('supplier_id',$this->id)->where('deleted',0)->orderBy('created_at','desc')->first();
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
  //extract a template html for every
  public function getHtmlTemplate()
  {
    $template = '<div class="col-md-12 col-lg-6">
      <div class="card mt-4">
        <div class="row">
          <div class="col-xs-4 col-sm-4 col-md-3 col-lg-4">
            <a href="'.route('showsupplier',['id'=>$this->id]).'"><img src="'.asset('images/supplier.png').'" class="w-100" alt=""></a>
          </div>
          <div class="col-xs-8 col-sm-8 col-md-9 col-lg-8">
              <h3 class="mb-2">
                <span class="label label-default min-w-150">أسم المورد</span>
                <a href="'.route('showsupplier',['id'=>$this->id]).'">'.$this->name.'</a>
              </h3>
              <h3 class="mb-2">
                <span class="label label-default min-w-150">التليفون</span>
                '.htmlspecialchars(str_replace(',',' , ',$this->phone)).'
              </h3>
              <h3 class="mb-2">
                <span class="label label-default min-w-150">المدينة</span>
                '.htmlspecialchars($this->city).'
              </h3>
              <h3 class="mb-2">
                <span class="label label-default min-w-150">نوع المورد</span>
                '.htmlspecialchars(str_replace(',',' , ',$this->type)).'
              </h3>
              <h3 class="mb-2">
              <span class="label label-default min-w-150">أخر كمية وردها</span>
              ';
    $store = $this->lastStore();
    if (!empty($store)) {
      $template .= htmlspecialchars($store->amount)." ".htmlspecialchars($store->unit)." من ".htmlspecialchars($store->type);
    }else{
      $template .= 'لم يورد أى كمية حتى الان';
    }
    $template .= '</h3>
          </div>
        </div>
      </div>
    </div>';

    return $template;
  }
}
