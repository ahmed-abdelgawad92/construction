<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Str;
class Contractor extends Model {
	//replace arabic letter
  public function setNameAttribute($value)
  {
    $this->attributes['name'] = \Str::arabic_replace($value);
  }
  //escape html entities while getting name
  public function getNameAttribute($value)
  {
    return htmlspecialchars($value);
  }
	//replace arabic letter
  public function setTypeAttribute($value)
  {
    $this->attributes['type'] = \Str::arabic_replace($value);
  }
	//replace arabic letter
  public function setCityAttribute($value)
  {
    $this->attributes['city'] = \Str::arabic_replace($value);
  }
	//1 to 1 with user
	public function user()
	{
		return $this->belongsTo('App\User');
	}
	//Define the one to many relationship with contracts
	public function contracts()
	{
		return $this->hasMany('App\Contract');
	}
	//Define the one to many relationship with terms
	public function terms()
	{
		$terms = Term::whereRaw("terms.id in (select contracts.term_id from contracts where contracts.contractor_id=? and contracts.deleted=0)",[$this->id])
									 ->where("deleted",0)
									 ->orderBy("created_at","desc")
									 ->get();
		return $terms;
	}
	//get avg rate of the contractor
	public function rate()
	{
		$sql = "select avg(productions.rate) as rate , count(*) as length from productions
						join contracts on contracts.id=productions.contract_id
						join contractors on contractors.id=contracts.contractor_id
						where contractors.id = ?
						and contracts.deleted=0
						and contractors.deleted=0
						and productions.deleted=0";
		$rate = DB::select($sql,[$this->id]);
		return $rate;
	}

	//get all notes on production
	public function productionNotes()
	{
		$sql = "select contracts.term_id, productions.note as note , productions.created_at as date, productions.rate as rate from productions
						join contracts on contracts.id=productions.contract_id
						join contractors on contractors.id=contracts.contractor_id
						where contractors.id = 2
        				and productions.note is NOT NULL
						and contracts.deleted=0
						and contractors.deleted=0
						and productions.deleted=0";
		$notes = DB::select($sql,[$this->id]);
		return $notes;
	}
	public function productions()
	{
		return $this->hasManyThrough('App\Production','App\Contract');
	}
  //get last contracted term
  public function lastTerm()
  {
    $contract = Contract::where('contractor_id',$this->id)->where('deleted',0)->orderBy('created_at','desc')->first();
    $term = (!empty($contract)) ? Term::find($contract->term_id) : null;
    return $term;
  }

	//extract log link
	public function extractLogLink()
	{
		if($this->deleted == 0){
			$link = '<a href="'.route('showcontractor',['id'=>$this->id]).'" class="btn btn-primary">المقاول '.$this->name.'</a>';
		}
		else{
			$link = '<p class="alert alert-info">اسم المقاول : '.$this->name.' (لا يمكن فتحه٫ لانه تم حذفه)</p>';
		}
		return $link;
	}
  //extract a template html for every
  public function getHtmlTemplate()
  {
    $template = '<div class="col-md-12 col-lg-6">
      <div class="card mt-4">
        <div class="row">
          <div class="col-xs-4 col-sm-4 col-md-3 col-lg-4">
            <a href="'.route('showcontractor',['id'=>$this->id]).'"><img src="'.asset('images/contractor.png').'" class="w-100" alt=""></a>
          </div>
          <div class="col-xs-8 col-sm-8 col-md-9 col-lg-8">
              <h3 class="mb-2">
                <span class="label label-default min-w-150">أسم المقاول</span>
                <a href="'.route('showcontractor',['id'=>$this->id]).'">'.$this->name.'</a>
              </h3>
              <h3 class="mb-2">
                <span class="label label-default min-w-150">التليفون</span>
                '.htmlspecialchars(str_replace(',',' , ',$this->phone)).'
              </h3>
              <h3 class="mb-2">
                <span class="label label-default min-w-150">المدينة</span>
                '.htmlspecialchars($this->city).'
              </h3>
              <h3 class="mb-2">
                <span class="label label-default min-w-150">نوع المقاول</span>
                '.htmlspecialchars(str_replace(',',' , ',$this->type)).'
              </h3><h3 class="mb-2">
              <span class="label label-default min-w-150">أخر بند تعاقد عليه</span>
              ';
    $term = $this->lastTerm();
    if (!empty($term)) {
      $template .= '<a href="'.route('showterm',['id'=>$term->id]).'">'.$term->code.'</a>';
    }else{
      $template .= 'لم يتعاقد على أى بند حتى الان';
    }
    $template .= '</h3>
          </div>
        </div>
      </div>
    </div>';

    return $template;
  }

}
