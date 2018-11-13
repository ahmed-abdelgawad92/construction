<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model {

	//get the relationship with terms  1 term  to many Notes
  public function term()
  {
      return $this->belongsTo("App\Term");
  }

  //extract log link
  public function extractLogLink()
  {
    if($this->deleted == 0){
      $link = '<a href="'.route('showterm',['id'=>$this->term_id]).'" class="btn btn-primary ml-2">رابط البند</a><a href="'.route('updatenote',['id'=>$this->id]).'" class="btn btn-primary">أفتح الملحوظة : '.$this->title.'</a>';
    }
    else{
      $link = '<p class="alert alert-info">ملحوظة : '.$this->title.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
    }
    return $link;
  }
}
