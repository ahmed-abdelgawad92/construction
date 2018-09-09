<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Project;
use App\Term;
use App\Transaction;

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
			$total_in=$project->transactions()->where('transactions.type','in')->sum('transactions.transaction');
			$total_out=$project->transactions()->where('transactions.type','out')->sum('transactions.transaction');
			$terms=$project
					->terms()
					->where('started_at','<=',Carbon::today())
					->with('transactions','contractor')
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
			$transactions_in=$term->transactions()->where('type','in')->get();
			$transactions_out=$term->transactions()->where('type','out')->get();
			$total_in=$term->transactions()->where('type','in')->sum('transactions.transaction');
			$total_out=$term->transactions()->where('type','out')->sum('transactions.transaction');
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
			$terms=$req->input('term');
			$found=0;
			$transactions=array();
			$errors=array('type_error'=>'الكمية الحالية يجب أن تكون أرقام فقط');
			for($i=1;$i<=count($terms);$i++) {
				if(isset($terms[$i]['check'])&&$terms[$i]['check']==1){
					$found++;
					if(!is_numeric($terms[$i]['current_amount'])){
						$errors['current_amount'.$i]=1;
					}else{
						if($terms[$i]['current_amount']>0){
							$term=Term::findOrFail($terms[$i]['id']);
							$transaction=new Transaction;
							$transaction->transaction=$terms[$i]['current_amount']*$term->value;
							$transaction->term_id=$term->id;
							$transaction->type='in';
							$transactions[]=$transaction;
						}
					}
				}
			}
			if($found==0)
				return redirect()->back()->with('empty_error','يجب أختيار صف واحد على الأقل')->withInput();
			if(count($errors)>1)
				return redirect()->back()->with($errors)->withInput();
			DB::beginTransaction();
			$insert_error=0;
			foreach ($transactions as $transaction) {
				$saved=$transaction->save();
				if(!$saved){
					$insert_error=1;
					DB::rollBack();
				}
			}
			DB::commit();
			if($insert_error==1)
				return redirect()->back()->with('insert_error','حدث عطل خلال حفظ هذا المستخلص , يرجى المحاولة فى وقت لاحق');
			return redirect()->route('createextractor',$id)->with('success','تم حفظ المستخلص بنجاح, لاحظ أنه جميع كمية الأنتاج السابقة للبنود التى أختيرت أضيفت إليها الكمية الحالية التى أدخلتموها (أو التى أستخلصها النظام) . أذا وجدت ألأن كيمة الأنتاج لبند أنت أدخلته لا تساوى الصفر , لا تقلق فقط أعلم أنك أنتجت أكثر من ما تم محاسبتك عليه أو تم محاسبتك على كمية أكثر من ما أنتجت(فى هذه الحالة أذا كنت أنتجت الكمية كلها فربما نسيت أضافت هذا الأنتاج)');
		}
		else
			abort('404');
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
