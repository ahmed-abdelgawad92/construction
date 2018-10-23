<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\CompanyEmployee;
use App\Employee;
use App\EmployeeProject;
use App\Project;
use App\Log;

use Auth;
use Validator;
use Carbon\Carbon;

class EmployeeController extends Controller {

	/**
	 * Display a listing of all assigned Employees
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
			$employees=Employee::with('projects')->get();
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
	 * Display a listing of all company employees
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

	//choose project to get all assigned employees
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
	 * Show the form for creating a new employee.
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
	 * Display the specified assigned Employee.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Auth::user()->type=='admin'){
			$employee=Employee::findOrFail($id);
			$advances=$employee->advances()->take(3)->get();
			$projects=$employee->projects()->orderBy('employee_project.created_at','desc')->take(3)->get();
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
	 * Display the specified Company Employee.
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
	 * Show the form for editing the specified assigned Employee.
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
	 * Update the specified assigned Employee in storage.
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
				'phone.*'=>'required|numeric',
				'address'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'village'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u'
			];
			//error_messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم الموظف',
				'name.regex'=>'أسم الموظف يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'job.required'=>'يجب أدخال السمى الوظيفى',
				'job.regex'=>'المسمى الوظيفى يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'phone.*.required'=>'يجب أدخال رقم الهاتف',
				'phone.*.numeric'=>'رقم الهاتف يجب أن يكون أرقام فقط',
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
			$check=false;
			$description="قام بتعديل بيانات الموظف المنتدب ".$employee->name." . ";
			if($employee->name!=$req->input('name')){
				$check=true;
				$description.='قام بتغيير الأسم من ('.$employee->name.') إلى ('.$req->input("name").') . ';
				$employee->name=$req->input('name');
			}
			if($employee->job!=$req->input('job')){
				$check=true;
				$description.='قام بتغيير المسمى الوظيفى من ('.$employee->job.') إلى ('.$req->input("job").') . ';
				$employee->job=$req->input('job');
			}
			if($employee->phone!=implode(',',$req->input('phone'))){
				$check=true;
				$description.='قام بتغيير أرقام الهاتف من ('.$employee->phone.') إلى ('.implode(',',$req->input("phone")).') . ';
				$employee->phone=$req->input('phone');
			}
			if($employee->address!=$req->input('address')){
				$check=true;
				$description.='قام بتغيير عنوان الشارع من ('.$employee->address.') إلى ('.$req->input("address").') . ';
				$employee->address=$req->input('address');
			}
			if($employee->village!=$req->input('village')){
				$check=true;
				$description.='قام بتغيير عنوان القرية من ('.$employee->village.') إلى ('.$req->input("village").') . ';
				$employee->village=$req->input('village');
			}
			if($employee->city!=$req->input('city')){
				$check=true;
				$description.='قام بتغيير عنوان المدينة من ('.$employee->city.') إلى ('.$req->input("city").') . ';
				$employee->city=$req->input('city');
			}
			if($check){
				$saved=$employee->save();
				if(!$saved){
					return redirect()->back()->with('insert_error','حدث عطل خلال تعديل هذا الموظف, يرجى المحاولة فى وقت لاحق');
				}
				$log=new Log;
				$log->table="employees";
				$log->action="update";
				$log->record_id=$employee->id;
				$log->user_id=Auth::user()->id;
				$log->description=$description;
				$log->save();
				return redirect()->route('showemployee',$employee->id)->with('success','تم تعديل الموظف بنجاح');
			}
			return redirect()->back()->with("info","لا يوجد تعديل حتى يتم حفظه");
		}
		else
			abort('404');
	}

	/**
	 * Show the form for editing the specified Company Employee.
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
	 * Update the specified resource in Company Employee.
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
				'phone.*'=>'required|numeric',
				'address'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'village'=>'nullable|regex:/^[\pL\pN\s]+$/u',
				'city'=>'required|regex:/^[\pL\pN\s]+$/u',
				'salary'=>'required|numeric'
			];
			//error_messages
			$error_messages=[
				'name.required'=>'يجب أدخال أسم الموظف',
				'name.regex'=>'أسم الموظف يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'job.required'=>'يجب أدخال السمى الوظيفى',
				'job.regex'=>'المسمى الوظيفى يجب أن يتكون من حروف و أرقام و مسافت فقط',
				'phone.*.required'=>'يجب أدخال رقم الهاتف',
				'phone.*.numeric'=>'رقم الهاتف يجب أن يكون أرقام فقط',
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
			$check=false;
			$description="";
			$employee=Employee::findOrFail($id);
			if($employee->name!=$req->input('name')){
				$check=true;
				$description.='قام بتغيير الأسم من ('.$employee->name.') إلى ('.$req->input("name").') . ';
				$employee->name=$req->input('name');
			}
			if($employee->job!=$req->input('job')){
				$check=true;
				$description.='قام بتغيير المسمى الوظيفى من ('.$employee->job.') إلى ('.$req->input("job").') . ';
				$employee->job=$req->input('job');
			}
			if($employee->phone!=implode(',',$req->input('phone'))){
				$check=true;
				$description.='قام بتغيير أرقام الهاتف من ('.$employee->phone.') إلى ('.implode(',',$req->input("phone")).') . ';
				$employee->phone=$req->input('phone');
			}
			if($employee->address!=$req->input('address')){
				$check=true;
				$description.='قام بتغيير عنوان الشارع من ('.$employee->address.') إلى ('.$req->input("address").') . ';
				$employee->address=$req->input('address');
			}
			if($employee->village!=$req->input('village')){
				$check=true;
				$description.='قام بتغيير عنوان القرية من ('.$employee->village.') إلى ('.$req->input("village").') . ';
				$employee->village=$req->input('village');
			}
			if($employee->city!=$req->input('city')){
				$check=true;
				$description.='قام بتغيير عنوان المدينة من ('.$employee->city.') إلى ('.$req->input("city").') . ';
				$employee->city=$req->input('city');
			}
			if($employee->salary!=$req->input('salary')){
				$check=true;
				$description.='قام بتغيير الراتب من ('.$employee->salary.' جنيه) إلى ('.$req->input("salary").' جنيه) . ';
				$employee->salary=$req->input('salary');
			}
			if($check){
				$saved=$employee->save();
				if(!$saved){
					return redirect()->back()->with('insert_error','حدث عطل خلال تعديل هذا الموظف, يرجى المحاولة فى وقت لاحق');
				}
				$log=new Log;
				$log->table="company_employees";
				$log->action="update";
				$log->record_id=$employee->id;
				$log->user_id=Auth::user()->id;
				$log->description=$description;
				$log->save();
				return redirect()->route('showemployee',$employee->id)->with('success','تم تعديل الموظف بنجاح');
			}
			return redirect()->back()->with("info","لا يوجد تعديل حتى يتم حفظه");
		}
		else
			abort('404');
	}

	/**
	 * Show the form for assigning a new job for Assigned Employees
	 *
	 * @return Response
	 */
	public function getAssignJob($id)
	{
		if(Auth::user()->type=='admin')
		{
			$employee=Employee::findOrFail($id);
			if($employee->countCurrentProjects()>0){
				return redirect()->back()->with("info","هذا الموظف يعمل بالفعل فى مشروع, لا يمكن توظيف اى موظف منتدب بأكثر من مشروع فى نفس الوقت , تستطيع أنهاء عمله بالمشروع الأخر حتى تستطيع تعيينه.");
			}
			$projects=Project::where('done',0)->get();
			$array=['active'=>'emp','projects'=>$projects,'employee'=>$employee];
			return view('employee.assignJob',$array);
		}
		else
			abort('404');
	}

	/**
	 * post a new job for the assigned employee
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
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$employee=Employee::findOrFail($id);
			if($employee->countCurrentProjects()>0){
				return redirect()->back()->with("info","هذا الموظف يعمل بالفعل فى مشروع, لا يمكن توظيف اى موظف منتدب بأكثر من مشروع , تستطيع أنهاء عمله بالمشروع الأخر حتى تستطيع تعيينه.");
			}
			$array=['salary'=>$req->input('salary')];
			try {
				$employee->projects()->attach($req->input('project_id'),$array);
			} catch (\Exception $e) {
				return redirect()->back()->with("insert_error","حدث عطل خلال توظيف الموظف من فضلك حاول المحاولة مرة أخرى");
			}
			$project = Project::where('id',$req->input("project_id"))->first();
			$log=new Log;
			$log->table="employees";
			$log->action="create";
			$log->record_id=$employee->id;
			$log->user_id=Auth::user()->id;
			$log->description="قام بتعيين الموظف المنتدب : ".$employee->name." بمشروع ".$project->name." فى مدينة ".$project->city;
			$log->save();
			return redirect()->route('showemployee',$employee->id)->with('success','تم تعيين الموظف بنجاح');
		}
		else
			abort('404');
	}

	//end job of an assigned Employee
	public function endJob($id)
	{
		if(Auth::user()->type=='admin')
		{
			$employee=EmployeeProject::findOrFail($id);
			$employee->done=1;
			$employee->ended_at=Carbon::today();
			$saved=$employee->save();
			if(!$saved){
				return redirect()->back()->with('update_error','حدث عطل خلال إنهاء وظيفة الموظف '.$employee->employee->name.' , يرجى المحاولة فى وقت لاحق');
			}
			$log=new Log;
			$log->table="employees";
			$log->action="update";
			$log->record_id=$employee->employee->id;
			$log->user_id=Auth::user()->id;
			$log->description="قام بأنهاء الوظيفة يوم ".date('d/m/Y',strtotime($employee->ended_at));
			$log->save();
			return redirect()->back()->with('success','تم إنهاء وظيفة الموظف '.$employee->project->name.' بنجاح');
		}
		else
			abort('404');
	}

	//update salary of the assigned employee
	public function updateSalary(Request $req,$id)
	{
		if(Auth::user()->type=='admin')
		{
			$employee=EmployeeProject::findOrFail($id);
			$rule=['salary'=>'required|numeric'];
			$error_message=[
				'salary.required'=>'يجب أدخال الراتب',
				'salary.numeric'=>'الراتب يجب أن يتكون من أرقام فقط'
			];
			$validator=Validator::make($req->all(),$rule,$error_message);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			if ($employee->salary!=$req->input('salary')) {
				$log=new Log;
				$log->table="employees";
				$log->action="create";
				$log->record_id=$id;
				$log->user_id=Auth::user()->id;
				$log->description='قام بتغيير الراتب من ('.$employee->salary.') إلى ('.$req->input("salary").') .';
				$employee->salary=$req->input('salary');
				$saved=$employee->save();
				if(!$saved){
					return redirect()->back()->with('update_error','حدث عطل خلال تعديل الراتب, يرجى المحاولة فى وقت لاحق');
				}
				$log->save();
				return redirect()->back()->with('success','تم تعديل الراتب بنجاح');
			}
			return redirect()->back()->with('info','لا يوجد تعديل حتى يتم تعديله');
		}
		else
			abort('404');
	}


	//show all projects of an assigned employee
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
	 * Remove the specified assigned employee from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Auth::user()->type=='admin'){
			$employee=Employee::findOrFail($id);
			try {
				DB::beginTransaction();
				$employee->projects()->detach();
				$advances=$employee->advances;
				foreach ($advances as $advance) {
					$advance->delete();
				}
				$employee->delete();
				DB::commit();
			} catch (\Exception $e) {
				DB::rollBack();
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا الموظف, يرجى المحاولة فى وقت لاحق');
			}
			$log=new Log;
			$log->table="employees";
			$log->action="delete";
			$log->record_id=$id;
			$log->user_id=Auth::user()->id;
			$log->description="قام بحذف الموظف المنتدب (".$employee->name.") و بالتالى جميع المعلومات الخاصة به بالمشاريع السابقة وجميع السلفات التى قام بها";
			$log->save();
			return redirect()->route('allemployee')->with('success','تم حذف الموظف بنجاح');
		}
		else
			abort('404');
	}

	/**
	 * Remove the specified company employee from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroyCompany($id)
	{
		if(Auth::user()->type=='admin'){
			$employee=CompanyEmployee::findOrFail($id);
			try {
				DB::beginTransaction();
				$advances=$employee->advances;
				foreach ($advances as $advance) {
					$advance->delete();
				}
				$employee->delete();
				DB::commit();
			} catch (\Excepion $e) {
				DB::rollBack();
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا الموظف, يرجى المحاولة فى وقت لاحق');
			}
			$log=new Log;
			$log->table="company_employees";
			$log->action="delete";
			$log->record_id=$id;
			$log->user_id=Auth::user()->id;
			$log->description="قام بحذف الموظف المنتدب (".$employee->name.") و بالتالى جميع المعلومات الخاصة به وجميع السلفات التى قام بها";
			$log->save();
			return redirect()->route('allcompanyemployee')->with('success','تم حذف الموظف بنجاح');
		}
		else
			abort('404');
	}

}
