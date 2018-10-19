<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    //1 to m with project
    public function project()
    {
      return $this->belongsTo("App\Project");
    }
}
