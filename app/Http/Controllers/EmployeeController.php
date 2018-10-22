<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\CompanyEmployee;
use App\Employee;
use App\Project;
use App\Log;

use Auth;
use Validator;
use Carbon\Carbon;

class EmployeeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id=null)
	{
		if(Auth::user()->type=='admin'){
			$today=Carbon::today();
			$tomorrow=Carbon::tomorrow();
			$in2Days=Carbon::tomorrow()->addDay();
			if($id!=null){
				$project=Project::findOrFail($id);
				$employees=$project->employees;
				$array=[
					'active'=>'emp',
					'employees'=>$employees,
					'project'=>$project,
					'today'=>$today,
					'tomorrow'=>$tomorrow,
					'in2Days'=>$in2Days
				];
				return view('employee.all',$array);
			}
			$employees=Employee::all();
			$array=[
				'active'=>'emp',
				'employees'=>$employees,
				'today'=>$today,
				'tomorrow'=>$tomorrow,
				'in2Days'=>$in2Days
			];
			return view('employee.all',$array);
		}
		else
			abort('404');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function company()
	{
		if(Auth::user()->type=='admin'){
			$employees=CompanyEmployee::all();
			$today=Carbon::today();
			$tomorrow=Carbon::tomorrow();
			$in2Days=$tomorrow->addDay();
			$array=[
				'active'=>'emp',
				'employees'=>$employees,
				'today'=>$today,
				'tomorrow'=>$tomorrow,
				'in2Days'=>$in2Days
			];
			return view('employee.all',$array);
		}
		else
			abort('404');
	}

	//choose project
	public function chooseProject()
	{
		if(Auth::user()->type=='admin'){
			$projects=Project::where('done',0)->get();
			$array=['active'=>'emp','projects'=>$projects];
			return view('production.allprojects',$array);
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
			$projects=Project::where('done',0)->get();
			$array=['active'=>'emp','projects'=>$projects];
			return view('employee.add',$array);
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
		if(Auth::user()->type=='admin'){
			//validation rules
			$rules=[
				'name'=>'required|regex:/^[\pL\pN\s]+$/u',
				'job'=>'required|regex:/^[\pL\pN\s]+$/u',
				'type'=>'required|in:1,2',
				'phone.*'=>'required|numeric',
				'address'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'village'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'assign_job'=>'nullable|required_if:type,2|in:0,1',
				'project_id'=>'nullable|required_if:assign_job,1|exists:projects,id',
				'salary'=>'nullable|required_if:assign_job,1|numeric',
				'salary_company'=>'nullable|required_if:type,1|numeric'
			];
			//error_messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم الموظف',
				'name.regex'=>'أسم الموظف يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'job.required'=>'يجب أدخال السمى الوظيفى',
				'job.regex'=>'المسمى الوظيفى يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'type.required'=>'يحب أختيار نوع الموظف',
				'phone.*.required'=>'يجب أدخال رقم الهاتف',
				'phone.*.numeric'=>'رقم الهاتف يجب أن يكون أرقام فقط',
				'address.regex'=>'الشارع يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'village.regex'=>'القرية يجب أن تتكون من حروف و أرقام و مسافت فقط',
				'city.required'=>'يجب أدخال المدينة',
				'city.regex'=>'المدينة يجب أن تتكون من حروف و أرقام و مسافت فقط',
				'assign_job.required_if'=>'هل تريد توظيفه ألأن؟',
				'project_id.required_if'=>'يجب أختيار المشروع الذى تود توظيفه فيه',
				'project_id.exists'=>'المشروع يجب أن يكون موجود بقاعدة البيانات',
				'salary.required_if'=>'يجب أدخال الراتب',
				'salary.numeric'=>'الراتب يجب أن يتكون من أرقام فقط',
				'salary_company.required_if'=>'يجب أدخال الراتب',
				'salary_company.numeric'=>'الراتب يجب أن يتكون من أرقام فقط'
			];
			//validator
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			//save to db
			try {
				if($req->input('type')==2){
					DB::beginTransaction();
					$employee=new Employee;
					$employee->name=$req->input('name');
					$employee->job=$req->input('job');
					$employee->phone=implode(",",$req->input('phone'));
					$employee->address=$req->input('address');
					$employee->village=$req->input('village');
					$employee->city=$req->input('city');
					//check if it should be assigned to a project now
					if($req->input('assign_job')==1){
						$pivotAttr=[
							'salary'=>$req->input('salary')
						];
						$project=Project::findOrFail($req->input('project_id'));
						$project->employees()->save($employee,$pivotAttr);
						//create Log
						$log=new Log;
						$log->table="employees";
						$log->action="create";
						$log->record_id=$employee->id;
						$log->user_id=Auth::user()->id;
						$log->description="قام بتعيين الموظف المنتدب : ".$employee->name." بمشروع ".$project->name." فى مدينة ".$project->city;
						$log->save();
					}else{
						$employee->save();
					}
					//create Log
					$log=new Log;
					$log->table="employees";
					$log->action="create";
					$log->record_id=$employee->id;
					$log->user_id=Auth::user()->id;
					$log->description="قام بأضافة موظف جديد  الى الشركة";
					$log->save();
				}elseif($req->input('type')==1){
					$employee=new CompanyEmployee;
					$employee->name=$req->input('name');
					$employee->job=$req->input('job');
					$employee->phone=implode(",",$req->input('phone'));
					$employee->address=$req->input('address');
					$employee->village=$req->input('village');
					$employee->city=$req->input('city');
					$employee->salary=$req->input('salary_company');
					$employee->save();
					//create Log
					$log=new Log;
					$log->table="company_employees";
					$log->action="create";
					$log->record_id=$employee->id;
					$log->user_id=Auth::user()->id;
					$log->description="قام بأضافة موظف جديد  الى الشركة";
					$log->save();
				}
				DB::commit();
			} catch (\Exception $e) {
				DB::rollBack();
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذا الموظف, يرجى المحاولة فى وقت لاحق');
			}
			if ($req->input("type")==2) {
				if($req->input("assign_job")==1){
					return redirect()->route('showemployee',$employee->id)->with('success','تم أضافة الموظف بنجاح, و تم أيضاً توظيفه بنجاح');
				}else{
					return redirect()->route('showemployee',$employee->id)->with('success','تم أضافة الموظف بنجاح');
				}
			}else{
				return redirect()->route('showcompanyemployee',$employee->id)->with('success','تم أضافة الموظف بنجاح');
			}

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
			$employee=Employee::findOrFail($id);
			$advances=$employee->advances()->take(3)->get();
			$projects=$employee->projects()->orderBy('created_at','desc')->take(3)->get();
			$array=[
				'active'=>'emp',
				'employee'=>$employee,
				'projects'=>$projects,
				'advances'=>$advances
			];
			return view('employee.show',$array);
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
	public function showCompanyEmployee($id)
	{
		if(Auth::user()->type=='admin'){
			$employee=CompanyEmployee::findOrFail($id);
			$advances=$employee->advances()->take(3)->get();
			$array=['active'=>'emp','employee'=>$employee,'advances'=>$advances];
			return view('employee.show',$array);
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
			$employee=Employee::findOrFail($id);
			$array=['active'=>'emp','employee'=>$employee];
			return view('employee.edit',$array);
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
				'name'=>'required|regex:/^[\pL\pN\s]+$/u',
				'job'=>'required|regex:/^[\pL\pN\s]+$/u',
				'phone'=>'required|numeric',
				'address'=>'regex:/^[\pL\pN\s]+$/u',
				'village'=>'regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u'
			];
			//error_messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم الموظف',
				'name.regex'=>'أسم الموظف يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'job.required'=>'يجب أدخال السمى الوظيفى',
				'job.regex'=>'المسمى الوظيفى يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'phone.required'=>'يجب أدخال رقم الهاتف',
				'phone.numeric'=>'رقم الهاتف يجب أن يكون أرقام فقط',
				'address.regex'=>'الشارع يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'village.regex'=>'القرية يجب أن تتكون من حروف و أرقام و مسافت فقط',
				'city.required'=>'يجب أدخال المدينة',
				'city.regex'=>'المدينة يجب أن تتكون من حروف و أرقام و مسافت فقط'
			];
			//validator
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();
			//save to db
			$employee=Employee::findOrFail($id);
			$employee->name=$req->input('name');
			$employee->job=$req->input('job');
			$employee->phone=$req->input('phone');
			$employee->address=$req->input('address');
			$employee->village=$req->input('village');
			$employee->city=$req->input('city');
			$saved=$employee->save();
			if(!$saved)
				return redirect()->back()->with('insert_error','حدث عطل خلال تعديل هذا الموظف, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showemployee',$employee->id)->with('success','تم تعديل الموظف بنجاح');
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
	public function editCompany($id)
	{
		if(Auth::user()->type=='admin'){
			$employee=CompanyEmployee::findOrFail($id);
			$array=['active'=>'emp','employee'=>$employee];
			return view('employee.edit',$array);
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
	public function updateCompany(Request $req,$id)
	{
		if(Auth::user()->type=='admin'){
			//validation rules
			$rules=[
				'name'=>'required|regex:/^[\pL\pN\s]+$/u',
				'job'=>'required|regex:/^[\pL\pN\s]+$/u',
				'phone'=>'required|numeric',
				'address'=>'regex:/^[\pL\pN\s]+$/u',
				'village'=>'regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'salary'=>'required|numeric'
			];
			//error_messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم الموظف',
				'name.regex'=>'أسم الموظف يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'job.required'=>'يجب أدخال السمى الوظيفى',
				'job.regex'=>'المسمى الوظيفى يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'phone.required'=>'يجب أدخال رقم الهاتف',
				'phone.numeric'=>'رقم الهاتف يجب أن يكون أرقام فقط',
				'address.regex'=>'الشارع يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'village.regex'=>'القرية يجب أن تتكون من حروف و أرقام و مسافت فقط',
				'city.required'=>'يجب أدخال المدينة',
				'city.regex'=>'المدينة يجب أن تتكون من حروف و أرقام و مسافت فقط',
				'salary.numeric'=>'الراتب يجب أن يتكون من أرقام فقط',
				'salary.required'=>'يجب أدخال الراتب'
			];
			//validator
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();
			//save to db
			$employee=CompanyEmployee::findOrFail($id);
			$employee->name=$req->input('name');
			$employee->job=$req->input('job');
			$employee->phone=$req->input('phone');
			$employee->address=$req->input('address');
			$employee->village=$req->input('village');
			$employee->city=$req->input('city');
			$employee->salary=$req->input('salary');
			$saved=$employee->save();
			if(!$saved)
				return redirect()->back()->with('insert_error','حدث عطل خلال تعديل هذا الموظف, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showcompanyemployee',$employee->id)->with('success','تم تعديل الموظف بنجاح');
		}
		else
			abort('404');
	}

	/**
	 * Show the form for assigning a new job
	 *
	 * @return Response
	 */
	public function getAssignJob($id)
	{
		if(Auth::user()->type=='admin')
		{
			$employee=Employee::findOrFail($id);
			$projects=Project::where('done',0)->get();
			$array=['active'=>'emp','projects'=>$projects,'employee'=>$employee];
			return view('employee.assignJob',$array);
		}
		else
			abort('404');
	}

	/**
	 * post a new job for the employee
	 *
	 * @return Response
	 */
	public function assignJob(Request $req,$id)
	{
		if(Auth::user()->type=='admin')
		{
			//validation rules
			$rules=[
				'project_id'=>'required|exists:projects,id',
				'salary'=>'required|numeric'
			];
			//error_messages
			$error_messages=[
				'project_id.required'=>'يجب أختيار المشروع الذى تود توظيفه فيه',
				'project_id.exists'=>'المشروع يجب أن يكون موجود بقاعدة البيانات',
				'salary.required'=>'يجب أدخال الراتب',
				'salary.numeric'=>'الراتب يجب أن يتكون من أرقام فقط'
			];
			//validator
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();

			$employee=Employee::findOrFail($id);
			$array=['salary'=>$req->input('salary')];
			$saved=$employee->projects()->attach($req->input('project_id'),$array);
			return redirect()->route('showemployee',$employee->id)->with('success','تم تعيين الموظف بنجاح');
		}
		else
			abort('404');
	}

	//end job
	public function endJob($eid,$pid)
	{
		if(Auth::user()->type=='admin')
		{
			$employee=Employee::findOrFail($eid);
			$array=['done'=>1,'ended_at'=>Carbon::today()];
			$saved=$employee->projects()->updateExistingPivot($pid,$array);
			if(!$saved)
				return redirect()->back()->with('update_error','حدث عطل خلال إنهاء وظيفة الموظف $employee->name , يرجى المحاولة فى وقت لاحق');
			return redirect()->back()->with('success','تم إنهاء وظيفة الموظف '.$employee->name.' بنجاح');
		}
		else
			abort('404');
	}

	//update salary
	public function updateSalary(Request $req,$eid,$pid)
	{
		if(Auth::user()->type=='admin')
		{
			$employee=Employee::findOrFail($eid);
			$rule=['salary'=>'required|numeric'];
			$error_message=[
				'salary.required'=>'يجب أدخال الراتب',
				'salary.numeric'=>'الراتب يجب أن يتكون من أرقام فقط'
			];
			$validator=Validator::make($req->all(),$rule,$error_message);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();

			$array=['salary'=>$req->input('salary')];
			$saved=$employee->projects()->updateExistingPivot($pid,$array);
			if(!$saved)
				return redirect()->back()->with('update_error','حدث عطل خلال تعديل الراتب, يرجى المحاولة فى وقت لاحق');
			return redirect()->back()->with('success','تم تعديل الراتب بنجاح');
		}
		else
			abort('404');
	}


	//show all projects of an employee
	public function allProjects($id)
	{
		if(Auth::user()->type=='admin')
		{
			$employee=Employee::findOrFail($id);
			$array=['active'=>'emp','employee'=>$employee];
			return view('employee.allprojects',$array);
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
			$employee=Employee::findOrFail($id);
			$employee->projects()->detach();
			$deleted=$employee->delete();
			if(!$deleted)
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا الموظف, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('allemployee')->with('success','تم حذف الموظف بنجاح');
		}
		else
			abort('404');
	}

	public function destroyCompany($id)
	{
		if(Auth::user()->type=='admin'){
			$employee=CompanyEmployee::findOrFail($id);
			$deleted=$employee->delete();
			if(!$deleted)
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا الموظف, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('allcompanyemployee')->with('success','تم حذف الموظف بنجاح');
		}
		else
			abort('404');
	}

}
