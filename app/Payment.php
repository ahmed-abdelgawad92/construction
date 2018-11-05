<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  // relationship with project
  public function project()
  {
    return $this->belongsTo('App\Project');
  }
}
