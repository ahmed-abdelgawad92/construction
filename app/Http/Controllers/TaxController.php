<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Project;
use App\Tax;

use Validator;
use Auth;

class TaxController extends Controller {

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id=null)
	{
		if(Auth::user()->type=='admin'){
			if($id!=null){
				$project=Project::findOrFail($id);
				$array=[
					'active'=>'tax',
					'project'=>$project
				];
			}
			else{
				$projects=Project::where('done',0)->get();
				$array=[
					'active'=>'tax',
					'projects'=>$projects
				];
			}
			return view('tax.add',$array);
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
			//validation rules
			$rules=[
				'name'=>'required',
				'percent'=>'required|numeric',
				'project_id'=>'required|exists:projects,id'
			];
			//error messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم الضريبة',
				'percent.required'=>'يجب أدخال نسبة الضريبة',
				'percent.numeric'=>'نسبة الضريبة يجب أن تكون أرقام فقط',
				'project_id.required'=>'يجب أختيار المشروع',
				'project_id.exists'=>'يجب على المشروع أن يكون موجود بقاعدة البيانات'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();
			$sum=Tax::where('project_id',$req->input('project_id'))->sum('percent');
			if(($sum+$req->input('percent'))>20)
				return redirect()->back()->with('insert_error','مجموع ضرئب المشروع لا يمكن أن تتعدى العشرون بالمئة');
			$tax=new Tax;
			$tax->name=$req->input('name');
			$tax->percent=$req->input('percent');
			$tax->project_id=$req->input('project_id');
			$saved=$tax->save();
			if(!$saved)
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذه الضريبة, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showtax',$tax->project_id)->with('success','تم أضافة الضريبة بنجاح');
		}
		else
			abort('404');
	}

	//choose project to show taxes
	public function chooseProject()
	{
		$projects=Project::where('done',0)->get();
		$array=[
			'projects'=>$projects,
			'active'=>'tax'
		];
		return view('production.allprojects',$array);
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
			$project=Project::findOrFail($id);
			$taxes=$project->taxes;
			$total_tax=$project->taxes()->sum('percent');
			$total_term_value=$project
					->transactions()
					->where('transactions.type','in')
					->sum('transactions.transaction');
			$total_tax_value=$total_term_value*($total_tax/100);
			$array=[
				'active'=>'tax',
				'project'=>$project,
				'taxes'=>$taxes,
				'total_tax'=>$total_tax,
				'total_term_value'=>$total_term_value,
				'total_tax_value'=>$total_tax_value
			];
			return view('tax.show',$array);
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
			$tax=Tax::findOrFail($id);
			$array=[
				'active'=>'tax',
				'tax'=>$tax
			];
			return view('tax.edit',$array);
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
				'name'=>'required',
				'percent'=>'required|numeric'
			];
			//error messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم الضريبة',
				'percent.required'=>'يجب أدخال نسبة الضريبة',
				'percent.numeric'=>'نسبة الضريبة يجب أن تكون أرقام فقط'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();
			$tax=Tax::findOrFail($id);
			$tax->name=$req->input('name');
			$tax->percent=$req->input('percent');
			$saved=$tax->save();
			if(!$saved)
				return redirect()->back()->with('update_error','حدث عطل خلال تعديل هذه الضريبة, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showtax',$tax->project_id)->with('success','تم تعديل الضريبة بنجاح');
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
			$tax=Tax::findOrFail($id);
			$deleted=$tax->delete();
			if(!$deleted)
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذه الضريبة, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showtax',$tax->project_id)->with('success','تم حذف الضريبة بنجاح');
		}
		else
			abort('404');
	}

}
