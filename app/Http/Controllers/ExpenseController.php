<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Payment;
use App\Project;
use App\Expense;
use App\Log;

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
				'project_id'=>'required|exists:projects,id',
				'payment_type'=>'in:0,1'
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
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$project = Project::findOrFail($req->input("project_id"));
			$expense=new Expense;
			$expense->whom=$req->input('whom');
			$expense->expense=$req->input('expense');
			$expense->project_id=$req->input('project_id');
			$saved=$expense->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذه الأكرامية, يرجى المحاولة فى وقت لاحق');
			}
			$log=new Log;
			$log->table="expenses";
			$log->action="create";
			$log->record_id=$expense->id;
			$log->user_id=Auth::user()->id;
			$log->description="قام بأضافة أكرامية تم دفعها بمشروع ".$project->name." قيمتها ".$expense->expense." جنيه .";
			$log->save();
			// ADD Payment to Payment Table
			$payment = new Payment;
			$payment->project_id= $project->id;
			$payment->type = $req->input("payment_type");
			$payment->payment_amount = $req->input("expense");
			$payment->table_name = "expenses";
			$payment->table_id = $expense->id;
			$payment->save();
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
			$expenses=$project->expenses()->where('expenses.deleted',0)->get();
			$total_expense=$project->expenses()->where('expenses.deleted',0)->sum('expense');
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
			$payment = Payment::where('project_id',$expense->project_id)->where('table_name','expenses')->where('table_id',$expense->id)->first();
			$check= false;
			$description="";
			if ($expense->expense != $req->input("expense")) {
				$check = true;
				$description.='قام بتغيير قيمة الأكرامية من "'.$expense->expense.'" إلى "'.$req->input("expense").'" . ';
				$expense->expense=$req->input('expense');
				$payment->payment_amount=$req->input("expense");
			}
			if ($expense->whom != $req->input("whom")) {
				$check = true;
				$description.='قام بتغيير وصف الأكرامية من "'.$expense->whom.'" إلى "'.$req->input("whom").'" . ';
				$expense->whom=$req->input('whom');
			}
			if($check){
				$saved=$expense->save();
				if(!$saved){
					return redirect()->back()->with('update_error','حدث عطل خلال تعديل هذه الأكرامية, يرجى المحاولة فى وقت لاحق');
				}
				$payment->save();
				$log=new Log;
				$log->table="expenses";
				$log->action="update";
				$log->record_id=$id;
				$log->user_id=Auth::user()->id;
				$log->description=$description;
				$log->save();
				return redirect()->route('showexpense',$expense->project_id)->with('success','تم تعديل الأكرامية بنجاح');
			}
			return redirect()->back()->with('info','لا يوجد تعديل حتى يتم حفظه');
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
			$expense->deleted = 1;
			$payment=$expense->payment();
			$payment->deleted=1;
			$payment->save();
			$deleted=$expense->save();
			if(!$deleted){
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذه الأكرامية, يرجى المحاولة فى وقت لاحق');
			}
			$log=new Log;
			$log->table="expenses";
			$log->action="delete";
			$log->record_id=$id;
			$log->user_id=Auth::user()->id;
			$log->description="قام بحذف أكرامية ".$expense->expense." جنيه من مشروع ".$expense->project->name;
			$log->save();
			return redirect()->route('showexpense',$expense->project_id)->with('success','تم حذف الأكرامية بنجاح');
		}
		else
			abort('404');
	}

}
