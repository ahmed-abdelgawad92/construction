<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\StoreType;
use Validator;
use Auth;

class StoreTypeController extends Controller {

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
			$store_types=StoreType::all();
			$array=['active'=>'store','store_types'=>$store_types];
			return view('store.alltype',$array);
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
			$array=['active'=>'store'];
			return view('store.addtype',$array);
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
				'type'=>'required|regex:/^[\pL\s]+$/u|unique:store_types,name|max:255',
				'unit'=>'required|alpha_num|max:30'
			];
			//message
			$error_messages=[
				'type.required'=>'يجب أدخال نوع البند',
				'type.regex'=>'نوع البند يجب أن يتكون من حروف و مسافات فقط',
				'type.unique'=>'هذا النوع موجود بالفعل',
				'type.max'=>'عدد الحروف تعدى العدد المسموح',
				'unit.required'=>'يجب أدخال الوحدة',
				'unit.alpha_num'=>'الوحدة يجب تتكون من أرقام و حروف فقط',
				'unit.max'=>'عدد الحروف و الأرقام لا يمكن أن يتعدى 30 فى الوحدة'
			];
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withInput()->withErrors($validator);
			$store_type= new StoreType;
			$store_type->name=$req->input('type');
			$store_type->unit=$req->input('unit');
			$saved=$store_type->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث عطل خلال أدخال نوع بند جديد');
			}
			$log=new Log;
			$log->table="store_types";
			$log->action="create";
			$log->record_id=$store_type->id;
			$log->user_id=Auth::user()->id;
			$log->description="قام بأضافة نوع خام جديد بالبرنامج (".$store_type->name.")";
			$log->save();
			return redirect()->route('allstoretype')->with('success','تم حفظ نوع بند بنجاح');
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
			$type=StoreType::findOrFail($id);
			$array=['active'=>'store','type'=>$type];
			return view('store.edittype',$array);
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
				'type'=>'required|regex:/^[\pL\s]+$/u|unique:store_types,name|max:255',
				'unit'=>'required|alpha_num|max:30'
			];
			//message
			$error_messages=[
				'type.required'=>'يجب أدخال نوع البند',
				'type.regex'=>'نوع البند يجب أن يتكون من حروف و مسافات فقط',
				'type.unique'=>'هذا النوع موجود بالفعل',
				'type.max'=>'عدد الحروف تعدى العدد المسموح',
				'unit.required'=>'يجب أدخال الوحدة',
				'unit.alpha_num'=>'الوحدة يجب تتكون من أرقام و حروف فقط',
				'unit.max'=>'عدد الحروف و الأرقام لا يمكن أن يتعدى 30 فى الوحدة'
			];
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withInput()->withErrors($validator);
			$store_type= StoreType::findOrFail($id);
			$description ="";
			$check = false;
			if($store_type->name!=$req->input('type')){
				$description.="قام بتغيير نوع الخام بالبرنامج من (".$store_type->name.") إلى (".$req->input("type")."). ";
				$check=true;
				$store_type->name=$req->input('type');
			}
			if($store_type->unit!=$req->input('unit')){
				$description.="قام بتغيير وحدة الخام بالبرنامج من (".$store_type->unit.") إلى (".$req->input("unit")."). ";
				$check=true;
				$store_type->unit=$req->input('unit');
			}
			if($check){
				$saved=$store_type->save();
				if(!$saved){
					return redirect()->back()->with('insert_error','حدث عطل خلال تعديل نوع البند');
				}
				$log=new Log;
				$log->table="store_types";
				$log->action="update";
				$log->record_id=$id;
				$log->user_id=Auth::user()->id;
				$log->description=$description;
				$log->save();
				return redirect()->route('allstoretype')->with('success','تم تعديل نوع البند بنجاح');
			}
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
			$store_type=StoreType::findOrFail($id);
			$deleted=$store_type->delete();
			if(!$deleted){
				return redirect()->route('allstoretype')->with('delete_error','حدث عطل خلال حذف هذا النوع يرجى المحاولة فى وقت لاحق');
			}
			$log=new Log;
			$log->table="store_types";
			$log->action="delete";
			$log->record_id=$id;
			$log->user_id=Auth::user()->id;
			$log->description="قام بحذف نوع البند (".$store_type->name.")";
			$log->save();
			return redirect()->route('allstoretype')->with('success','تم حذف نوع البند ('.$store_type->name.') بنجاح');
		}
		else
			abort('404');
	}

}
