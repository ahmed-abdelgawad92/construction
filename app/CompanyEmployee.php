<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyEmployee extends Model {

	//relation with advances 1 to many
	public function advances()
	{
		return $this->hasMany('App\Advance');
	}

}
