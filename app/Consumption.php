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
}
