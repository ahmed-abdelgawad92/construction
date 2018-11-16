<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    //get description and name with htmlspecialchars
    public function getNameAttribute($value)
    {
      return htmlspecialchars($value);
    }
    public function getDescriptionAttribute($value)
    {
      return htmlspecialchars($value);
    }
    //belongs to a project
    public function project()
    {
      return $this->belongsTo('App\Project');
    }
    //extract log link
  	public function extractLogLink()
  	{
  		if($this->deleted == 0){
  			$link = '<p>أسم ملف الحصر : '.$this->name.'</p><a href="'.route('showinventory',['id'=>$this->id]).'" class="btn btn-primary">أفتح ملف الحصر '.$this->name.'</a>';
  		}
  		else{
  			$link = '<p class="alert alert-info">أسم ملف الحصر : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
  		}
  		return $link;
  	}
}
