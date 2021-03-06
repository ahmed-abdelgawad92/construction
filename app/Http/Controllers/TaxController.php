<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Project;
use App\Payment;
use App\Tax;
use App\Log;

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
	 * type = 1 ==> % || type = 2 ==> LE
	 * @return Response
	 */
	public function store(Request $req)
	{
		if(Auth::user()->type=='admin'){
			//validation rules
			$rules=[
				'name'=>'required',
				'value'=>'required|numeric',
				'type'=>'required|in:1,2',
				'project_id'=>'required|exists:projects,id'
			];
			//error messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم الأستقطاع',
				'value.required'=>'يجب أدخال نسبة أو قيمة الأستقطاع',
				'value.numeric'=>'قيمة أو نسبة الأستقطاع يجب أن تكون أرقام فقط',
				'project_id.required'=>'يجب أختيار المشروع',
				'project_id.exists'=>'يجب على المشروع أن يكون موجود بقاعدة البيانات'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$tax=new Tax;
			$tax->name=$req->input('name');
			$tax->value=$req->input('value');
			$tax->type=$req->input('type');
			$tax->project_id=$req->input('project_id');
			$saved=$tax->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذا الأستقطاع, يرجى المحاولة فى وقت لاحق');
			}
			$log=new Log;
			$log->table="taxes";
			$log->action="create";
			$log->record_id=$tax->id;
			$log->user_id=Auth::user()->id;
			$log->description="قام بأضافة أستقطاع قيمته ".$tax->value." ".$tax->getType()." بمشروع ".$tax->project->name;
			$log->save();
			return redirect()->route('showtax',$tax->project_id)->with('success','تم أضافة الأستقطاع بنجاح');
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
			$total_tax=$taxes->where('type',1)->where('deleted',0)->sum('value');
			$tax_type_value=$taxes->where('type',2)->where('deleted',0)->sum('value');
			$total_term_value=$project
					->transactions()
					->where('transactions.deleted',0)
					->sum('transactions.transaction');
			$total_tax_value=($total_term_value*($total_tax/100))+$tax_type_value;
			$total_tax += ($tax_type_value/$total_term_value)*100;
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
				'value'=>'required|numeric',
				'type'=>'required|in:1,2'
			];
			//error messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم الأستقطاع',
				'value.required'=>'يجب أدخال نسبة أو قيمة الأستقطاع',
				'value.numeric'=>'قيمة أو نسبة الأستقطاع يجب أن تكون أرقام فقط'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$tax=Tax::findOrFail($id);
			$oldType = $tax->getType();
			$description = "";
			$check = false;
			if($tax->name != $req->input("name")){
				$check = true;
				$description = "قام بتغيير اسم الاستقطاع من ".$tax->name." الي ".$req->input("name")." . ";
				$tax->name = $req->input("name");
			}
			if($tax->type != $req->input("type")){
				$check = true;
				$tax->type = $req->input("type");
				$description .= "قام بتغيير نوع الاستقطاع من '".$oldType."' الي '".$tax->getType()."' . ";
			}
			if ($tax->paid==0) {
				if($tax->value != $req->input("value")){
					$check = true;
					$description .= "قام بتغيير قيمة الاستقطاع من ".$tax->value." ".$oldType." الي ".$req->input("value")." ".$tax->getType()." . ";
					$tax->value = $req->input("value");
				}
			}
			if($check){
				$saved=$tax->save();
				if(!$saved){
					return redirect()->back()->with('update_error','حدث عطل خلال تعديل هذا الأستقطاع, يرجى المحاولة فى وقت لاحق');
				}
				$log=new Log;
				$log->table="taxes";
				$log->action="update";
				$log->record_id=$id;
				$log->user_id=Auth::user()->id;
				$log->description=$description;
				$log->save();
				return redirect()->route('showtax',$tax->project_id)->with('success','تم تعديل الأستقطاع بنجاح');
			}
		}
		else
			abort('404');
	}
	/**
	 * Pay Tax.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 public function payTax(Request $req, $id)
	 {
		 $tax = Tax::where('id',$id)->where('deleted',0)->firstOrFail();
		 if ($tax->paid==1) {
			 return redirect()->back()->with("info","هذا الأستقطاع مدفوع مسبقاً");
		 }
		 $project = $tax->project;
		 $payment_amount = 0;
		 switch ($tax->type) {
		 	case '1':
				$terms_value = $project;
				break;
		 	case '2':
				$payment_amount = $tax->value;
				break;
		 }
		 // ADD Payment to Payment Table
		 $payment = new Payment;
		 $payment->payment_amount = $payment_amount;
		 $payment->table_name = "taxes";
		 $payment->table_id = $id;
		 $payment->project_id = $project->id;
		 if ($req->input("loan") !== null) {
			 $payment->type = 1;
		 }else{
			 $payment->type = 0;
		 }
		 try {
			DB::beginTransaction();
		 	$tax->paid=1;
			$tax->save();
			$payment->save();
			DB::commit();
		 }
		 catch(Exception $e) {
			DB::rollBack();
		 	return redirect()->back()->with("insert_error","حدث عطل خلال دفع قيمة الأستقطاع, من فضلك حاول مرة أخرى");
		 }
		 return redirect()->back()->with("success","تم دفع قيمة الأستقطاع بنجاح");
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
			if(!$deleted){
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذه الضريبة, يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('showtax',$tax->project_id)->with('success','تم حذف الضريبة بنجاح');
		}
		else
			abort('404');
	}

}
