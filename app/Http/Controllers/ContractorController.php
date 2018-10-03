<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Contractor;
use App\TermType;
use App\Log;

use Validator;
use Auth;

use Carbon\Carbon;

class ContractorController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Auth::user()->type=='admin'){
			$contractors=Contractor::all();
			$array=['active'=>'cont','contractors'=>$contractors];
			return view('contractor.all',$array);
		}
		else
			abort('404');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if(Auth::user()->type=='admin'){
			$term_types=TermType::all();
			$array=['active'=>'cont','term_types'=>$term_types];
			return view('contractor.add',$array);
		}else{
			abort('404');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
		if(Auth::user()->type=='admin'){
			//Validation Rules
			$rules=[
				'name'=>'required|regex:/^[\pL\s]+$/u',
				'type'=>'required_without:contractor_type|exists:term_types,name',
				'contractor_type.*'=>"sometimes|required|unique:term_types,name|regex:/^[\pL\s]+$/u",
				'address'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'center'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'phone.*'=>'required|numeric',
			];
			//Error Vaildation Messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم المقاول',
				'name.regex'=>'أسم المقاول يجب أن يتكون من حروف و مسافات فقط',
				'type.required_without'=>'يجب أدخال نوع المقاول',
				'type.exists'=>'أنواع المقاول يجب أن تكون مسجلة بقاعدة البيانات فى أنواع البنود',
				'contractor_type.*.required'=>'يجب أدخال نوع المقاول',
				'contractor_type.*.unique'=>'هذا النوع موجود بالفعل بقاعدة البيانات , من فضلك اختاره من اعلى بدلاً من اضافته',
				'address.regex'=>'الشارع يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'center.regex'=>'المركز يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'city.required'=>'يجب أدخال المدينة',
				'city.regex'=>'المدينة يجب أن تتكون من حروف و أرقام و مسافات فقط',
				'phone.*.required'=>'يجب أدخال التليفون',
				'phone.*.numeric'=>'التليفون يجب أن يتكون من أرقام فقط'
			];
			//validate the request
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}

			//STORE CONTRACTOR IN DB
			$contractor=new Contractor;
			$contractor->name=$req->input('name');
			$contractor->address=$req->input('address');
			$contractor->center=$req->input('center');
			$contractor->city=$req->input('city');
			$contractor->phone=implode(",",$req->input('phone'));
			$contractor->type=implode(",",array_merge($req->input("type")??[],$req->input('contractor_type')??[]));
			foreach ($req->input('contractor_type')??[] as $type) {
				$term_type = new TermType;
				$term_type->name=$type;
				$term_type->save();
				$log=new Log;
				$log->table="term_types";
				$log->action="create";
				$log->record_id=$term_type->id;
				$log->user_id=Auth::user()->id;
				$log->description="قام بأضافة نوع بند جديد";
				$log->save();
			}
			$saved=$contractor->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث خطأ خلال أضافة هذا المقاول , يرجى المحاولة فى وقت لاحق');
			}
			$log=new Log;
			$log->table="contractors";
			$log->action="create";
			$log->record_id=$contractor->id;
			$log->user_id=Auth::user()->id;
			$log->description="قام باضافة مقاول جديد";
			$log->save();
			return redirect()->route('showcontractor',$contractor->id)->with('success','تم حفظ المقاول بنجاح');
		}else{
			abort('404');
		}
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
			$contractor=Contractor::findOrFail($id);
			$terms=$contractor->contracts()
				->where('started_at','<=',Carbon::today())
				->orderBy('started_at','desc')
				->take(3)
				->get();
			$array=['active'=>'cont','contractor'=>$contractor,'terms'=>$terms];

			return view('contractor.show',$array);
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
			$contractor=Contractor::findOrFail($id);
			$contractor_types=explode(",",$contractor->type);
			$term_types=TermType::all();
			$array=[
				'active'=>'cont',
				'contractor'=>$contractor,
				'term_types'=>$term_types,
				'contractor_types'=>$contractor_types
			];
			return view('contractor.edit',$array);
		}else{
			abort('404');
		}
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
			//Validation Rules
			$rules=[
				'name'=>'required|regex:/^[\pL\s]+$/u',
				'type'=>'required|exists:term_types,name',
				'address'=>'regex:/^[\pL\pN\s]+$/u',
				'center'=>'regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'phone'=>'required|numeric',
			];
			//Error Vaildation Messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم المقاول',
				'name.regex'=>'أسم المقاول يجب أن يتكون من حروف و مسافات فقط',
				'type.required'=>'يجب أدخال نوع المقاول',
				'type.exists'=>'أنواع المقاول يجب أن تكون مسجلة بقاعدة البيانات فى أنواع البنود',
				'address.regex'=>'الشارع يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'center.regex'=>'المركز يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'city.required'=>'يجب أدخال المدينة',
				'city.regex'=>'المدينة يجب أن تتكون من حروف و أرقام و مسافات فقط',
				'phone.required'=>'يجب أدخال التليفون',
				'phone.numeric'=>'التليفون يجب أن يتكون من أرقام فقط'
			];
			//validate the request
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}

			//STORE CONTRACTOR IN DB
			$contractor=Contractor::findOrFail($id);
			$contractor->name=$req->input('name');
			$contractor->type=$req->input('type');
			$contractor->address=$req->input('address');
			$contractor->center=$req->input('center');
			$contractor->city=$req->input('city');
			$contractor->phone=$req->input('phone');
			$contractor->type="";
			foreach ($req->input('type') as $type) {
				$contractor->type.=$type.",";
			}
			$contractor->type=substr($contractor->type,0,-1);
			$saved=$contractor->save();
			if(!$saved){
				return redirect()->back()->with('update_error','حدث خطأ خلال تعديل هذا المقاول و يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('showcontractor',$contractor->id)->with('success','تم تعديل المقاول بنجاح');
		}else{
			abort('404');
		}
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
			$contractor=Contractor::findOrFail($id);
			$deleted=$contractor->delete();
			if(!$deleted){
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا المقاول يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('allcontractor')->with('success','تم حذف المقاول بنجاح');
		}else{
			abort('404');
		}
	}

	/**
	 * Get All Contracted Terms
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getAllTerms($id)
	{
		if(Auth::user()->type=='admin')
		{
			$contractor=Contractor::findOrFail($id);
			$terms=$contractor->terms;
			$array=['active'=>'cont','contractor'=>$contractor,'terms'=>$terms];
			return view('contractor.terms',$array);
		}
		else
			abort('404');
	}
}
