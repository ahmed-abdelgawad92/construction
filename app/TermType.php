<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Str;
class TermType extends Model {

  //replace arabic letter
  public function setNameAttribute($value)
  {
    $this->attributes['name'] = \Str::arabic_replace($value);
  }
  //escape html entities while getting name
  public function getNameAttribute($value)
  {
    return htmlspecialchars($value);
  }
  //extract log link
  public function extractLogLink()
  {
    return '<p>نوع البند : '.$this->name.'</p>';
  }

}
