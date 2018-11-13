<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\StoreType;

class Consumption extends Model {

	//Define the one to many relationship with consumption
	public function term()
	{
		return $this->belongsTo('App\Term');
	}
	//get unit of the store type
	public function getUnit()
	{
		$storeType = StoreType::where('name',$this->type)->where('deleted',0)->first();
		return $storeType->unit;
	}

	//extract log link
	public function extractLogLink()
	{
		if($this->deleted == 0){
			$link = '<a href="'.route('showtermconsumption',['id'=>$this->term_id]).'" class="btn btn-primary">استهلاك البند '.$this->term->code.'</a>';
			$link .= '<a href="'.route('showterm',['id'=>$this->term_id]).'" class="btn btn-primary mr-2">افتح البند '.$this->term->code.'</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم الموظف : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
}
