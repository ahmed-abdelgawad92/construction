<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model {

	//get the relationship with terms  1 term  to many Notes
  public function term()
  {
      return $this->belongsTo("App\Term");
  }
}
