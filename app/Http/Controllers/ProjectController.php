<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Organization;
use App\Project;
use App\Log;

use Auth;
use Validator;
use Carbon\Carbon;

class ProjectController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		if(Auth::user()->type=='admin'){
			$projects=Project::all();
			$array=['active'=>'project','projects'=>$projects];
			return view('project.all',$array);
		}else
			abort('404');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function allStarted()
	{
		if(Auth::user()->type=='admin'){
			$projects=Project::where('started_at','<=',Carbon::today())->where('done',0)->where('started_at','!=','null')->get();
			$array=['active'=>'project','projects'=>$projects];
			return view('project.all',$array);
		}else
			abort('404');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function allDone()
	{
		if(Auth::user()->type=='admin'){
			$projects=Project::where('done',1)->get();
			$array=['active'=>'project','projects'=>$projects];
			return view('project.all',$array);
		}else
			abort('404');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function allNotStarted()
	{
		if(Auth::user()->type=='admin'){
			$projects=Project::where('started_at','>',Carbon::today())->where('started_at','!=','null')->get();
			$array=['active'=>'project','projects'=>$projects];
			return view('project.all',$array);
		}else
			abort('404');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id=null)
	{
		if(Auth::user()->type=='admin'){
			if(isset($id)){
				$orgs[]=Organization::findOrFail($id);
				$array=['orgs'=>$orgs,'active'=>'project'];
				return view('project.add',$array);
			}
			$orgs=Organization::all();
			$array=['orgs'=>$orgs,'active'=>'project'];
			return view('project.add',$array);
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
		if(Auth::user()->type=='admin'){
			//validation rules
			$rules=[
				'organization_id'=>'required|exists:organizations,id',
				'name'=>'required|regex:/^[\pL\pN\s]+$/u',
				'def_num'=>'regex:/^[\pL\pN]+$/u',
				'address'=>'regex:/^[\pL\pN\s]+$/u',
				'village'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'center'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'city'=>'regex:/^[\pL\pN\s]+$/u',
				'extra_data'=>'nullable|regex:/^[\pL\pN\s\(\)]+$/u',
				'model_used'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'implementing_period'=>'numeric',
				'floor_num'=>'regex:/^[\pL\pN\s\+]+$/u',
				'approximate_price'=>'regex:/^[0-9]*\.?[0-9]+$/',
				'started_at'=>'date',
				'loan'=>'nullable|numeric',
				'cash_box'=>'nullable|numeric',
				'loan_interest_rate'=>'nullable|required_with:loan|numeric|max:100|min:0',
				'bank'=>'nullable|required_with:loan|regex:/^[\pL\pN\s]+$/u'
			];
			//validation messages
			$error_messages=[
				'organization_id.required'=>'يجب أدخال أسم الهيئة التابع لها هذا المشروع',
				'organization_id.exists'=>'يجب أختيلر هيئة محفوظة بالنظام',
				'name.required'=>'يجب أدخال أسم المشروع',
				'name.regex'=>'أسم المشروع يجب أن يتكون من حروف أو أرقام أو مسافات فقط',
				'def_num.regex'=>'الرقم التعريفى يجب أن يتكون فقط من أرقام و حروف',
				'address.regex'=>'الشارع لابد أن يتكون من حروف و أرقام فقط',
				'village.regex'=>'القرية لابد أن يتكون من حروف و أرقام فقط',
				'center.regex'=>'المركز لابد أن يتكون من حروف و أرقام فقط',
				'city.regex'=>'المدينة لابد أن يتكون من حروف و أرقام فقط',
				'extra_data.regex'=>'البيانات الأضافية يجب أن تتكون من حروف و أرقام و () فقط',
				'model_used.regex'=>'النموذج المستخدم يجب أن يكون أرقام و حروف فقط',
				'implementing_period.numeric'=>'مدة التنفيذ يجب أن تتكون من أرقام فقط',
				'floor_num.regex'=>'عدد الأدوار لابد أن يتكون من حروف و أرقام و + فقط',
				'approximate_price.regex'=>'السعر التقريبى يجب أن يكون أرقام فقط سواء كسور أو أرقام صحيحة',
				'started_at.date'=>'تاريخ استلام الموقع يجب أن يكون تاريخ صحيح',
				'loan.numeric'=>'قيمة القرض يجب ان تتكون من أرقام فقط',
				'cash_box.numeric'=>'قيمة الصندوق يجب ان تتكون من أرقام فقط',
				'loan_interest_rate.numeric'=>'قيمة نسبة الفائدة يجب ان تتكون من أرقام فقط',
				'loan_interest_rate.required_with'=>'لابد من إدخال نسبة الفائدة في حالة وجود قرض',
				'bank.regex'=>'أسم البنك يجب أن يتكون من حروف و أرقام فقط',
				'bank.required_with'=>'لابد من إدخال أسم البنك في حالة وجود قرض'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
			{
				return redirect()->back()->withErrors($validator)->withInput();
			}
			//store in db
			$project=new Project;
			$project->name=$req->input('name');
			$project->def_num=$req->input('def_num');
			$project->organization_id=$req->input('organization_id');
			$project->address=$req->input('address');
			$project->village=$req->input('village');
			$project->center=$req->input('center');
			$project->city=$req->input('city');
			$project->extra_data=$req->input('extra_data');
			$project->implementing_period=$req->input('implementing_period');
			$project->floor_num=$req->input('floor_num');
			$project->approximate_price=$req->input('approximate_price');
			if(!empty($req->input('started_at')))
				$project->started_at=$req->input('started_at');
			$project->model_used=$req->input('model_used');
			$project->cash_box=$req->input('cash_box');
			$project->loan=$req->input('loan');
			$project->loan_interest_rate=$req->input('loan_interest_rate');
			$project->bank=$req->input('bank');

			$saved=$project->save();
			//check if stored
			if(!$saved){
				return redirect()->route('addproject')->with('insert_error','حدث عطل خلال أضافة هذا المشروع يرجى المحاولة فى وقت لاحق')->withInput();
			}

			$log= new Log;
			$log->table="projects";
			$log->record_id=$project->id;
			$log->action="create";
			$log->description="هذا المستخدم أضاف مشروع جديد";
			$log->user_id= Auth::user()->id;
			$log->save();

			return redirect()->route('nonorg')->with([
				'success'=>'تم حفظ المشروع بنجاح',
				'project_id'=>$project->id
				]);
		}else
			abort('404');
	}

	/**
	 * Check is the NonOrgCost Exists.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function checkNonOrgCost()
	{
		if(Auth::user()->type=='admin'){
			//Check Organization Type which non-organization payment depend on
			if(session('project_id')){

				$project=Project::findOrFail(session('project_id'));
				$org=$project->organization;
				if($org->type=="1"){
					return view('project.add_non_cost',
						['success'=>session('success'),'active'=>'project','project_id'=>session('project_id')]);
				}
				return redirect()->route('showproject',session('project_id'))->with('success','تم حفظ المشروع بنجاح');
			}
			else{
				abort('404');
			}
		}else
			abort('404');
	}

	/**
	 * Save the NonOrgCost
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function addNonOrgCost(Request $req,$id)
	{
		if(Auth::user()->type=='admin'){
			$rules=[
				'non_organization_payment'=>[
					'required',
					'regex:/(^100(\.0{1,2})?$)|(^([1-9]([0-9])?|0)(\.[0-9]{1,2})?$)/'
				]
			];

			$error_messages=[
				'non_organization_payment.required'=>'من فضلك أدخل نسبة المقاول',
				'non_organization_payment.regex'=>'قيمة النسبة يجب أن تكون من 0.01 ألى 100.00'
			];

			$validator=Validator::make($req->all(),$rules,$error_messages);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput()->with('project_id',$id);
			}
			$project= Project::findOrFail($id);
			$project->non_organization_payment=$req->input('non_organization_payment');
			$saved=$project->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة نسبة المقاول بالمشروع يرجى المحاولة فى وقت لاحق')->withInput();
			}
			return redirect()->route('showproject',$project->id)->with('success','تم أضافة نسبة المقاول بنجاح');
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
		if(Auth::user()->type=='admin'){
			//
			$project=Project::findOrFail($id);
			$org=$project->organization;
			$startedTerms=$project->terms()
				->where('started_at','<=',Carbon::today())
				->where('done',0)
				->where('disabled',0)
				->orderBy('started_at','asc')
				->take(3)
				->get();
			$notStartedTerms=$project->terms()
				->where('done',0)
				->where(function($query){
					$query->where('started_at','>',Carbon::today())
						->orWhere('started_at',null);
				})
				->orderBy('started_at','asc')
				->take(3)
				->get();
			$doneTerms=$project->terms()
				->where('done',1)
				->orderBy('started_at','desc')
				->take(3)
				->get();
			$disabledTerms=$project->terms()
				->where('done',0)
				->where('disabled',1)
				->orderBy('started_at','desc')
				->take(3)
				->get();
			$employees = $project->employees;
			$productions= $project->productionDetails();
			$productionReport =$project->productionReport();
			$suppliers= $project->supplierDetails();
			$stores = $project->stockReport();
			$array=[
				'active'=>'project',
				'project'=>$project,
				'org'=>$org,
				'productions'=>$productions,
				'productionReport'=>$productionReport[0]??null,
				'stores'=>$stores,
				'startedTerms'=>$startedTerms,
				'notStartedTerms'=>$notStartedTerms,
				'disabledTerms'=>$disabledTerms,
				'doneTerms'=>$doneTerms,
				'suppliers'=>$suppliers,
				'employees'=>$employees
			];
			return view('project.show',$array);
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
		if(Auth::user()->type=='admin'){
			//
			$project=Project::findOrFail($id);
			$array=['project'=>$project,'active'=>'project'];
			return view('project.edit',$array);
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
		if(Auth::user()->type=='admin'){
			//validation rules
			$rules=[
				'organization_id'=>'required|exists:organizations,id',
				'name'=>'required|regex:/^[\pL\pN\s]+$/u',
				'def_num'=>'regex:/^[\pL\pN]+$/u',
				'address'=>'regex:/^[\pL\pN\s]+$/u',
				'village'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'center'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'city'=>'regex:/^[\pL\pN\s]+$/u',
				'extra_data'=>'nullable|regex:/^[\pL\pN\s\(\)]+$/u',
				'model_used'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'implementing_period'=>'numeric',
				'floor_num'=>'regex:/^[\pL\pN\s\+]+$/u',
				'approximate_price'=>'regex:/^[0-9]*\.?[0-9]+$/',
				'started_at'=>'date'
			];
			//validation messages
			$error_messages=[
				'organization_id.required'=>'يجب أدخال أسم الهيئة التابع لها هذا المشروع',
				'organization_id.exists'=>'يجب أختيلر هيئة محفوظة بالنظام',
				'name.required'=>'يجب أدخال أسم المشروع',
				'name.regex'=>'أسم المشروع يجب أن يتكون من حروف أو أرقام أو مسافات فقط',
				'def_num.regex'=>'الرقم التعريفى يجب أن يتكون فقط من أرقام و حروف',
				'address.regex'=>'الشارع لابد أن يتكون من حروف و أرقام فقط',
				'village.regex'=>'القرية لابد أن يتكون من حروف و أرقام فقط',
				'center.regex'=>'المركز لابد أن يتكون من حروف و أرقام فقط',
				'city.regex'=>'المدينة لابد أن يتكون من حروف و أرقام فقط',
				'extra_data.regex'=>'البيانات الأضافية يجب أن تتكون من حروف و أرقام و () فقط',
				'model_used.regex'=>'النموذج المستخدم يجب أن يكون أرقام و حروف فقط',
				'implementing_period.numeric'=>'مدة التنفيذ يجب أن تتكون من أرقام فقط',
				'floor_num.regex'=>'عدد الأدوار لابد أن يتكون من حروف و أرقام و + فقط',
				'approximate_price.regex'=>'السعر التقريبى يجب أن يكون أرقام فقط سواء كسور أو أرقام صحيحة',
				'started_at.date'=>'تاريخ استلام الموقع يجب أن يكون تاريخ صحيح'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
			{
				return redirect()->back()->withErrors($validator)->withInput();
			}
			//store in db
			$project=Project::findOrFail($id);
			$check=true;
			$description="هذا المستخدم قام بتعديل بيانات المشروع ,";
			if ($project->name!=$req->input('name')) {
				$description.=" تغيير الأسم من ".$project->name." إلى ".$req->input("name")." .";
				$project->name=$req->input('name');
				$check=false;
			}
			if ($project->def_num!=$req->input('def_num')) {
				$description.=" تغيير الرقم التعريفي من ".$project->def_num." إلى ".$req->input('def_num')." .";
				$project->def_num=$req->input('def_num');
				$check=false;
			}
			if ($project->def_num!=$req->input('def_num')) {
				$description.=" تغيير الرقم التعريفي من ".$project->def_num." إلى ".$req->input('def_num')." .";
				$project->def_num=$req->input('def_num');
				$check=false;
			}
			if ($project->organization_id!=$req->input('organization_id')) {
				$description.=" تغيير العميل من  ".$project->organization_id." إلى ".$req->input('organization_id')." .";
				$project->organization_id=$req->input('organization_id');
				$check=false;
			}
			if ($project->address!=$req->input('address')) {
				$description.=" تغيير الشارع من ".$project->address." إلى ".$req->input('address')." .";
				$project->address=$req->input('address');
				$check=false;
			}
			if ($project->village!=$req->input('village')) {
				$description.=" تغيير القرية من ".$project->village." إلى ".$req->input('village')." .";
				$project->village=$req->input('village');
				$check=false;
			}
			if ($project->center!=$req->input('center')) {
				$description.=" تغيير المركز من ".$project->center." إلى ".$req->input('center')." .";
				$project->center=$req->input('center');
				$check=false;
			}
			if ($project->city!=$req->input('city')) {
				$description.=" تغيير المدينة من ".$project->city." إلى ".$req->input('city')." .";
				$project->city=$req->input('city');
				$check=false;
			}
			if ($project->extra_data!=$req->input('extra_data')) {
				$description.=" تغيير البيانات الإضافية من ".$project->extra_data." إلى ".$req->input('extra_data')." .";
				$project->extra_data=$req->input('extra_data');
				$check=false;
			}
			if ($project->implementing_period!=$req->input('implementing_period')) {
				$description.=" تغيير مدة التنفيذ من ".$project->implementing_period." إلى ".$req->input('implementing_period')." .";
				$project->implementing_period=$req->input('implementing_period');
				$check=false;
			}
			if ($project->floor_num!=$req->input('floor_num')) {
				$description.=" تغيير عدد الادوار من ".$project->floor_num." إلى ".$req->input('floor_num')." .";
				$project->floor_num=$req->input('floor_num');
				$check=false;
			}
			if ($project->approximate_price!=$req->input('approximate_price')) {
				$description.=" تغيير السعر التقريبى من ".$project->approximate_price." إلى ".$req->input('approximate_price')." .";
				$project->approximate_price=$req->input('approximate_price');
				$check=false;
			}
			if ($project->started_at!=$req->input('started_at')) {
				$description.=" تغيير تاريخ بدء المشروع من ".$project->started_at." إلى ".$req->input('started_at')." .";
				$project->started_at=$req->input('started_at');
				$check=false;
			}
			if ($project->model_used!=$req->input('model_used')) {
				$description.=" تغيير النموذج المستخدم من ".$project->model_used." إلى ".$req->input('model_used')." .";
				$project->model_used=$req->input('model_used');
				$check=false;
			}
			if ($project->non_organization_payment!=$req->input('non_organization_payment')) {
				$description.=" تغيير نسبةالمقاول من ".$project->non_organization_payment." إلى ".$req->input('non_organization_payment')." .";
				$project->non_organization_payment=$req->input('non_organization_payment');
				$check=false;
			}

			if (!$check) {
				$saved=$project->save();
				//check if stored
				if(!$saved){
					return redirect()->back()->with('update_error','حدث عطل خلال تعديل هذا المشروع يرجى المحاولة فى وقت لاحق');
				}
				$log = new Log;
				$log->table="projects";
				$log->record_id=$id;
				$log->description=$description;
				$log->user_id= Auth::user()->id;
				$log->action="update";
				$log->save();
				return redirect()->route('showproject',$project->id)->with([
					'success'=>'تم تعديل المشروع بنجاح'
				]);
			}
			return redirect()->route('showproject',$project->id)->with([
				'warning'=>'لا يوجد تعديل لحفظه '
			]);

		}else
			abort('404');
	}


	/**
     *
	 *  START PROJECT
	 *  @param int $id
	 *  @return response
	 */
	public function startProject($id)
	{
		if(Auth::user()->type=='admin'){
			$project=Project::findOrFail($id);
			$project->started_at=Carbon::today();
			$saved=$project->save();
			if(!$saved){
				return redirect()->back()->with('update_error','حدث عطل خلال تعديل هذا المشروع يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('showproject',$id)->with([
				'success'=>'تم بدأ المشروع و أستلام الموقع بنجاح'
				]);
		}else
			abort('404');
	}


	/**
     *
	 *  Add Cash Box to A project
	 *  @param int $id
	 *  @return response
	 */

	 public function addCashBox(Request $req ,$id)
	 {
		 if (Auth::user()->type=="admin") {
			 $rules=[
				 'cash_box'=>'required|numeric'
			 ];
			 $error_messages=[
				 'cash_box:required'=>'لابد من ادخال قيمة الصندوق ',
				 'cash_box:numeric'=>'قيمة الصندوق يجب ان تتكون من ارقام فقط'
			 ];
			 $validator= Validator::make($req->all(),$rules,$error_messages);
			 if($validator->fails()){
				 return redirect()->back()->withInput()->withErrors($validator);
			 }
			 $check=true;
			 $description="";
			 $project= Project::findOrFail($id);
			 if ($project->cash_box!=$req->input('cash_box')) {
				 $description = "قام بتغيير قيمة الصندوق من ". ($project->cash_box?:"لا شئ") . "إلى" .$req->input('cash_box');
				 $project->cash_box=$req->input('cash_box');
				 $check=false;
			 }
			 if (!$check) {
				 $saved=$project->save();
				 if(!$saved){
					 return redirect()->back()->with(['error'=>'حدث عطل خلال اضافة الصندوق']);
				 }
				 $log=new Log;
				 $log->table="projects";
				 $log->record_id=$id;
				 $log->action="update";
				 $log->description=$description;
				 $log->user_id= Auth::user()->id;
				 $log->save();
				 return redirect()->back()->with(['success'=>'تم حفظ قيمة الصندوق بنجاح']);
			 }

			 return redirect()->back()->with(['warning'=>'لم يحدث تعديل حتى يتم حفظه']);
		 }else{
			 abort('404');
		 }
	 }
	/**
     *
	 *  Add Loan to a Project
	 *  @param int $id
	 *  @return response
	 */

	 public function addLoan(Request $req ,$id)
	 {
		 if (Auth::user()->type=="admin") {
			 $rules=[
				 'loan'=>'required|numeric',
				 'loan_interest_rate'=>'numeric|min:0|max:100',
				 'bank'=>'string'
			 ];
			 $error_messages=[
				 'loan:required'=>'لابد من إدخال قيمة القرض',
				 'loan:numeric'=>'يجب ان تتكون من ارقام فقط',
				 'loan_interest_rate:numeric'=>'يجب ان تتكون من ارقام فقط',
				 'loan_interest_rate:min'=>'اقل قيمة هي الصفر',
				 'loan_interest_rate:max'=>'اعلى قيمة هى المائة',
				 'bank:string'=>'يجب ادخال اسم البنك'
			 ];
			 $validator= Validator::make($req->all(),$rules,$error_messages);
			 if($validator->fails()){
				 return redirect()->back()->withInput()->withErrors($validator);
			 }
			 $check=true;
			 $description="";
			 $project= Project::findOrFail($id);
			 if ($project->loan!=$req->input('loan')) {
				 $description.="قام بتغيير قيمة القرض من ";
				 $description.= $project->loan?:' لا شئ ';
				 $description.='إلى '.$req->input('loan')." . ";
				 $project->loan=$req->input('loan');
				 $check=false;
			 }
			 if ($project->loan_interest_rate!=$req->input('loan_interest_rate')) {
				 $description.="قام بتغيير نسبة الفائدة من ";
				 $description.= $project->loan_interest_rate?:' لا شئ ';
				 $description.='إلى '.$req->input('loan_interest_rate')." % . ";
				 $project->loan_interest_rate=$req->input('loan_interest_rate');
				 $check=false;
			 }
			 if ($project->bank!=$req->input('bank')) {
				 $description.="قام بتغيير اسم البنك من ";
				 $description.= $project->bank?:' لا شئ ';
				 $description.='إلى '.$req->input('bank')." . ";
				 $project->bank=$req->input('bank');
				 $check=false;
			 }
			 if (!$check) {
				 $saved=$project->save();
				 if(!$saved){
					 return redirect()->back()->with(['error'=>'حدث عطل خلال اضافة القرض']);
				 }
				 $log=new Log;
				 $log->table="projects";
				 $log->record_id=$id;
				 $log->action="update";
				 $log->description=$description;
				 $log->user_id= Auth::user()->id;
				 $log->save();
				 return redirect()->back()->with(['success'=>'تم حفظ قيمة القرض']);
			 }

			 return redirect()->back()->with(['warning'=>'لم يحدث تعديل حتى يتم حفظه']);
		 }else{
			 abort('404');
		 }
	 }

	/**
     *
	 *  END PROJECT
	 *  @param int $id
	 *  @return response
	 */
	public function endProject($id)
	{
		if(Auth::user()->type=='admin'){
			$project=Project::findOrFail($id);
			$project->done=1;
			$saved=$project->save();
			if(!$saved){
				return redirect()->back()->with('update_error','حدث عطل خلال تعديل هذا المشروع يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('showproject',$id)->with([
				'success'=>'تم أنهاء المشروع و تسليمه بنجاح'
				]);
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
		if(Auth::user()->type=='admin'){
			$project=Project::findOrFail($id);
			$deleted=$project->delete();
			if(!$deleted){
				return redirect()->route('allproject')->with('delete_error','حدث عطل خلال حذف هذا المشروع يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('allproject')->with('success','تم حذف المشروع بنجاح');
		}else
			abort('404');
	}

}
