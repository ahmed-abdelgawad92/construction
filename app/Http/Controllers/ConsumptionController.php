<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Consumption;
use App\Term;
use App\Project;
use App\Store;
use App\StoreType;

use Auth;
use Validator;

class ConsumptionController extends Controller {

	/**
	 * Display all raws consumptions within a term
	 *
	 * @return Response
	 */
	public function index($id)
	{
		if(Auth::user()->type=='admin'){
			$term=Term::findOrFail($id);
			$consumptions=$term
					->consumptions()
					->selectRaw('consumptions.type as type,sum(consumptions.amount) as amount')
					->groupBy('type')
					->get();
			$store_types=StoreType::all();
			$array=[
				'active'=>'cons',
				'term'=>$term,
				'consumptions'=>$consumptions,
				'store_types'=>$store_types
			];
			return view('consumption.all',$array);
		}
		else
			abort('404');
	}

	/**
	 * Display all raws consumptions within a project
	 *
	 * @return Response
	 */
	public function indexProject($id)
	{
		if(Auth::user()->type=='admin'){
			$project=Project::findOrFail($id);
			$consumptions=$project
					->consumptions()
					->selectRaw('consumptions.type as type,sum(consumptions.amount) as amount')
					->groupBy('type')
					->get();
			$store_types=StoreType::all();
			$array=[
				'active'=>'cons',
				'project'=>$project,
				'consumptions'=>$consumptions,
				'store_types'=>$store_types
			];
			return view('consumption.all',$array);
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
			$store_types=StoreType::all();
			$array=['active'=>'cons','term'=>$term,'store_types'=>$store_types];
			return view('consumption.add',$array);
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
				'type'=>'required|exists:store_types,name',
				'amount'=>'required|numeric'
			];
			//error messages
			$error_messages=[
				'type.required'=>'يجب أختيار نوع الخام المستهلك',
				'type.exists'=>'نوع الخام يجب أن يكون موجود بقاعدة البيانات',
				'amount.required'=>'يجب أدخال الكمية المستهلكة',
				'amount.numeric'=>'كمية الخام المستهلاك يحب أن تكون أرقام فقط'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			//check if there is enough raw in the store
			$project=Term::findOrFail($id)->project;
			$store_amount=$project
					->stores()
					->where('stores.type',$req->input('type'))
					->sum('stores.amount');
			$consumed_amount=$project
					->consumptions()
					->where('consumptions.type',$req->input('type'))
					->sum('consumptions.amount');
			$store_amount-=$consumed_amount;
			$type=StoreType::where('name',$req->input('type'))->first();
			if($store_amount<$req->input('amount'))
				return redirect()->back()->with('amount_error','لا يوجد كمية كافية بالمخازن من ال'.$req->input("type").' ,يوجد بالمخازن '.$store_amount.' '.$type->unit.' , من فضلك قم بتوريد خامات الى المخازن');
			//save in db
			$consumption=new Consumption;
			$consumption->type=$req->input('type');
			$consumption->amount=$req->input('amount');
			$consumption->term_id=$id;
			$saved=$consumption->save();
			if(!$saved)
				return redirect()->back()->with('insert_error','حدث عطل خلال أدخال هذا الأستهلاك , يرجى المحاولة فى وقت لاحق');
			return redirect()->back()->with('success','تم أضافة الأستهلاك بنجاح , أدخل أستهلاك جديد');

		}
		else
			abort('404');
	}

	/**
	 * Display the specified raw within a project.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function showProjectConsumedRaw($id,$type)
	{
		if(Auth::user()->type=='admin'){
			$checkType=StoreType::where('name',$type)->firstOrFail();
			$project=Project::findOrFail($id);
			$consumptions=$project
					->consumptions()
					->where('consumptions.type',$type)
					->get();
			$total_amount=$project
					->consumptions()
					->where('consumptions.type',$type)
					->sum('consumptions.amount');
			$store_types=StoreType::all();
			$array=[
				'active'=>'cons',
				'project'=>$project,
				'consumptions'=>$consumptions,
				'store_types'=>$store_types,
				'type'=>$type,
				'total_amount'=>$total_amount
			];
			return view('consumption.show',$array);
		}
		else
			abort('404');
	}


	/**
	 * Display the specified raw within a term.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function showTermConsumedRaw($id,$type)
	{
		if(Auth::user()->type=='admin'){
			$checkType=StoreType::where('name',$type)->firstOrFail();
			$term=Term::findOrFail($id);
			$consumptions=$term
					->consumptions()
					->where('type',$type)
					->get();
			$total_amount=$term
					->consumptions()
					->where('type',$type)
					->sum('amount');
			$store_types=StoreType::all();
			$array=[
				'active'=>'cons',
				'term'=>$term,
				'consumptions'=>$consumptions,
				'store_types'=>$store_types,
				'type'=>$type,
				'total_amount'=>$total_amount
			];
			return view('consumption.show',$array);
		}
		else
			abort('404');
	}

	// select project
	public function selectProjectConsumption()
	{
		if(Auth::user()->type=='admin'){
			$projects=Project::where('done',0)->get();
			return view('production.allprojects',['active'=>'cons','projects'=>$projects]);
		}
		else
			abort('404');
	}

	// select project
	public function selectTermConsumption()
	{
		if(Auth::user()->type=='admin'){
			$projects=Project::where('done',0)->get();
			return view('production.allprojects',['active'=>'cons','projects'=>$projects]);
		}
		else
			abort('404');
	}

	// select project
	public function selectTermConsumedRaw()
	{
		if(Auth::user()->type=='admin'){
			$projects=Project::where('done',0)->get();
			return view('production.allprojects',['active'=>'cons','projects'=>$projects]);
		}
		else
			abort('404');
	}

	// select project
	public function selectProjectConsumedRaw()
	{
		if(Auth::user()->type=='admin'){
			$projects=Project::where('done',0)->get();
			return view('production.allprojects',['active'=>'cons','projects'=>$projects]);
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
			$consumption=Consumption::findOrFail($id);
			$store_types=StoreType::all();
			$unit=StoreType::where('name',$consumption->type)->firstOrFail();
			$array=[
				'active'=>'cons',
				'consumption'=>$consumption,
				'store_types'=>$store_types,
				'unit'=>$unit
			];
			return view('consumption.edit',$array);
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
				'type'=>'required|exists:store_types,name',
				'amount'=>'required|numeric'
			];
			//error messages
			$error_messages=[
				'type.required'=>'يجب أختيار نوع الخام المستهلك',
				'type.exists'=>'نوع الخام يجب أن يكون موجود بقاعدة البيانات',
				'amount.required'=>'يجب أدخال الكمية المستهلكة',
				'amount.numeric'=>'كمية الخام المستهلاك يحب أن تكون أرقام فقط'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			//check if there is enough raw in the store
			$consumption= Consumption::findOrFail($id);
			$project=$consumption->term->project;
			if($req->input('amount')>$consumption->amount){
				$store_amount=$project
						->stores()
						->where('stores.type',$req->input('type'))
						->sum('stores.amount');
				$consumed_amount=$project
						->consumptions()
						->where('consumptions.type',$req->input('type'))
						->where('consumptions.id','!=',$id)
						->sum('consumptions.amount');
				$store_amount-=$consumed_amount;
				$type=StoreType::where('name',$req->input('type'))->first();
				if($store_amount<$req->input('amount'))
					return redirect()->back()->with('amount_error','لا يوجد كمية كافية بالمخازن من ال'.$req->input("type").' ,يوجد بالمخازن '.$store_amount.' '.$type->unit.' , من فضلك قم بتوريد خامات الى المخازن');
			}
			//save in db
			$consumption->type=$req->input('type');
			$consumption->amount=$req->input('amount');
			$saved=$consumption->save();
			if(!$saved)
				return redirect()->back()->with('insert_error','حدث عطل خلال أدخال هذا الأستهلاك , يرجى المحاولة فى وقت لاحق');
			return redirect()->back()->with('success','تم أضافة الأستهلاك بنجاح , أدخل أستهلاك جديد');

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
			$consumption=Consumption::findOrFail($id);
			$deleted=$consumption->delete();
			if(!$deleted){
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا الأستهلاك , يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->back()->with('success','تم حذف الأستهلاك بنجاح');
		}
		else
			abort('404');
	}

}
