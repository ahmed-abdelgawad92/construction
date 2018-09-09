<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Organization;
use App\Project;

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
				$orgs=Organization::findOrFail($id);
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
				'organization_id'=>'required|exists:organizations,id|',
				'name'=>'required|regex:/^[\pL\pN\s]+$/u',
				'def_num'=>'numeric',
				'address'=>'regex:/^[\pL\pN\s]+$/u',
				'village'=>'regex:/^[\pL\pN\s]+$/u',
				'center'=>'regex:/^[\pL\pN\s]+$/u',
				'city'=>'regex:/^[\pL\pN\s]+$/u',
				'extra_data'=>'regex:/^[\pL\pN\s\(\)]+$/u',
				'model_used'=>'alpha_num',
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
				'def_num.numeric'=>'الرقم التعريفى يجب أن يتكون فقط من أرقام',
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

			$saved=$project->save();
			//check if stored
			if(!$saved){
				return redirect()->route('addproject')->with('insert_error','حدث عطل خلال أضافة هذا المشروع يرجى المحاولة فى وقت لاحق')->withInput();
			}

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
			$array=[
				'active'=>'project',
				'project'=>$project,
				'org'=>$org,
				'startedTerms'=>$startedTerms,
				'notStartedTerms'=>$notStartedTerms,
				'doneTerms'=>$doneTerms
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
				'organization_id'=>'required|exists:organizations,id|',
				'name'=>'required|regex:/^[\pL\pN\s]+$/u',
				'def_num'=>'numeric',
				'address'=>'regex:/^[\pL\pN\s]+$/u',
				'village'=>'regex:/^[\pL\pN\s]+$/u',
				'center'=>'regex:/^[\pL\pN\s]+$/u',
				'city'=>'regex:/^[\pL\pN\s]+$/u',
				'extra_data'=>'regex:/^[\pL\pN\s\(\)]+$/u',
				'model_used'=>'alpha_num',
				'implementing_period'=>'numeric',
				'floor_num'=>'regex:/^[\pL\pN\s\+]+$/u',
				'approximate_price'=>'regex:/^[0-9]*\.?[0-9]+$/',
				'started_at'=>'date',
				'regex:/(^100(\.0{1,2})?$)|(^([1-9]([0-9])?|0)(\.[0-9]{1,2})?$)/'
			];
			//validation messages
			$error_messages=[
				'organization_id.required'=>'يجب أدخال أسم الهيئة التابع لها هذا المشروع',
				'organization_id.exists'=>'يجب أختيلر هيئة محفوظة بالنظام',
				'name.required'=>'يجب أدخال أسم المشروع',
				'name.regex'=>'أسم الهيئة يجب أن يتكون من حروف أو أرقام أو مسافات فقط',
				'def_num.numeric'=>'الرقم التعريفى يجب أن يتكون فقط من أرقام',
				'address.regex'=>'الشارع لابد أن يتكون من حروف و أرقام فقط',
				'village.regex'=>'القرية لابد أن يتكون من حروف و أرقام فقط',
				'center.regex'=>'المركز لابد أن يتكون من حروف و أرقام فقط',
				'city.regex'=>'المدينة لابد أن يتكون من حروف و أرقام فقط',
				'extra_data.regex'=>'البيانلت الأضافية يجب أن تتكون من حروف و أرقام و () فقط',
				'model_used.regex'=>'النموذج المستخدم يجب أن يكون أرقام و حروف فقط',
				'implementing_period.numeric'=>'مدة التنفيذ يجب أن تتكون من أرقام فقط',
				'floor_num.regex'=>'عدد الأدوار لابد أن يتكون من حروف و أرقام و + فقط',
				'approximate_price.regex'=>'السعر التقريبى يجب أن يكون أرقام فقط سواء كسور أو أرقام صحيحة',
				'started_at.date'=>'تاريخ استلام الموقع يجب أن يكون تاريخ صحيح',
				'non_organization_payment.regex'=>'قيمة النسبة يجب أن تكون من 0.01 ألى 100.00'
			];
			//validate
			$validator=Validator::make($req->all(),$rules);
			if($validator->fails())
			{
				return redirect()->back()->withErrors($validator)->withInput();
			}
			//store in db
			$project=Project::findOrFail($id);
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
			$project->started_at=$req->input('started_at');
			$project->model_used=$req->input('model_used');
			$project->non_organization_payment=$req->input('non_organization_payment');

			$saved=$project->save();
			//check if stored
			if(!$saved){
				return redirect()->back()->with('update_error','حدث عطل خلال تعديل هذا المشروع يرجى المحاولة فى وقت لاحق');
			}

			return redirect()->route('showproject',$project->id)->with([
				'success'=>'تم تعديل المشروع بنجاح'
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
