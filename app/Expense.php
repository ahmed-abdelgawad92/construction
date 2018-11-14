<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Payment;
class Expense extends Model {

	//Define the one to many relationship with project
	public function project()
	{
		return $this->belongsTo('App\Project');
	}
	//get payment of the expense
	public function payment()
	{
		return Payment::where('project_id',$this->project_id)->where('table_name','expenses')->where('table_id',$this->id)->first();
	}
	//escape html entities while getting name
	public function getWhomAttribute($value)
	{
		return htmlspecialchars($value);
	}
	//extract log link
	public function extractLogLink()
	{
		if($this->project->deleted == 0){
			$link = '<p>وصف الأكرامية : '.$this->whom.'</p><p>قيمة الأكرامية : '.$this->expense.' جنيه</p><a href="'.route('showexpense',['id'=>$this->project_id]).'" class="btn btn-primary ml-2">اكراميات المشروع</a><a href="'.route('showproject',['id'=>$this->project_id]).'" class="btn btn-primary">رابط المشروع</a>';
		}
		else{
			$link = '<p class="alert alert-info">اكرامية : '.$this->whom.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
}
