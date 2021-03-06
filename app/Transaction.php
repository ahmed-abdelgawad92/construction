<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

	//with terms 1 to many
	public function term()
	{
		return $this->belongsTo('App\Term');
	}

	public function transaction_term()
	{
		return $this->hasMany('App\TransactionTerm');
	}
	//extract log link
	public function extractLogLink()
	{
		if ($this->term->deleted == 0) {
			$link = '<a href="'.route('alltermtransaction',['id'=>$this->term_id]).'" class="btn btn-primary">مستخلص البند '.$this->term->code.'</a>';
		}else{
			$link = '<p class="alert alert-info">كود البند : '.$this->term->code.' (هذا البند محذوف)</p>';
		}
		return $link;
	}
}
