<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreType extends Model {

  //extract log link
  public function extractLogLink()
  {
    return '<p class="alert alert-info">نوع الخام : '.$this->type.'</p>';  
  }

}
