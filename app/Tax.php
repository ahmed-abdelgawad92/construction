<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model {
	//get type of taxes
	public function getType()
	{
		if ($this->type == 1) {
			return "%";
		}else {
			return "جنيه";
		}
	}
	//escape html entities while getting name
	public function getNameAttribute($value)
	{
		return htmlspecialchars($value);
	}
	//Define the one to many relationship with project
	public function project()
	{
		return $this->belongsTo('App\Project');
	}

	//extract log link
	public function extractLogLink()
	{
		if($this->project->deleted == 0){
			$link = '<p>أسم الأستقطاع : '.$this->name.'</p><p>قيمة الأستقطاع : '.$this->value." ".$this->getType().'</p><p>بمشروع : <a href="'.route('showproject',['id'=>$this->project_id]).'">'.$this->project->name.'</a></p><a href="'.route('showtax',['id'=>$this->project_id]).'" class="btn btn-primary">أستقطاعات المشروع</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم الضريبة : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
}
