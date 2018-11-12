<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Contract;

class Payment extends Model
{
  protected $appends = ['contractor'];
  //Constructor Initialize $contractor
  public function getContractorAttribute()
  {
    if ($this->table_name == "transactions") {
      $contract = Contract::find($this->table_id);
      return $contract->contractor;
    }else{
      return null;
    }
  }
  // relationship with project
  public function project()
  {
    return $this->belongsTo('App\Project');
  }

  //extract log link
  public function extractLogLink()
  {
    return null;
  }
}
