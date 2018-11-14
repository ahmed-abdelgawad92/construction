<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Graph extends Model {

	//Define the one to many relationship with project
	public function project()
	{
		return $this->belongsTo('App\Project');
	}

	//get type
	public function getType()
	{
		return ($this->type==1)? 'رسم معمارى' : 'رسم أنشائى';
	}
	//escape html entities while getting name
	public function getNameAttribute($value)
	{
		return htmlspecialchars($value);
	}
	//extract log link
	public function extractLogLink()
	{
		if($this->deleted == 0){
			$link = '<p>أسم الرسم : '.$this->name.'</p><p>نوع الرسم : '.$this->getType().'</p><a href="'.route('showgraph',['id'=>$this->id]).'" class="btn btn-primary ml-2">رابط الرسم</a><a href="'.route('showproject',['id'=>$this->project_id]).'" class="btn btn-primary">رابط المشروع</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم الرسم : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
}
