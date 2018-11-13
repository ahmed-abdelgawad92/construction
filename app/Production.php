<?php namespace App;

use Illuminate\Database\Eloquent\Model;
class Production extends Model {

	//Define the one to many relationship with production
	public function contract()
	{
		return $this->belongsTo('App\Contract');
	}
	//Get the releated term
	public function term()
	{
		return $this->contract->term;
	}
	//Get the releated term
	public function contractor()
	{
		return $this->contract->contractor;
	}
	//extract log link
	public function extractLogLink()
	{
		$link = '<p>المقاول القائم : <a href="'.route('showcontractor',['id'=>$this->contractor()->id]).'">'.$this->contractor()->name ?? 'الشركة ذاتها';
		$link .= '</a></p><p>كمية الأنتاج : '.$this->amount.' &nbsp;&nbsp;&nbsp; تقييم : '.$this->rate.'</p>';
		$link .= '<p><a href="'.route('showterm',['id'=>$this->term()->id]).'" class="btn btn-primary ml-2">رابط البند '.$this->term()->code.'</a><a href="'.route('showtermproduction',['id'=>$this->term()->id]).'" class="btn btn-primary">جميع انتاج البند '.$this->term()->code.'</a></p>';
		return $link;
	}
}
