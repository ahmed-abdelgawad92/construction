<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Organization;
use App\Log;

use Auth;
use Validator;

class OrganizationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$organizations= Organization::where('deleted',0)->get();
		$array=['organizations'=>$organizations,'active'=>'org'];
		return view('organization.all',$array);
	}

	/**
	 * Display a listing of all clients.
	 *
	 * @return Response
	 */
	public function getClients()
	{
		$organizations= Organization::where('type',0)->where('deleted',0)->get();
		$array=['organizations'=>$organizations,'active'=>'org'];
		return view('organization.all',$array);
	}

	/**
	 * Display a listing of all contract clients.
	 *
	 * @return Response
	 */
	public function getContractClients()
	{
		$organizations= Organization::where('type',1)->where('deleted',0)->get();
		$array=['organizations'=>$organizations,'active'=>'org'];
		return view('organization.all',$array);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('organization.add',['active'=>'org']);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
		//validation rules
		$rules=[
			'name'=>'required|regex:/^[\pL\pN\s]+$/u',
			'address'=>'regex:/^[\pL\pN\s]+$/u',
			'center'=>'regex:/^[\pL\pN\s]+$/u',
			'city'=>'regex:/^[\pL\pN\s]+$/u',
			'phone.*'=>'numeric',
			'type'=>'required|in:0,1'
		];
		//validation error messages
		$errorMessages=[
			'name.required'=>'يجب أدخال أسم العميل',
			'name.regex'=>'أسم العميل يجب أن يتكون من حروف و مسافات و أرقام فقط',
			'address.regex'=>'الشارع لابد أن يتكون من حروف و أرقام فقط',
			'center.regex'=>'المركز لابد أن يتكون من حروف و أرقام فقط',
			'city.regex'=>'المدينة لابد أن تتكون من حروف و أرقام فقط',
			'phone.*.numeric'=>'رقم الهاتف لابد أن يتكون من أرقام فقط',
			'type.required'=>'من فضلك حدد نوع العميل اذا كانت هيئة أو مستخلص',
			'type.in'=>'نرجو عدم تغيير قيمة نوع العميل و أختيار من الختيارات المتاحة فقط'
		];
		//make validation
		$validator=Validator::make($req->all(),$rules,$errorMessages);
		//check if valid
		if($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput();
		}
		//store in db
		$org= new Organization;
		$org->name=$req->input('name');
		$org->address=$req->input('address');
		$org->center=$req->input('center');
		$org->city=$req->input('city');
		$org->phone=implode(',',$req->input('phone'));
		$org->type=$req->input('type');

		$saved=$org->save();
		if(!$saved){
			return redirect()->route('addorganization')->with('insert_error','حدث عطل خلال أضافة هذا العميل يرجى المحاولة فى وقت لاحق')->withInput();
		}
		$log=new Log;
		$log->user_id=Auth::user()->id;
		$log->table="organizations";
		$log->description="هذا المستخدم أضاف عميل جديد";
		$log->record_id=$org->id;
		$log->action="create";
		$log->save();
		return redirect()->route('showorganization',$org->id)->with('success','تم حفظ العميل بنجاح');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		$org= Organization::where('deleted',0)->findOrFail($id);
		$projects=$org->projects;
		$current_projects=$org->projects()->where('done',0)->get();
		$array=['org'=>$org,'projects'=>$projects,'current_projects'=>$current_projects,'active'=>'org'];
		return view('organization.show',$array);
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
		$org=Organization::where('deleted',0)->findOrFail($id);
		$array=['org'=>$org,'active'=>'org'];
		return view('organization.edit',$array);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $req,$id)
	{
		$org=Organization::where('deleted',0)->findOrFail($id);
		//validation rules
		$rules=[
			'name'=>'regex:/^[\pL\s]+$/u',
			'address'=>'regex:/^[\pL\pN\s]+$/u',
			'center'=>'regex:/^[\pL\pN\s]+$/u',
			'city'=>'regex:/^[\pL\pN\s]+$/u',
			'phone.*'=>'numeric',
			'type'=>'in:0,1'
		];
		//validation error messages
		$errorMessages=[
			'name.regex'=>'أسم العميل يجب أن يتكون من حروف و مسافات فقط',
			'address.regex'=>'الشارع لابد أن يتكون من حروف و أرقام فقط',
			'center.regex'=>'المركز لابد أن يتكون من حروف و أرقام فقط',
			'city.regex'=>'المدينة لابد أن تتكون من حروف و أرقام فقط',
			'phone.*.numeric'=>'رقم الهاتف لابد أن يتكون من أرقام فقط',
			'type.in'=>'نرجو عدم تغيير قيمة نوع العميل و أختيار من الختيارات المتاحة فقط'
		];
		//make validation
		$validator=Validator::make($req->all(),$rules,$errorMessages);
		//check if valid
		if($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput();
		}
		$check=true;
		$description="هذا المستخدم قام بتعديل بيانات العميل ,";
		//update in db
		if ($org->name!=$req->input('name')) {
			$description.=" تغيير الاسم من  ".$org->name." إلى ".$req->input('name')." . ";
			$org->name=$req->input('name');
			$check=false;
		}
		if ($org->address!=$req->input('address')) {
			$description.="تغيير العنوان من ".$org->address." إلى ".$req->input('address')." . ";
			$org->address=$req->input('address');
			$check=false;
		}
		if ($org->center!=$req->input('center')) {
			$description.="تغيير المركز من  ".$org->center." إلى ".$req->input('center')." . ";
			$org->center=$req->input('center');
			$check=false;
		}
		if ($org->city!=$req->input('city')) {
			$description.="تغيير المدينة من ".$org->city." إلى ".$req->input('city')." . ";
			$org->city=$req->input('city');
			$check=false;
		}
		if ($org->phone!=implode(",",$req->input('phone'))) {
			$description.="تغيير ارقام التليفون من '".$org->phone."' إلى '".implode(",",$req->input('phone'))."' . ";
			$org->phone=implode(",",$req->input('phone'));
			$check=false;
		}
		if ($org->type!=$req->input('type')) {
			if($org->type==0){
				$description.="تغيير نوع العميل من عميل إلى مقاول .";
			}else{
				$description.="تغيير نوع العميل من مقاول إلى عميل .";
			}
			$org->type=$req->input('type');
			$check=false;
		}

		if($check){
			return redirect()->route('updateorganization')->with('warning','لا يوجد اى تعديل جديد');
		}
		$saved=$org->save();
		if(!$saved){
			return redirect()->route('updateorganization')->with('update_error','حدث عطل خلال تعديل هذا العميل يرجى المحاولة فى وقت لاحق')->withInput();
		}
		$log=new Log;
		$log->user_id=Auth::user()->id;
		$log->table="organizations";
		$log->description=$description;
		$log->record_id=$org->id;
		$log->action="update";
		$log->save();
		return redirect()->route('showorganization',$id)->with('success','تم تعديل العميل بنجاح');

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
		$org=Organization::where('deleted',0)->findOrFail($id);
		$org->deleted=1;
		$deleted=$org->save();
		if(!$deleted){
			return redirect()->route('allorganization')->with('delete_error','حدث عطل خلال حذف هذا العميل يرجى المحاولة فى وقت لاحق');
		}
		$log= new Log;
		$log->table="organizations";
		$log->action="delete";
		$log->description="قام بحذف هذا العميل";
		$log->record_id=$id;
		$log->user_id=Auth::user()->id;
		$log->save();
		return redirect()->route('allorganization')->with('success','تم حذف العميل بنجاح');
	}

}
