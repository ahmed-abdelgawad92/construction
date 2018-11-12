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
	//Define the one to many relationship with project
	public function project()
	{
		return $this->belongsTo('App\Project');
	}

	//extract log link
	public function extractLogLink()
	{
		if($this->project->deleted == 0){
			$link = '<a href="'.route('showtax',['id'=>$this->project_id]).'" class="btn btn-primary">'.$this->project->name.'</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم الضريبة : '.$this->project->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
}
