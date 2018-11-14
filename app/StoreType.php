<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreType extends Model {
  //replace arabic letter
  public function setTypeAttribute($value)
  {
    $this->type = Str::arabic_replace($value);
  }
  //extract log link
  public function extractLogLink()
  {
    return '<p class="alert alert-info">نوع الخام : '.$this->type.'</p>';
  }

}
