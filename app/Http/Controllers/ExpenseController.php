<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Project;
use App\Expense;

use Validator;
use Auth;

class ExpenseController extends Controller {

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
					'active'=>'exp',
					'project'=>$project
				];
			}
			else{
				$projects=Project::where('done',0)->get();
				$array=[
					'active'=>'exp',
					'projects'=>$projects
				];
			}
			return view('expense.add',$array);
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
				'whom'=>'required',
				'expense'=>'required|numeric',
				'project_id'=>'required|exists:projects,id'
			];
			//error messages
			$error_messages=[
				'whom.required'=>'يجب كتابة وصف لهذه الأكرامية',
				'expense.required'=>'يجب أدخال الأكرامية',
				'expense.numeric'=>'الأكرامية يجب أن تكون أرقام فقط',
				'project_id.required'=>'يجب أختيار المشروع',
				'project_id.exists'=>'يجب على المشروع أن يكون موجود بقاعدة البيانات'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();
			$expense=new Expense;
			$expense->whom=$req->input('whom');
			$expense->expense=$req->input('expense');
			$expense->project_id=$req->input('project_id');
			$saved=$expense->save();
			if(!$saved)
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذه الأكرامية, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showexpense',$expense->project_id)->with('success','تم أضافة الأكرامية بنجاح');
		}
		else
			abort('404');
	}

	//choose project to show expenses
	public function chooseProject()
	{
		$projects=Project::where('done',0)->get();
		$array=[
			'projects'=>$projects,
			'active'=>'exp'
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
			$expenses=$project->expenses;
			$total_expense=$project->expenses()->sum('expense');
			$array=[
				'active'=>'exp',
				'project'=>$project,
				'expenses'=>$expenses,
				'total_expense'=>$total_expense
			];
			return view('expense.show',$array);
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
			$expense=Expense::findOrFail($id);
			$array=[
				'active'=>'exp',
				'expense'=>$expense
			];
			return view('expense.edit',$array);
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
				'whom'=>'required',
				'expense'=>'required|numeric'
			];
			//error messages
			$error_messages=[
				'whom.required'=>'يجب كتابة وصف لهذه الأكرامية',
				'expense.required'=>'يجب أدخال الأكرامية',
				'expense.numeric'=>'الأكرامية يجب أن تكون أرقام فقط'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();
			$expense=Expense::findOrFail($id);
			$expense->whom=$req->input('whom');
			$expense->expense=$req->input('expense');
			$saved=$expense->save();
			if(!$saved)
				return redirect()->back()->with('update_error','حدث عطل خلال تعديل هذه الأكرامية, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showexpense',$expense->project_id)->with('success','تم تعديل الأكرامية بنجاح');
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
			$expense=Expense::findOrFail($id);
			$deleted=$expense->delete();
			if(!$deleted)
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذه الأكرامية, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showexpense',$expense->project_id)->with('success','تم حذف الأكرامية بنجاح');
		}
		else
			abort('404');
	}

}
