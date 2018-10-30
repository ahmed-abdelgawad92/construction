<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Payment;
class Contract extends Model
{
  //Define the one to many relationship with Terms
  public function term()
  {
    return $this->belongsTo('App\Term');
  }
  //Define the one to many relationship with Contractors
  public function contractor()
  {
    return $this->belongsTo('App\Contractor');
  }
  //Define the one to many relationship with productions
  public function productions()
  {
    return $this->hasMany('App\Production');
  }
  //Paid Productions
  public function paidProductions()
  {
    $total = Payment::where('table_name','transactions')->where('table_id',$this->id)->where('deleted',0)->sum('payment_amount');
    return $total;
  }
}
