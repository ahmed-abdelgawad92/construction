<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Transaction;
use App\Contract;
use App\Payment;
use App\Project;
use App\Term;
use App\Log;

use Carbon\Carbon;
use Validator;
use Auth;
use DB;

class TransactionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id)
	{
		if(Auth::user()->type=='admin'){
			$project=Project::findOrFail($id);
			$total_in=$project->transactions()->where('transactions.deleted',0)->sum('transactions.transaction');
			$total_out=$project->payments()->where('payments.deleted',0)->where('payments.table_name','transactions')->sum('payments.payment_amount');
			$terms=$project
					->terms()
					->where('started_at','<=',Carbon::today())
					->with('transactions','contracts')
					->orderBy('terms.code')
					->get();
			$array=[
				'active'=>'trans',
				'project'=>$project,
				'total_in'=>$total_in,
				'total_out'=>$total_out,
				'terms'=>$terms
			];
			return view('transaction.all',$array);
		}
		else
			abort('404');
	}


	//all term transactions
	public function allTermTransaction($id)
	{
		if(Auth::user()->type=='admin'){
			$term=Term::findOrFail($id);
			$transactions_in=$term->transactions()->where('transactions.deleted',0)->orderBy('transactions.created_at')->get();
			$transactions_out=$term->getPayments();
			// dd($transactions_out);
			$total_in=$term->transactions()->where('transactions.deleted',0)->sum('transactions.transaction');
			$total_out=$term->payments();
			$array=[
				'active'=>'trans',
				'term'=>$term,
				'transactions_in'=>$transactions_in,
				'transactions_out'=>$transactions_out,
				'total_in'=>$total_in,
				'total_out'=>$total_out
			];
			return view('transaction.all',$array);
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
			$project=Project::findOrFail($id);
			$terms=$project
				->terms()
				->where('terms.started_at','<=',Carbon::today())
				->with('transactions','productions')
				->orderBy('terms.code')
				->get();
			$array=[
				'project'=>$project,
				'terms'=>$terms,
				'active'=>'trans'
			];
			return view('transaction.create',$array);
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
			$checked = $req->input("checked")??[];
			$terms = $req->input('term');
			$production=0;
			$transactions = array();
			$changedTerms = array();
			$errors = array();
			if(count($checked)==0){
				return redirect()->back()->with('empty_error','يجب أختيار صف واحد على الأقل')->withInput();
			}
			foreach($checked as $i) {
				$term=Term::findOrFail($terms[$i]['id']);
				if(!is_numeric($terms[$i]['current_amount'])){
					$errors['type_error']='جميع المدخلات يجب أن تكون أرقام فقط';
					$errors['current_amount'.$i]=1;
				}else{
					if($terms[$i]['current_amount']>0){
						$production=$terms[$i]['current_amount'];
						$transaction=new Transaction;
						$transaction->transaction=$terms[$i]['current_amount']*$term->value;
						$transaction->term_id=$term->id;
						$transactions[]=$transaction;
					}
				}
				if (isset($terms[$i]['deduction_percent'])) {
					if(!is_numeric($terms[$i]['deduction_percent'])){
						$errors['type_error']='جميع المدخلات يجب أن تكون أرقام فقط';
						$errors['deduction_percent'.$i]=1;
					}else{
						if($terms[$i]['deduction_percent']>0 && $terms[$i]['deduction_percent']<100){
							if($term->deduction_percent != $terms[$i]['deduction_percent']) {
								$term->deduction_percent=$terms[$i]['deduction_percent'];
								$changedTerms[]=$term;
							}
						}else{
							$errors['error_100']='يجب أدخال نسبة الأستقطاع و تكون أرقام فقط ولا أن تكون أكثر من 99% أو أقل من 0%';
							$errors['deduction_percent'.$i]=1;
						}
					}
				}
				if (isset($terms[$i]['deduction_value'])) {
					if(!is_numeric($terms[$i]['deduction_value'])){
						$errors['type_error']='جميع المدخلات يجب أن تكون أرقام فقط';
						$errors['deduction_value'.$i]=1;
					}else{
						$production += $term->productions()->where('productions.deleted',0)->sum('productions.amount');
						$total_production_value= $production * $term->value;
						$deduction_percent = ($terms[$i]['deduction_value']/$total_production_value) * 100;
						if ($deduction_percent>99 ) {
							$errors['error_100']='يجب أدخال نسبة الأستقطاع و تكون أرقام فقط ولا أن تكون أكثر من 99% أو أقل من 0%';
							$errors['deduction_value'.$i]=1;
						}else{
							if($term->deduction_percent != $deduction_percent){
								$term->deduction_percent= $deduction_percent;
								$changedTerms[]=$term;
							}
						}
					}
				}
			}
			if(count($errors)>0){
				return redirect()->back()->with($errors)->withInput();
			}
			try {
				DB::beginTransaction();
				$count = 0;
				foreach ($transactions as $transaction) {
					$transaction->save();
					$log=new Log;
					$log->table="transactions";
					$log->action="create";
					$log->record_id=$transaction->id;
					$log->user_id=Auth::user()->id;
					$log->description='قام باضافة مستخلص قيمته '.$transaction->transaction.' جنيه الى البند '.$transaction->term->code.' بمشروع '.$transaction->term->project->name;
					$log->save();
					$count++;
				}
				foreach ($changedTerms as $term) {
					$term->save();
					$log=new Log;
					$log->table="terms";
					$log->action="update";
					$log->record_id=$term->id;
					$log->user_id=Auth::user()->id;
					$log->description='قام بتغيير نسبة الاستقطاع الى '.$term->deduction_percent.'%';
					$log->save();
					$count++;
				}
				DB::commit();
			} catch (\Exception $e) {
				DB::rollBack();
				return redirect()->back()->with('insert_error','حدث عطل خلال حفظ هذا المستخلص , يرجى المحاولة فى وقت لاحق');
			}
			if ($count>0) {
				return redirect()->route('createextractor',$id)->with('success','تم حفظ المستخلص بنجاح, لاحظ أنه جميع كمية الأنتاج السابقة للبنود التى أختيرت أضيفت إليها الكمية الحالية التى أدخلتموها (أو التى أستخلصها النظام) . أذا وجدت ألأن كمية الأنتاج لبند أنت أدخلته لا تساوى الصفر , لا تقلق فقط أعلم أنه تم محاسبتك على كمية أكثر من مما أنتجت (فى هذه الحالة أذا كنت أنتجت الكمية كلها فربما نسيت أضافت هذا الأنتاج)');
			}else{
				return redirect()->route('createextractor',$id)->with('info','الكميات الحالية التي تساوي الصفر او اقل لا يتم حفظها');
			}
		}
		else
			abort('404');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	 public function createForTerm($id)
	 {
		 $term = Term::findOrFail($id);
		 $prev_production= $term->transactions()->where('transactions.deleted',0)->sum('transactions.transaction') / $term->value;
		 $total_production= $term->productions()->where('productions.deleted',0)->sum('productions.amount');
		 $current_production= $total_production - $prev_production;
		 $deduction_value= ($prev_production>$total_production) ? ($prev_production*$term->value)*($term->deduction_percent/100) : ($total_production*$term->value)*($term->deduction_percent/100);
		 $net_value= ($prev_production>$total_production) ? ($prev_production*$term->value)-($deduction_value) : ($total_production*$term->value)-($deduction_value);

		 $array = [
			 'active'=>'trans',
			 'term' => $term,
			 'prev_production'=>$prev_production,
			 'total_production'=>$total_production,
			 'current_production'=>$current_production,
			 'deduction_value'=>$deduction_value,
			 'net_value'=>$net_value
		 ];
		 return view('transaction.addForTerm',$array);
	 }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	 public function storeForTerm(Request $req,$id)
	 {
		 $term = Term::findOrFail($id);
		 $count = 0;
		 $rules=[
			 'current_production'=>'required|numeric',
			 'deduction_percent'=>'required|numeric|min:0|max:99'
		 ];
		 $error_messages=[
			 'current_production.required'=>'يجب أدخال الكمية الحالية',
			 'current_production.numeric'=>'يجب أن تتكون من أرقام فقط',
			 'deduction_percent.required'=>'يجب أدخال نسبة الأستقطاع',
			 'deduction_percent.numeric'=>'يجب أن تتكون من أرقام فقط',
			 'deduction_percent.min'=>'يجب ان لا تقل عن الصفر',
			 'deduction_percent.max'=>'يجب أن لا تزيد عن 99%'
		 ];
		 $validator = Validator::make($req->all(),$rules,$error_messages);
		  if ($validator->fails()) {
		  	return redirect()->back()->withErrors($validator)->withInput();
		 }
		 if($req->input("current_production")>0){
			 $transaction = new Transaction;
			 $transaction->transaction = $req->input("current_production")*$term->value;
			 $transaction->term_id=$term->id;
			 $saved = $transaction->save();
			 //check if saved correctly
			 if (!$saved) {
			 	return redirect()->back()->with("insert_error","حدث عطل خلال حفظ هذا المستخلص");
			 }
			 $log=new Log;
			 $log->table="transactions";
			 $log->action="create";
			 $log->record_id=$transaction->id;
			 $log->user_id=Auth::user()->id;
			 $log->description='قام باضافة مستخلص قيمته '.$transaction->transaction.' جنيه الى البند '.$term->code.' بمشروع '.$term->project->name;
			 $log->save();
			 $count++;
		 }
		 if ($req->input("deduction_percent")!=$term->deduction_percent) {
			 $term->deduction_percent = $req->input("deduction_percent");
			 $saved=$term->save();
			 //check if saved correctly
			 if (!$saved) {
			 	return redirect()->back()->with("insert_error","حدث عطل خلال تعديل نسبة الأستقطاع");
			 }
			 $log=new Log;
			 $log->table="terms";
			 $log->action="update";
			 $log->record_id=$term->id;
			 $log->user_id=Auth::user()->id;
			 $log->description='قام بتغيير نسبة الاستقطاع الى '.$term->deduction_percent.'%';
			 $log->save();
			 $count++;
		 }
		 if ($count>0) {
			 return redirect()->back()->with("success","تم حفظ المستخلص أو نسبة الأستقطاع بنجاح");
		 }
		 return redirect()->back()->with("info","لا يوجد شئ كي يتم حفظه");
	 }
	/**
	 * Show the form for creating a new OUT PAYMENT to a Contract resource.
	 *
	 * @return Response
	 */
	 public function createForContract($id)
	 {
		 $contract = Contract::with('contractor','term','productions')->findOrFail($id);
		 $total_production = $contract->productions()->where('productions.deleted',0)->sum('productions.amount');
		 $prev_production = $contract->paidProductions()/$contract->unit_price;
		 $current_production = $total_production - $prev_production;
		 $array = [
			 'active'=>'trans',
			 'contract' => $contract,
			 'current_production' => $current_production,
			 'prev_production' => $prev_production,
			 'total_production' => $total_production
		 ];
		 return view('transaction.addForContract',$array);
	 }

	/**
	 * Store a newly created OUT PAYMENT to a Contract resource in storage.
	 *
	 * @return Response
	 */
	 public function storeForContract(Request $req, $id)
	 {
		 $contract = Contract::findOrFail($id);
		 $rules=[
			 'current_production'=>'required|numeric|min:0',
			 'paymen_type'=>'nullable|in:0,1'
		 ];
		 $error_messages=[
			 'current_production.required'=>'يجب أدخال الكمية الحالية',
			 'current_production.numeric'=>'يجب أن تتكون من أرقام فقط',
			 'current_production.numeric'=>'يجب على الأقل أن يكون صفر'
		 ];
		 $validator = Validator::make($req->all(),$rules,$error_messages);
		  if ($validator->fails()) {
		  	return redirect()->back()->withErrors($validator)->withInput();
		 }
		 if($req->input("current_production")>0){
			 // ADD Payment to Payment Table
			 $payment = new Payment;
			 $payment->type = $req->input("payment_type")??0;
			 $payment->payment_amount = $req->input("current_production")*$contract->unit_price;
			 $payment->table_name = "transactions";
			 $payment->table_id = $contract->id;
			 $payment->project_id = $contract->term->project_id;
			 $saved = $payment->save();
			 //check if saved correctly
			 if (!$saved) {
			 	return redirect()->back()->with("insert_error","حدث عطل خلال حفظ هذا المستخلص");
			 }
			 $log=new Log;
			 $log->table="payments";
			 $log->action="create";
			 $log->record_id=$payment->id;
			 $log->user_id=Auth::user()->id;
			 $log->description='قام باضافة مستخلص قيمته '.$payment->payment_amount.' جنيه الى المقاول '.$contract->contractor->name.' بمشروع '.$contract->term->project->name.' بند '.$contract->term->code;
			 $log->save();
			 return redirect()->back()->with('success','تم حفظ المعاملة بنجاح');
		 }
	 }


	//print extractor
	public function printTable($id)
	{
		if(Auth::user()->type=='admin'){
			$project=Project::findOrFail($id);
			$terms=$project
				->terms()
				->where('terms.started_at','<=',Carbon::today())
				->with('transactions','productions')
				->orderBy('terms.code')
				->get();
			$array=[
				'project'=>$project,
				'terms'=>$terms,
				'active'=>'trans'
			];
			return view('transaction.print',$array);
		}
		else
			abort('404');
	}

	//choose project
	public function chooseProject()
	{
		if(Auth::user()->type='admin'){
			$projects=Project::where('done',0)->get();
			$array=['active'=>'trans','projects'=>$projects];
			return view('transaction.allprojects',$array);
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
			$rules=[
				'transaction'=>'required|numeric'
			];
			$error_message=[
				'transaction.required'=>'يجب أدخال قيمة المستخلص',
				'transaction.numeric'=>'قيمة المستخلص يجب أن تكون أرقام فقط'
			];
			$validator=Validator::make($req->all(),$rules,$error_message);
			if($validator->fails())
				return redirect()->back()->withErrors($validator);
			$transaction=Transaction::findOrFail($id);
			$transaction->transaction=$req->input('transaction');
			$saved=$transaction->save();
			if(!$saved)
				return redirect()->back()->with('delete_error','حدث عطل خلال تعديل قيمة المستخلص, يرجى المحاولة فى وقت لاحق');
			return redirect()->back()->with('success','تم تعديل قيمة المستخلص بنجاح');
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
			$transaction=Transaction::findOrFail($id);
			$deleted=$transaction.delete();
			if(!$deleted)
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا المستخلص, يرجى المحاولة فى وقت لاحق');
			return redirect()->back()->with('success','تم حذف المستخلص بنجاح');
		}
		else
			abort('404');
	}

}
