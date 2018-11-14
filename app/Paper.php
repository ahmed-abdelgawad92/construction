<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    //escape html entities while getting name
    public function getNameAttribute($value)
    {
      return htmlspecialchars($value);
    }
    //1 to m with project
    public function project()
    {
      return $this->belongsTo("App\Project");
    }
    //extract log link
    public function extractLogLink()
    {
      if($this->deleted == 0){
        $link = '<a href="'.route('showpaper',['id'=>$this->id]).'" class="btn btn-primary">'.$this->name.'</a>';
      }
      else{
        $link = '<p class="alert alert-info">اسم الورقية : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
      }
      return $link;
    }
}
