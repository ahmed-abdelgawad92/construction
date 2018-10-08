<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Store;
use App\Project;
use App\Supplier;
use App\StoreType;

use Auth;
use DB;
use Validator;

class StoreController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id)
	{
		if(Auth::user()->type=='admin'){
			$project=Project::findOrFail($id);
			$consumptions=$project->consumptions()
					->selectRaw('consumptions.type as type, sum(consumptions.amount) as amount')
					->groupBy('type')
					->get();
			$stores=Store::where('project_id',$id)
					->selectRaw('type, sum(amount) as amount ,sum(amount_paid) as amount_paid')
					->groupBy('type')
					->get();
			$projects=Project::where('done','=',0)->orderBy('name')->get();
			$store_types=StoreType::all();
			$array=[
				'active'=>'store',
				'stores'=>$stores,
				'project'=>$project,
				'consumptions'=>$consumptions,
				'projects'=>$projects,
				'store_types'=>$store_types
			];
			return view('store.all',$array);
		}
		else
			abort('404');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function findStore()
	{
		if(Auth::user()->type=='admin'){
			$projects=Project::where('done','=',0)->orderBy('name')->get();
			$array=['active'=>'store','projects'=>$projects];
			return view('store.all',$array);
		}
		else
			abort('404');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getAll(Request $req)
	{
		if(Auth::user()->type=='admin'){
			$rule=[
				'project_id'=>'required|exists:projects,id'
			];
			$message=[
				'project_id.required'=>'يجب أختيار مشروع',
				'project_id.exists'=>'من فضلك أختار مشروع صحيح'
			];
			$validator=Validator::make($req->all(),$rule,$message);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			return redirect()->route('allstores',$req->input('project_id'));
		}
		else
			abort('404');
	}

	/**
	 * Show the form for creating a new resource.
	 *	$tid to redirect to the term consumption if the form is coming from adding a consumption within a term and there was no enough
	 *  amount of raw in the store
	 * @return Response
	 */
	 public function getSuppliers($type=null)
	 {
		 $specificSuppliers=null;
		 if ($type===null) {
			 $suppliers = Supplier::where("deleted",0)->orderBy("name")->get();
			 $state="ALL";
		 }else{
			 $specificSuppliers = Supplier::where("type","like","%".$type."%")->where("deleted",0)->orderBy("name")->get();
			 $suppliers = Supplier::where("type","not like","%".$type."%")->where("deleted",0)->orderBy("name")->get();
			 $state="SPEC";
		 }
		 return json_encode(['state'=>$state,'suppliers'=>$suppliers,'specificSuppliers'=>$specificSuppliers]);
	 }
	/**
	 * Show the form for creating a new resource.
	 *	$tid to redirect to the term consumption if the form is coming from adding a consumption within a term and there was no enough
	 *  amount of raw in the store
	 * @return Response
	 */
	public function create($cid=null,$pid=null,$tid=0)
	{
		if(Auth::user()->type=='admin'){
			if($cid!=null && $cid!=0){
				$supplier=Supplier::where('id',$cid)->where("deleted",0)->firstOrFail();
				$array['supplier']=$supplier;
			}else{
				$suppliers=Supplier::where("deleted",0)->get();
				$array['suppliers']=$suppliers;
			}
			if($pid!=null && $pid!=0){
				$project=Project::where('id',$pid)->where('done',0)->where("deleted",0)->firstOrFail();
				$array['project']=$project;
			}else{
				$projects=Project::where('done',0)->where("deleted",0)->get();
				$array['projects']=$projects;
			}
			$store_types=StoreType::where("deleted",0)->get();
			$array['active']='store';
			$array['store_types']=$store_types;
			$array['tid']=$tid;
			return view('store.add',$array);
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
		// dd($req->all());
		if(Auth::user()->type=='admin'){
			$rules=[
				'project_id'=>'required|exists:projects,id',
				'supplier_id'=>'required|exists:suppliers,id',
				'type'=>'required|exists:store_types,name',
				'new_store_type'=>'nullable|unique:store_types,name',
				'new_store_type_unit'=>'nullable',
				'amount'=>'required|numeric',
				'value'=>'required|numeric',
				'amount_paid'=>'required|numeric'
			];
			$error_messages=[
				'project_id.required'=>'يجب أختيار مشروع',
				'project_id.exists'=>'المشروع يجب أن يكون موجود بقاعدة البيانات',
				'supplier_id.required'=>'يجب أختيار مقاول المورد',
				'supplier_id.exists'=>'المقاول المورد يجب أن يكون موجود بقاعدة البيانات',
				'type.required'=>'يجب أدخال نوع الخام',
				'type.exists'=>'نوع الخام يجب أن يكون موجود بقاعدة البيانات',
				'new_store_type.required'=>'يجب أدخال نوع الخام',
				'new_store_type.unique'=>'نوع الخام موجود بقاعدة البيانات',
				'new_store_type_unit.required'=>'يجب أدخال الوحدة',
				'amount.required'=>'يجب أدخال الكمية',
				'amount.numeric'=>'الكمية يجب أن تتكون من أرقام فقط',
				'value.numeric'=>'القيمة يجب أن تتكون من أرقام فقط',
				'value.required'=>'يجب أدخال القيمة',
				'amount_paid.numeric'=>'المبلغ المدفوع يجب أن يتكون من أرقام فقط',
				'amount_paid.required'=>'يجب أدخال المبلغ المدفوع'
			];
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$store=new Store;
			$store->type=$req->input('type');
			$store->value=$req->input('value');
			$store->amount=$req->input('amount');
			$store->amount_paid=$req->input('amount_paid');
			$store->project_id=$req->input('project_id');
			$store->supplier_id=$req->input('supplier_id');
			$saved=$store->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذه الكمية من الخامو يرجى المحاولة فى وقت لاحق');
			}
			if($req->input('tid')!=null&&$req->input('tid')!=0){
				return redirect()->route('addconsumption',['id'=>$req->input('tid')])->with('success','تم شراء الخام بنجاح و تم التخزين');
			}
			return redirect()->back()->with('success','تم شراء الخام بنجاح و تم التخزين');
		}
		else
			abort('404');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $type
	 * @return Response
	 */
	public function show($type)
	{
		if(Auth::user()->type=='admin'){
			$store=Store::where('type',$type)->firstOrFail();
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
			$store=Store::findOrFail($id);
			$array=['active'=>'store','store'=>$store];
			return view('store.edit',$array);
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
				'project_id'=>'required|exists:projects,id',
				'supplier_id'=>'required|exists:suppliers,id',
				'type'=>'required|exists:store_types,name',
				'amount'=>'required|numeric',
				'value'=>'numeric',
				'amount_paid'=>'numeric'
			];
			$error_messages=[
				'project_id.required'=>'يجب أختيار مشروع',
				'project_id.exists'=>'المشروع يجب أن يكون موجود بقاعدة البيانات',
				'supplier_id.required'=>'يجب أختيار مقاول',
				'supplier_id.exists'=>'المقاول يجب أن يكون موجود بقاعدة البيانات',
				'type.required'=>'يجب أدخال نوع الخام',
				'type.exists'=>'نوع الخام يجب أن يكون موجود بقاعدة البيانات',
				'amount.required'=>'يجب أدخال الكمية',
				'amount.numeric'=>'الكمية يجب أن تتكون من أرقام فقط',
				'value.numeric'=>'القيمة يجب أن تتكون من أرقام فقط',
				'amount_paid.numeric'=>'المبلغ المدفوع يجب أن يتكون من أرقام فقط'
			];
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$store=Store::findOrFail($id);
			$store->type=$req->input('type');
			$store->value=$req->input('value');
			$store->amount=$req->input('amount');
			$store->amount_paid=$req->input('amount_paid');
			$store->project_id=$req->input('project_id');
			$store->supplier_id=$req->input('supplier_id');
			$saved=$store->save();
			if(!$saved){
				return redirect()->back()->with('update_error','حدث عطل خلال أضافة هذه الكمية من الخامو يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->back()->with('success','تم شراء الخام بنجاح و تم التخزين');
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
			$store=Store::findOrFail($id);
			$deleted=$store->delete();
			if(!$deleted){
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف كمية الخام , يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->back()->with('success','تم حذف كمية الخام بنجاح');
		}
		else
			abort('404');
	}

}
