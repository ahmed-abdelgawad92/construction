<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {

	//1 to many with the Store
	public function stores()
	{
		return $this->hasMany('App\Store');
	}

}
