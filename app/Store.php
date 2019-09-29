<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Payment;

class Store extends Model {
	//replace arabic letter
  public function setTypeAttribute($value)
  {
    $this->type = \Str::arabic_replace($value);
  }
	//escape html entities while getting type
	public function getTypeAttribute($value)
	{
		return htmlspecialchars($value);
	}
	//Define the one to many relationship with project
	public function project()
	{
		return $this->belongsTo('App\Project');
	}
	//1 to many with Contractor
	public function supplier()
	{
		return $this->belongsTo('App\Supplier');
	}
	//store payment
	public function payments()
	{
		$payments = Payment::where("table_name","stores")->where("table_id",$this->id)->where("deleted",0)->paginate(30);
		return $payments;
	}
	//extract log link
	public function extractLogLink()
	{
		$link = '<a href="'.route('showstore',['id'=>$this->project_id, 'type'=>$this->type]).'" class="btn btn-primary">جميع كميات '.$this->type.' بمشروع '.$this->project->name.'</a>';
		return $link;
	}
}
