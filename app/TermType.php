<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TermType extends Model {

  //extract log link
  public function extractLogLink()
  {
    return '<p  class="alert alert-info">نوع البند : '.$this->type.'</p>'; 
  }

}
