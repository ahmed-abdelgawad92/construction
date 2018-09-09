<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Production;
use App\Project;
use App\Term;

use Validator;
use Auth;

class ProductionController extends Controller {

	/*
	 *show all undone projects
	 *
     */
	public function chooseProject()
	{
		if(Auth::user()->type=='admin'){
			$projects=Project::where('done',0)
				->orderBy('created_at')
				->get();
			$array=[
				'active'=>'pro',
				'projects'=>$projects
			];
			return view('production.allprojects',$array);
		}
		else
			abort('404');

	}


	/*
	 *show all production records of a project
	 *and the total production of this project
     */
	public function index($id)
	{
		if(Auth::user()->type=='admin'){
			$project=Project::findOrFail($id);
			$productions=$project->productions()
					->selectRaw('sum(productions.amount) as amount,avg(productions.rate) as rate,productions.term_id as term_id,terms.code as code,terms.amount as total_amount')
					->groupBy('productions.term_id')
					->get();
			$avg_rate=$project->productions()->avg('rate');
			$total_production=$project->productions()->sum('productions.amount');
			$total_amount=$project->terms()->sum('amount');

			$array=[
				'active'=>'pro',
				'productions'=>$productions,
				'project'=>$project,
				'avg_rate'=>$avg_rate,
				'total_amount'=>$total_amount,
				'total_production'=>$total_production
			];
			return view('production.all',$array);
		}
		else
			abort('404');
	}

	/*
	 *show all production records of a term
	 *and the total production of this term
     */
	public function show($id)
	{
		if(Auth::user()->type=='admin'){
			$term=Term::findOrFail($id);
			$productions=$term->productions;
			$avg_rate=$term->productions()->avg('rate');
			$total_production=$term->productions()->sum('amount');
			$remain_amount=$term->amount-$total_production;
			$productionPercent=round(($total_production/$term->amount)*100,2);
			$array=[
				'active'=>'pro',
				'term'=>$term,
				'productions'=>$productions,
				'avg_rate'=>$avg_rate,
				'total_production'=>$total_production,
				'remain_amount'=>$remain_amount,
				'productionPercent'=>$productionPercent
			];
			return view('production.show',$array);
		}
		else
			abort('404');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
		if(Auth::user()->type=='admin'){
			$term=Term::findOrFail($id);
			$array=['active'=>'pro','term'=>$term];
			return view('production.add',$array);
		}
		else
			abort('404');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req,$id)
	{
		if(Auth::user()->type=='admin'){
			//validation rules
			$rules=[
				'amount'=>'required|numeric',
				'rate'=>'required|in:1,2,3,4,5,6,7,8,9,10',
				'note'=>'required_if:rate,1,2,3,4,5,6,7'
			];
			//error messages
			$error_messages=[
				'amount.required'=>'يجب أدخال كمية الأنتاج',
				'amount.numeric'=>'كمية الأنتاج لابد أن تتكون من أرقام فقط',
				'rate.required'=>'يجب أختيار التقييم المناسب',
				'rate.in'=>'التقييم يجب أن يكون رقم من 1 الى 10',
				'note.required_if'=>'يجب كتلبة ملحوظة توضح سبب التقييم'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$production = new Production;
			$production->amount=$req->input('amount');
			$production->rate=$req->input('rate');
			$production->note=$req->input('note');
			$production->term_id=$id;
			$saved=$production->save();
			if(!$saved)
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة كمية الأنتاج , يرجى المحاولة فى وقت لاحق');
			return redirect()->route('addconsumption',$id)->with('success','تم أضافة الأنتاج بنجاح الى هذا البند');
		}
		else
			abort('404');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if(Auth::user()->type=='admin'){
			$production=Production::findOrFail($id);
			$array=['active'=>'pro','production'=>$production];
			return view('production.edit',$array);
		}
		else
			abort('404');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $req,$id)
	{
		if(Auth::user()->type=='admin'){
			//validation rules
			$rules=[
				'amount'=>'required|numeric',
				'rate'=>'required|in:1,2,3,4,5,6,7,8,9,10',
				'note'=>'required_if:rate,1,2,3,4,5,6,7'
			];
			//error messages
			$error_messages=[
				'amount.required'=>'يجب أدخال كمية الأنتاج',
				'amount.numeric'=>'كمية الأنتاج لابد أن تتكون من أرقام فقط',
				'rate.required'=>'يجب أختيار التقييم المناسب',
				'rate.in'=>'التقييم يجب أن يكون رقم من 1 الى 10',
				'note.required_if'=>'يجب كتلبة ملحوظة توضح سبب التقييم'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$production =Production::findOrFail($id);
			$production->amount=$req->input('amount');
			$production->rate=$req->input('rate');
			if($req->input('rate')>=8)
				$production->note="";
			else
				$production->note=$req->input('note');
			$saved=$production->save();
			if(!$saved)
				return redirect()->back()->with('update_error','حدث عطل خلال تعديل كمية الأنتاج , يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showtermproduction',$production->term_id)->with('success','تم تعديل الأنتاج بنجاح');
		}
		else
			abort('404');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Auth::user()->type=='admin'){
			$production=Production::findOrFail($id);
			$deleted=$production->delete();
			if(!$deleted){
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا الأنتاج , يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->back()->with('success','تم حذف الأنتاج بنجاح');
		}
		else
			abort('404');
	}

}
