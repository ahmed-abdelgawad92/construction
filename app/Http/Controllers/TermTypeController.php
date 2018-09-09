<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\TermType;
use Validator;
use Auth;

class TermTypeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		if(Auth::user()->type=='admin')
		{
			$term_types=TermType::all();
			$array=['active'=>'term','term_types'=>$term_types];
			return view('term.alltype',$array);
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
		//
		if(Auth::user()->type=='admin')
		{
			$array=['active'=>'term'];
			return view('term.addtype',$array);
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
		if(Auth::user()->type=='admin')
		{
			//rule
			$rules=[
				'type'=>'required|regex:/^[\pL\s]+$/u|unique:term_types,name|max:255'
			];
			//message
			$error_messages=[
				'type.required'=>'يجب أدخال نوع البند',
				'type.regex'=>'يجب نوع البند أن يتكون من حروف و مسافات فقط',
				'type.max'=>'عدد الحروف تعدى العدد المسموح',
				'type.unique'=>'هذا النوع موجود بالفعل'
			];
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withInput()->withErrors($validator);
			$term_type= new TermType;
			$term_type->name=$req->input('type');
			$saved=$term_type->save();
			if(!$saved)
				return redirect()->back()->with('insert_error','حدث عطل خلال أدخال نوع بند جديد');
			return redirect()->route('alltermtype')->with('success','تم حفظ نوع بند بنجاح');
		}else
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
		//
		if(Auth::user()->type=='admin')
		{
			$type=TermType::findOrFail($id);
			$array=['active'=>'term','type'=>$type];
			return view('term.edittype',$array);
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
		if(Auth::user()->type=='admin')
		{
			//rule
			$rules=[
				'type'=>'required|regex:/^[\pL\s]+$/u|unique:term_types,name|max:255'
			];
			//message
			$error_messages=[
				'type.required'=>'يجب أدخال نوع البند',
				'type.regex'=>'يجب نوع البند أن يتكون من حروف و مسافات فقط',
				'type.max'=>'عدد الحروف تعدى العدد المسموح',
				'type.unique'=>'هذا النوع موجود بالفعل'
			];
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withInput()->withErrors($validator);
			$term_type= TermType::findOrFail($id);
			$term_type->name=$req->input('type');
			$saved=$term_type->save();
			if(!$saved)
				return redirect()->back()->with('insert_error','حدث عطل خلال تعديل نوع البند');
			return redirect()->route('alltermtype')->with('success','تم تعديل نوع البند بنجاح');
		}else
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
		//
		if(Auth::user()->type=='admin')
		{
			$term_type=TermType::findOrFail($id);
			$deleted=$term_type->delete();
			if(!$deleted){
				return redirect()->route('alltermtype')->with('delete_error','حدث عطل خلال حذف هذا النوع يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('alltermtype')->with('success','تم حذف نوع البند ('.$term_type->name.') بنجاح');
		}
		else
			abort('404');
	}

}
