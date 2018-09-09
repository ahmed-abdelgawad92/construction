<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

	//with terms 1 to many
	public function term()
	{
		return $this->belongsTo('App\Term');
	}

}
