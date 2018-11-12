<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

	//with terms 1 to many
	public function term()
	{
		return $this->belongsTo('App\Term');
	}
	//extract log link
	public function extractLogLink()
	{
		if ($this->term->deleted == 0) {
			$link = '<a href="'.route('alltermtransaction',['id'=>$this->term_id]).'" class="btn btn-primary"'.$this->term->code.'</a>';
		}else{
			$link = '<p class="alert alert-info">كود البند : '.$this->term->code.' (هذا البند محذوف)</p>';
		}
		return $link;
	}
}
