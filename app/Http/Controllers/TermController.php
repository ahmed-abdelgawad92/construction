<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Term;
use App\Project;
use Auth;
use Route;
use Validator;
use Carbon\Carbon;
use App\Contractor;
use App\TermType;
use App\Log;

class TermController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id=null)
	{
		if(Auth::user()->type=='admin'){
			if(isset($id))
			{
				$terms=Term::where('project_id',$id)->orderBy('code')->get();
				$project=Project::findOrFail($id);
				if(Route::current()->getName()=='allterm')
					$active='term';
				elseif (Route::current()->getName()=='alltermstoaddproduction')
					$active='pro';
				elseif (Route::current()->getName()=='alltermstoshowproduction')
					$active='pro';
				elseif (Route::current()->getName()=='termconsumption')
					$active='cons';
				elseif (Route::current()->getName()=='showtermtoaddconsumption')
					$active='cons';
				$array=['active'=>$active,'terms'=>$terms,'project'=>$project];
				return view('term.all',$array);
			}
			else
			{
				abort('404');
			}
		}
		else
		{
			if(!isset($id)){
				$con_id=Auth::user()->contractor->id;
				$terms=Term::where('contractor_id',$con_id)->orderBy('created_at','asc')->get();
				$array=['active'=>'term','terms'=>$terms];
			}else{
				$con_id=Auth::user()->contractor->id;
				$terms=Term::where('contractor_id',$con_id)->where('project_id',$id)->orderBy('created_at','desc')->get();
				$project=Project::where('id',$id)->get();
				$array=['active'=>'term','terms'=>$terms,'project'=>$project];
			}
			return view('term.all',$array);
		}
	}
	public function getStatement($code = null)
	{
		$term = Term::where("code",$code)->first();
		if($term){
			return json_encode(["state"=>"OK","statement"=>$term->statement,"unit"=>$term->unit]);
		}else {
			return json_encode(["state"=>"NOK", "code"=>422]);
		}
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id=null)
	{
		if(Auth::user()->type=='admin'){
			if(isset($id)&& !empty($id))
				$projects[]=Project::findOrFail($id);
			else
				$projects=Project::all();
			$term_types=TermType::all();
			$array=['active'=>'term','projects'=>$projects,'term_types'=>$term_types];
			return view('term.add',$array);
		}
		else
			abort('404');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
		if(Auth::user()->type=='admin'){
			//rules
			$rules=[
				'project_id'=>'required|numeric|exists:projects,id',
				'type_select'=>'required_without:type_text|exists:term_types,name',
				'type_text'=>'required_without:type_select',
				'code'=>'regex:/^[\pN\/]+$/u',
				'statement'=>'string',
				'unit'=>'regex:/^[\pL\pN\s]+$/u',
				'amount'=>'numeric',
				'value'=>'numeric',
				'num_phases'=>'nullable|numeric',
				'started_at'=>'nullable|date'
			];
			//messages
			$error_messages=[
				'project_id.required'=>'من فضلك أختار مشروع اتابع له هذا البند',
				'project_id.numeric'=>'من فضلك لا تغير قيمة المشروع',
				'project_id.exists'=>'هذا المشروع غير موجود بقاعدة البيانات',
				'type_select.required_without'=>'من فضلك أختار او أدخل نوع البند',
				'type_select.exists'=>'نوع البند يجب أن يكون موجود فى قاعدة البيانات',
				'type_text.required_without'=>'من فضلك أختار او أدخل نوع البند',
				'code.regex'=>'كود البند يجب أن يتكون من أرقام و / فقط',
				'statement.string'=>'يجب ادخال بيان الاعمال',
				'unit.regex'=>'يجب ان تتكون من حروف و ارقام و مسافات فقط',
				'amount.numeric'=>'الكمية يجب أن تتكون من أرقام فقط',
				'value.numeric'=>'القيمة يجب أن تتكون من أرقام فقط',
				'num_phases.numeric'=>'عدد المراحل يجب أن يتكون من أرقام فقط',
				'started_at.date'=>'يجب ادخال تاريخ صحيح'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}

			//save in db
			$term=new Term;
			if(!empty($req->input("type_text"))){
				$term_type=new TermType;
				$term_type->name=$req->input("type_text");
				$term_type->save();
				$term->type=$req->input("type_text");
			}else{
				$term->type=$req->input("type_select");
			}
			$term->code=$req->input("code");
			$term->statement=$req->input('statement');
			$term->unit=$req->input('unit');
			$term->amount=$req->input('amount');
			$term->value=$req->input('value');
			$term->num_phases=$req->input("num_phases")??1;
			$term->started_at=$req->input('started_at');
			$term->project_id=$req->input('project_id');

			$saved=$term->save();
			if(!$saved){
				return redirect()->back()->withInput()->with('insert_error','حدث خطأ خلال أضافة هذا البند يرجى المحاولة فى وقت لاحق');
			}
			$log = new Log;
			$log->table="terms";
			$log->record_id=$term->id;
			$log->action="create";
			$log->user_id=Auth::user()->id;
			$log->save();
			return redirect()->route('addterm',['id'=>$term->project_id])->with('success','تم أضافة البند صاحب الكود '.$term->code.' بنجاح');
		}
		else
			abort('404');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Auth::user()->type=='admin'){
			$term=Term::findOrFail($id);
			$productions=$term->productions()->orderBy('created_at','desc')->take(3)->get();
			$consumptions=$term->consumptions()->orderBy('created_at','desc')->take(3)->get();
			$array=['active'=>'term','term'=>$term,'productions'=>$productions,'consumptions'=>$consumptions];
			return view('term.show',$array);
		}
		else
		{
			$term=Term::where('id',$id)->where('contractor_id',Auth::user()->contractor->id)->get();
			if(count($term)>0){
				$array=['active'=>'term','term'=>$term];
				return view('term.show',$array);
			}
			else
				abort('404');
		}
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
			$term=Term::findOrFail($id);
			$term_types=TermType::all();
			$array=['active'=>'term','term'=>$term,'term_types'=>$term_types];
			return view('term.edit',$array);
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

		}
		else
			abort('404');
	}

	/**
	 * Show view for starting Term
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getStart($id)
	{
		if(Auth::user()->type=='admin'){

		}
		else
			abort('404');
	}
	/*
	*get the form of creating term contract
	*
	*$id of the term
	*
	*/
	public function getTermContract($id)
	{
		if(Auth::user()->type=='admin')
		{
			$term=Term::findOrFail($id);
			$contractors=Contractor::where('type','like',"%".$term->type."%")->get();
			$array=['active'=>'term','term'=>$term,'contractors'=>$contractors];
			return view('term.contract_term',$array);
		}
		else
			abort('404');
	}
	/*
	*save the contract and the contractor id with the term
	*Request
	*$id of the term
	*
	*/
	public function postTermContract(Request $req,$id)
	{
		if(Auth::user()->type=='admin')
		{
			//validation rules
			$rules=[
				'contractor_id'=>'required|exists:contractors,id',
				'contractor_unit_price'=>'required|numeric',
				'contract_text'=>'required',
				'started_at'=>'date'
			];
			//Error Messages
			$error_messages=[
				'contractor_id.required'=>'يجب أختيار مقاول للتعاقد معه على هذا البند',
				'contractor_id.exists'=>'المقاول يجب أن يكون موجود بقاعدة البيانات',
				'contractor_unit_price.required'=>'يجب أدخال قيمة الوحدة المتعاقد عليها مع المقاول',
				'contractor_unit_price.numeric'=>'قيمة الودة للمقاول يجب أن تتكون من أرقام فقط',
				'contract_text.required'=>'يجب أدخال نص العقد',
				'started_at.date'=>'يجب على تاريخ البدئ أن يكون تاريخ صحيح'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withInput()->withErrors($validator);
			}

			$term=Term::findOrFail($id);
			$term->contractor_id=$req->input('contractor_id');
			$term->contractor_unit_price=$req->input('contractor_unit_price');
			$term->contract_text=$req->input('contract_text');
			$term->started_at=$req->input('started_at');
			$saved=$term->save();
			if(!$saved){
				return redirect()->back()->withInput()->with('insert_error','حدث خطأ خلال عقد هذا البند يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('showterm',$term->id)->with('success','تم عقد البند صاحب الكود '.$term->code.' بنجاح مع المقاول '.$term->contractor->name);
		}
		else
			abort('404');
	}
}
