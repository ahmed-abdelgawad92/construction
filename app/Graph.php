<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Graph extends Model {

	//Define the one to many relationship with project
	public function project()
	{
		return $this->belongsTo('App\Project');
	}
	//extract log link
	public function extractLogLink()
	{
		if($this->deleted == 0){
			$link = '<a href="'.route('showgraph',['id'=>$this->id]).'" class="btn btn-primary">'.$this->name.'</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم الرسم : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
}
