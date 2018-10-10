<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Supplier;
use App\StoreType;
use App\Log;
use Carbon\Carbon;
use Validator;
use Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		if(Auth::user()->type=="admin"){
			$suppliers=Supplier::all();
			$array=['active'=>'sup','suppliers'=>$suppliers];
			return view('supplier.all',$array);
		}else
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
		if(Auth::user()->type=="admin"){
			$store_types=StoreType::all();
			$array=['active'=>'sup','store_types'=>$store_types];
			return view('supplier.add',$array);
		}else
			abort('404');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
		//
		if(Auth::user()->type=="admin"){
			//Validation Rules
			$rules=[
				'name'=>'required|regex:/^[\pL\s]+$/u',
				'type'=>'required_without:supplier_type|exists:store_types,name',
				'supplier_type.*'=>"sometimes|required|unique:store_types,name|regex:/^[\pL\s]+$/u",
				'supplier_type_unit.*'=>"sometimes|required|regex:/^[\pL\pN\s]+$/u",
				'address'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'center'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'phone.*'=>'required|numeric',
			];
			//Error Vaildation Messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم المورد',
				'name.regex'=>'أسم المورد يجب أن يتكون من حروف و مسافات فقط',
				'type.required_without'=>'يجب أدخال نوع المورد',
				'type.exists'=>'أنواع المورد يجب أن تكون مسجلة بقاعدة البيانات فى أنواع البنود',
				'supplier_type.*.required'=>'يجب أدخال نوع المورد',
				'supplier_type.*.unique'=>'هذا النوع موجود بالفعل بقاعدة البيانات , من فضلك اختاره من اعلى بدلاً من اضافته',
				'supplier_type.*.regex'=>'نوع المورد يجب أن يتكون من حروف و مسافات فقط',
				'supplier_type_unit.*.required'=>'يجب أدخال الوحدة',
				'supplier_type_unit.*.regex'=>'الوحدة يجب أن يتكون من  حروف و مسافات و ارقام فقط',
				'address.regex'=>'الشارع يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'center.regex'=>'المركز يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'city.required'=>'يجب أدخال المدينة',
				'city.regex'=>'المدينة يجب أن تتكون من حروف و أرقام و مسافات فقط',
				'phone.*.required'=>'يجب أدخال التليفون',
				'phone.*.numeric'=>'التليفون يجب أن يتكون من أرقام فقط'
			];
			//validate the request
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}

			//STORE CONTRACTOR IN DB
			$supplier=new Supplier;
			$supplier->name=$req->input('name');
			$supplier->address=$req->input('address');
			$supplier->center=$req->input('center');
			$supplier->city=$req->input('city');
			$supplier->phone=implode(",",$req->input('phone'));
			$supplier->type=implode(",",array_merge($req->input("type")??[],$req->input('supplier_type')??[]));

			$units=$req->input("supplier_type_unit");
			foreach ($req->input('supplier_type')??[] as $type) {
				$store_type = new StoreType;
				$store_type->name=$type;
				$store_type->unit=array_shift($units);
				$store_type->save();
				$log=new Log;
				$log->table="store_types";
				$log->action="create";
				$log->record_id=$store_type->id;
				$log->user_id=Auth::user()->id;
				$log->description="قام بأضافة نوع خام جديد";
				$log->save();
			}
			$saved=$supplier->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذا المورد, يرجى المحاولة فى وقت لاحق');
			}
			$log=new Log;
			$log->table="suppliers";
			$log->action="create";
			$log->record_id=$supplier->id;
			$log->user_id=Auth::user()->id;
			$log->description="قام باضافة مورد جيد";
			$log->save();
			return redirect()->route('showsupplier',$supplier->id)->with('success','تم أضافة المورد بنجاح');
		}else
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
		//
		if(Auth::user()->type=="admin"){
			$supplier=Supplier::findOrFail($id);
			$stores= $supplier->stores()->where("stores.deleted",0)->orderBy('created_at','desc')->take(3)->get();
			$allRawsReport= DB::select("
					select count(*) as count , sum(amount*value) as value , sum(amount_paid) as paid
					from stores where supplier_id=? and deleted=0
			",[$id]);
			$lastTenRawsReport= DB::select("
					select  sum(st.amount*st.value) as value , sum(st.amount_paid) as paid
					from (select amount , value, amount_paid from stores where supplier_id=? and deleted=0 order by created_at desc limit 10) as st
			",[$id]);
			$raws= DB::select("
					select  sum(amount*value) as value , sum(amount_paid) as paid, type, sum(amount) as amount, unit from stores
					where supplier_id=? and deleted=0
					group by type
			",[$id]);
			$payments = DB::select("
					select p.payment_amount , p.created_at, s.type, projects.name, p.type as payment_type, s.project_id  from payments as p
					join stores as s on p.table_name='stores' and s.id=p.table_id and s.supplier_id=?
					join projects where s.project_id=projects.id
					order by p.created_at desc limit 3
			",[$id]);
			// dd($raws);
			$array=[
				'active'=>'sup',
				'supplier'=>$supplier,
				'stores'=>$stores,
				'allRaws'=>$allRawsReport,
				'lastTenRaws'=>$lastTenRawsReport,
				'raws'=>$raws,
				'payments'=>$payments
			];
			return view('supplier.show',$array);
		}else
			abort('404');
	}

	/**
	 * Show all supplied raws by a supplier.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 public function getAllStores($id)
	 {
		 $supplier = Supplier::findOrFail($id);
		 $stores = $supplier->stores()->where("deleted",0)->orderBy("created_at","desc")->paginate(30);
		 $array=[
			 "active"=>"sup",
			 "supplier"=>$supplier,
			 "stores"=>$stores
		 ];
		 return view("supplier.stores",$array);
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
		if(Auth::user()->type=="admin"){
			$supplier=Supplier::findOrFail($id);
			$store_types=StoreType::all();
			$supplier_types=explode(",", $supplier->type);
			$array=[
				'active'=>'sup',
				'store_types'=>$store_types,
				'supplier'=>$supplier,
				'supplier_types'=>$supplier_types
			];
			return view('supplier.edit',$array);
		}else
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
		//
		if(Auth::user()->type=="admin"){
			//Validation Rules
			$rules=[
				'name'=>'required|regex:/^[\pL\s]+$/u',
				'type'=>'required_without:supplier_type|exists:store_types,name',
				'supplier_type.*'=>"sometimes|required|unique:store_types,name|regex:/^[\pL\s]+$/u",
				'supplier_type_unit.*'=>"sometimes|required|regex:/^[\pL\pN\s]+$/u",
				'address'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'center'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'phone.*'=>'required|numeric',
			];
			//Error Vaildation Messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم المورد',
				'name.regex'=>'أسم المورد يجب أن يتكون من حروف و مسافات فقط',
				'type.required_without'=>'يجب أدخال نوع المورد',
				'type.exists'=>'أنواع المورد يجب أن تكون مسجلة بقاعدة البيانات فى أنواع البنود',
				'supplier_type.*.required'=>'يجب أدخال نوع المورد',
				'supplier_type.*.unique'=>'هذا النوع موجود بالفعل بقاعدة البيانات , من فضلك اختاره من اعلى بدلاً من اضافته',
				'supplier_type.*.regex'=>'نوع المورد يجب أن يتكون من حروف و مسافات فقط',
				'supplier_type_unit.*.required'=>'يجب أدخال الوحدة',
				'supplier_type_unit.*.regex'=>'الوحدة يجب أن يتكون من  حروف و مسافات و ارقام فقط',
				'address.regex'=>'الشارع يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'center.regex'=>'المركز يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'city.required'=>'يجب أدخال المدينة',
				'city.regex'=>'المدينة يجب أن تتكون من حروف و أرقام و مسافات فقط',
				'phone.*.required'=>'يجب أدخال التليفون',
				'phone.*.numeric'=>'التليفون يجب أن يتكون من أرقام فقط'
			];
			//validate the request
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$supplier= Supplier::findOrFail($id);
			$phones=implode(",",$req->input('phone'));
			$types=implode(",",array_merge($req->input("type")??[],$req->input('supplier_type')??[]));
			$newTypesArray=array_merge($req->input("type")??[],$req->input('supplier_type')??[]);
			$oldTypesArray=explode(",",$supplier->type);
			$check = false;
			$description = "قام بتعديل بيانات المورد .";
			if ($phones != $supplier->phone) {
				$check=true;
				$description.="قام بتغيير أرقام الهاتف من '".$supplier->phone."' إلى '".$phones."' .";
				$supplier->phone=$phones;
			}
			if($supplier->type != $types){
				foreach ($newTypesArray as $string) {
					if(mb_strpos($supplier->type,$string)===false){
						$check=true;
						break;
					}
				}
				foreach ($oldTypesArray as $string) {
					if(mb_strpos($types,$string)===false){
						$check=true;
						break;
					}
				}
				if ($check) {
					$description.="قام بتغيير نوع المورد من '".$supplier->type."' إلى '".$types."' .";
					if($req->input("supplier_type")!==null){
						$units=$req->input("supplier_type_unit");
						foreach ($req->input('supplier_type')??[] as $type) {
							$store_type = new StoreType;
							$store_type->name=$type;
							$store_type->unit=array_shift($units);
							$store_type->save();
							$log=new Log;
							$log->table="store_types";
							$log->action="create";
							$log->record_id=$store_type->id;
							$log->user_id=Auth::user()->id;
							$log->description="قام بأضافة نوع خام جديد";
							$log->save();
						}
					}
					$supplier->type=$types;
				}
			}
			if ($supplier->name!=$req->input("name")) {
				$check=true;
				$description.="قام بتغيير أسم المورد من '".$supplier->name."' إلى '".$req->input("name")."' .";
				$supplier->name = $req->input("name");
			}
			if ($supplier->address!=$req->input("address")) {
				$check=true;
				$description.="قام بتغيير عنوان شارع المورد من '".$supplier->address."' إلى '".$req->input("address")."' .";
				$supplier->address = $req->input("address");
			}
			if ($supplier->center!=$req->input("center")) {
				$check=true;
				$description.="قام بتغيير عنوان مركز المورد من '".$supplier->center."' إلى '".$req->input("center")."' .";
				$supplier->center = $req->input("center");
			}
			if ($supplier->city!=$req->input("city")) {
				$check=true;
				$description.="قام بتغيير عنوان مدينة المورد من '".$supplier->city."' إلى '".$req->input("city")."' .";
				$supplier->city = $req->input("city");
			}
			if ($check) {
				$saved=$supplier->save();
				if(!$saved){
					return redirect()->back()->with('update_error','حدث عطل خلال تعديل هذا المورد, يرجى المحاولة فى وقت لاحق');
				}
				$log=new Log;
				$log->table="suppliers";
				$log->action="update";
				$log->record_id=$id;
				$log->user_id=Auth::user()->id;
				$log->description=$description;
				$log->save();
				return redirect()->route('showsupplier',$supplier->id)->with('success','تم تعديل المورد بنجاح');
			}
			return redirect()->back()->with("info","لا يوجد تعديل حتي يتم حفظه");
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
		if(Auth::user()->type=="admin"){
			$supplier=Supplier::findOrFail($id);
			$deleted=$supplier->delete();
			if(!$deleted){
				return redirect()->route('allsupplier')->with('delete_error','حدث عطل خلال حذف هذا المورد يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('allsupplier')->with('success','تم حذف المورد ('.$supplier->name.') بنجاح');
		}else
			abort('404');
	}

}
