<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Contractor;
use App\TermType;
use App\Log;

use Validator;
use Auth;

use Carbon\Carbon;

class ContractorController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Auth::user()->type=='admin'){
			$contractors=Contractor::all();
			$array=['active'=>'cont','contractors'=>$contractors];
			return view('contractor.all',$array);
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
		if(Auth::user()->type=='admin'){
			$term_types=TermType::all();
			$array=['active'=>'cont','term_types'=>$term_types];
			return view('contractor.add',$array);
		}else{
			abort('404');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
		if(Auth::user()->type=='admin'){
			//Validation Rules
			$rules=[
				'name'=>'required|regex:/^[\pL\s]+$/u',
				'type'=>'required_without:contractor_type|exists:term_types,name',
				'contractor_type.*'=>"sometimes|required|unique:term_types,name|regex:/^[\pL\s]+$/u",
				'address'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'center'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'phone.*'=>'required|numeric',
			];
			//Error Vaildation Messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم المقاول',
				'name.regex'=>'أسم المقاول يجب أن يتكون من حروف و مسافات فقط',
				'type.required_without'=>'يجب أدخال نوع المقاول',
				'type.exists'=>'أنواع المقاول يجب أن تكون مسجلة بقاعدة البيانات فى أنواع البنود',
				'contractor_type.*.required'=>'يجب أدخال نوع المقاول',
				'contractor_type.*.unique'=>'هذا النوع موجود بالفعل بقاعدة البيانات , من فضلك اختاره من اعلى بدلاً من اضافته',
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
			$contractor=new Contractor;
			$contractor->name=$req->input('name');
			$contractor->address=$req->input('address');
			$contractor->center=$req->input('center');
			$contractor->city=$req->input('city');
			$contractor->phone=implode(",",$req->input('phone'));
			$contractor->type=implode(",",array_merge($req->input("type")??[],$req->input('contractor_type')??[]));
			foreach ($req->input('contractor_type')??[] as $type) {
				$term_type = new TermType;
				$term_type->name=$type;
				$term_type->save();
				$log=new Log;
				$log->table="term_types";
				$log->action="create";
				$log->record_id=$term_type->id;
				$log->user_id=Auth::user()->id;
				$log->description="قام بأضافة نوع بند جديد";
				$log->save();
			}
			$saved=$contractor->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث خطأ خلال أضافة هذا المقاول , يرجى المحاولة فى وقت لاحق');
			}
			$log=new Log;
			$log->table="contractors";
			$log->action="create";
			$log->record_id=$contractor->id;
			$log->user_id=Auth::user()->id;
			$log->description="قام باضافة مقاول جديد";
			$log->save();
			return redirect()->route('showcontractor',$contractor->id)->with('success','تم حفظ المقاول بنجاح');
		}else{
			abort('404');
		}
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
			$contractor=Contractor::findOrFail($id);
			$contracts=$contractor->contracts()
				->where('deleted',0)
				->orderBy('created_at','desc')
				->take(3)
				->get();
			$rate = $contractor->rate();
			$productionNotes= $contractor->productionNotes();
			$productions = $contractor->productions()->where('productions.deleted',0)->orderBy('productions.created_at','desc')->take(3)->get();
			$array=['active'=>'cont','rate'=>$rate,'contractor'=>$contractor,'contracts'=>$contracts,'productionNotes'=>$productionNotes,'productions'=>$productions];

			return view('contractor.show',$array);
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
			$contractor=Contractor::findOrFail($id);
			$contractor_types=explode(",",$contractor->type);
			$term_types=TermType::all();
			$array=[
				'active'=>'cont',
				'contractor'=>$contractor,
				'term_types'=>$term_types,
				'contractor_types'=>$contractor_types
			];
			return view('contractor.edit',$array);
		}else{
			abort('404');
		}
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
			//Validation Rules
			$rules=[
				'name'=>'required|regex:/^[\pL\s]+$/u',
				'type'=>'required_without:contractor_type|exists:term_types,name',
				'contractor_type.*'=>"sometimes|required|unique:term_types,name|regex:/^[\pL\s]+$/u",
				'address'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'center'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'phone.*'=>'required|numeric',
			];
			//Error Vaildation Messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم المقاول',
				'name.regex'=>'أسم المقاول يجب أن يتكون من حروف و مسافات فقط',
				'type.required_without'=>'يجب أدخال نوع المقاول',
				'type.exists'=>'أنواع المقاول يجب أن تكون مسجلة بقاعدة البيانات فى أنواع البنود',
				'contractor_type.*.required'=>'يجب أدخال نوع المقاول',
				'contractor_type.*.unique'=>'هذا النوع موجود بالفعل بقاعدة البيانات , من فضلك اختاره من اعلى بدلاً من اضافته',
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
			$contractor=Contractor::findOrFail($id);

			$check=false;
			$types = implode(",",array_merge($req->input("type")??[],$req->input("contractor_type")??[]));
			$newTypeArray=array_merge($req->input("type")??[],$req->input("contractor_type")??[]);
			$oldTypeArray=explode(",",$contractor->type);
			$phones = implode(",",$req->input("phone"));
			$description="قام بتعديل بيانات المقاول .";

			if ($contractor->name!=$req->input("name")) {
				$check=true;
				$description.="قام بتغيير أسم المقاول من '".$contractor->name."' إلى '".$req->input("name")."' .";
				$contractor->name=$req->input('name');
			}
			if ($contractor->address!=$req->input("address")) {
				$check=true;
				$description.="قام بتغيير عنوان شارع المقاول من '".$contractor->address."' إلى '".$req->input("address")."' .";
				$contractor->address=$req->input('address');
			}
			if ($contractor->center!=$req->input("center")) {
				$check=true;
				$description.="قام بتغيير عنوان مركز المقاول من '".$contractor->center."' إلى '".$req->input("center")."' .";
				$contractor->center=$req->input('center');
			}
			if ($contractor->city!=$req->input("city")) {
				$check=true;
				$description.="قام بتغيير عنوان مدينة المقاول من '".$contractor->city."' إلى '".$req->input("city")."' .";
				$contractor->city=$req->input('city');
			}
			if ($phones != $contractor->phone) {
				$check=true;
				$description.="قام بتغيير أرقام الهاتف من '".$contractor->phone."' إلى '".$phones."' .";
				$contractor->phone=$phones;
			}
			if($contractor->type!=$types){
				foreach ($newTypeArray as $string) {
					if(mb_strpos($contractor->type,$string)===false){
						$check=true;
						break;
					}
				}
				foreach ($oldTypeArray as $string) {
					if(mb_strpos($types,$string)===false){
						$check=true;
						break;
					}
				}
				if ($check) {
					$description.="قام بتغيير نوع المقاول من '".$contractor->type."' إلى '".$types."' .";
					if($req->input("contractor_type")!==null){
						foreach ($req->input('contractor_type')??[] as $type) {
							$term_type = new TermType;
							$term_type->name=$type;
							$term_type->save();
							$log=new Log;
							$log->table="term_type";
							$log->action="create";
							$log->record_id=$term_type->id;
							$log->user_id=Auth::user()->id;
							$log->description="قام بأضافة نوع بند جديد";
							$log->save();
						}
					}
					$contractor->type=$types;
				}
			}
			if ($check) {
				$saved=$contractor->save();
				if(!$saved){
					return redirect()->back()->with('update_error','حدث خطأ خلال تعديل هذا المقاول و يرجى المحاولة فى وقت لاحق');
				}
				$log=new Log;
				$log->table="contractors";
				$log->action="update";
				$log->record_id=$id;
				$log->user_id=Auth::user()->id;
				$log->description=$description;
				$log->save();
				return redirect()->route('showcontractor',$contractor->id)->with('success','تم تعديل المقاول بنجاح');
			}
			return redirect()->back()->with("info","لا يوجد تعديل حتي يتم حفظه");
		}else{
			abort('404');
		}
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
			$contractor=Contractor::findOrFail($id);
			$deleted=$contractor->delete();
			if(!$deleted){
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا المقاول يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('allcontractor')->with('success','تم حذف المقاول بنجاح');
		}else{
			abort('404');
		}
	}

	/**
	 * Get All Contracted Terms
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getAllTerms($id)
	{
		if(Auth::user()->type=='admin')
		{
			$contractor=Contractor::findOrFail($id);
			$terms=$contractor->terms();
			$array=['active'=>'cont','contractor'=>$contractor,'terms'=>$terms];
			return view('contractor.terms',$array);
		}
		else
			abort('404');
	}
	/**
	 * Get All Productions
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getAllProductions($id)
	{
		if(Auth::user()->type=='admin')
		{
			$contractor=Contractor::findOrFail($id);
			$productions=$contractor->productions()->where("productions.deleted",0)->get();
			$avg_rate=$contractor->rate()[0]->rate;
			// dd($avg_rate);
			$array=['active'=>'cont','contractor'=>$contractor,'productions'=>$productions,'avg_rate'=>$avg_rate];
			return view('contractor.all_production',$array);
		}
		else
			abort('404');
	}
}
