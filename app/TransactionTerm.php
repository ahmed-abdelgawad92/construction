<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionTerm extends Model
{
    //
    protected $table = "transaction_term";

    public function term()
    {
        return $this->belongsTo('App\Term');
    }
}
