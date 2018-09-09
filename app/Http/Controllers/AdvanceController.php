<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Advance;
use App\Employee;
use App\CompanyEmployee;

use Carbon\Carbon;
use Validator;
use Auth;

class AdvanceController extends Controller {

	/**
	 * Display a listing of Advances within the company
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Auth::user()->type=='admin')
		{
			$advances=Advance::where('active',0)->get();
			$total_advance_unpaid=Advance::where('active',0)->sum('advance');
			$total_advance=Advance::sum('advance');
			$array=[
				'active'=>'adv',
				'advances'=>$advances,
				'total_advance_unpaid'=>$total_advance_unpaid,
				'total_advance'=>$total_advance
			];
			return view('advance.all',$array);
		}
		else
			abort('404');
	}

	/**
	 * Show the form for creating a new advance.
	 *
	 * @return Response
	 */
	public function create($id=null)
	{
		if(Auth::user()->type=='admin')
		{
			if($id!=null){
				$employee=Employee::findOrFail($id);
				$array=[
					'active'=>'adv',
					'employee'=>$employee
				];
				return view('advance.add',$array);
			}
			$employees=Employee::all();
			$array=[
				'active'=>'adv',
				'employees'=>$employees
			];
			return view('advance.add',$array);
		}
		else
			abort('404');
	}

	/**
	 * Store a newly created advance in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
		if(Auth::user()->type=='admin')
		{
			//validationn rules
			$rules=[
				'employee_id'=>'required|exists:employees,id',
				'advance'=>'required|numeric'
			];
			//error_messages
			$error_messages=[
				'employee_id.required'=>'يجب أختيار الموظف الذى أخذ السلفة',
				'employee_id.exists'=>'الموظف يجب أن يكون موجود بقاعدة البيانات',
				'advance.required'=>'يجب أدخال قيمة السلفة',
				'advance.numeric'=>'قيمة السلفة يجب أن تتكون من أرقام فقط'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();
			//save in db
			$advance=new Advance;
			$advance->employee_id=$req->input('employee_id');
			$advance->advance=$req->input('advance');
			$saved=$advance->save();
			if(!$saved)
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذه السلفة, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showadvance',$req->input('employee_id'))->with('success','تم أضافة السلفة بنجاح');
		}
		else
			abort('404');
	}

	/**
	 * Show the form for creating a new advance.
	 *
	 * @return Response
	 */
	public function createCompany($id=null)
	{
		if(Auth::user()->type=='admin')
		{
			if($id!=null){
				$employee=CompanyEmployee::findOrFail($id);
				$array=[
					'active'=>'adv',
					'employee'=>$employee
				];
				return view('advance.add',$array);
			}
			$employees=CompanyEmployee::all();
			$array=[
				'active'=>'adv',
				'employees'=>$employees
			];
			return view('advance.add',$array);

		}
		else
			abort('404');
	}

	/**
	 * Store a newly created advance in storage.
	 *
	 * @return Response
	 */
	public function storeCompany(Request $req)
	{
		if(Auth::user()->type=='admin')
		{
			//validationn rules
			$rules=[
				'employee_id'=>'required|exists:company_employees,id',
				'advance'=>'required|numeric'
			];
			//error_messages
			$error_messages=[
				'employee_id.required'=>'يجب أختيار الموظف الذى أخذ السلفة',
				'employee_id.exists'=>'الموظف يجب أن يكون موجود بقاعدة البيانات',
				'advance.required'=>'يجب أدخال قيمة السلفة',
				'advance.numeric'=>'قيمة السلفة يجب أن تتكون من أرقام فقط'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();
			//save in db
			$advance=new Advance;
			$advance->company_employee_id=$req->input('employee_id');
			$advance->advance=$req->input('advance');
			$saved=$advance->save();
			if(!$saved)
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذه السلفة, يرجى المحاولة فى وقت لاحق');
			return redirect()->route('showcompanyadvance',$req->input('employee_id'))->with('success','تم أضافة السلفة بنجاح');
		}
		else
			abort('404');
	}

	/**
	 * Display the specified advance.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Auth::user()->type=='admin')
		{
			$employee=Employee::findOrFail($id);
			$advances=$employee->advances;
			$total_advance=$employee->advances()->sum('advance');
			$total_advance_unpaid=$employee->advances()->where('active',0)->sum('advance');
			$array=[
				'employee'=>$employee,
				'advances'=>$advances,
				'total_advance'=>$total_advance,
				'total_advance_unpaid'=>$total_advance_unpaid,
				'active'=>'adv'
			];
			return view('advance.show',$array);
		}
		else
			abort('404');
	}

	/**
	 * Display the specified advance.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function showCompany($id)
	{
		if(Auth::user()->type=='admin')
		{
			$employee=CompanyEmployee::findOrFail($id);
			$advances=$employee->advances;
			$total_advance=$employee->advances()->sum('advance');
			$total_advance_unpaid=$employee->advances()->where('active',0)->sum('advance');
			$array=[
				'employee'=>$employee,
				'advances'=>$advances,
				'total_advance'=>$total_advance,
				'total_advance_unpaid'=>$total_advance_unpaid,
				'active'=>'adv'
			];
			return view('advance.show',$array);
		}
		else
			abort('404');
	}

	/**
	 * Repay the advance.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function repay($id)
	{
		if(Auth::user()->type=='admin')
		{
			$advance=Advance::findOrFail($id);
			$advance->active=1;
			$advance->payment_at=Carbon::now();
			$saved=$advance->save();
			if(!$saved)
				return redirect()->back()->with('update_error','حدث عطل خلال رد قيمة السلفة, يرجى المحاولة فى وقت لاحق');
			return redirect()->back()->with('success','تم رد قيمة السلفة للشركة بنجاح');
		}
		else
			abort('404');
	}

	/**
	 * Show the form for editing the specified advance.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if(Auth::user()->type=='admin')
		{
			$advance=Advance::findOrFail($id);
			$array=['active'=>'adv','advance'=>$advance];
			return view('advance.edit',$array);
		}
		else
			abort('404');
	}

	/**
	 * Update the specified advance in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $req,$id)
	{
		if(Auth::user()->type=='admin')
		{
			//validationn rules
			$rules=[
				'advance'=>'required|numeric'
			];
			//error_messages
			$error_messages=[
				'advance.required'=>'يجب أدخال قيمة السلفة',
				'advance.numeric'=>'قيمة السلفة يجب أن تتكون من أرقام فقط'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();
			//save in db
			$advance=Advance::findOrFail($id);
			$advance->advance=$req->input('advance');
			$saved=$advance->save();
			if(!$saved)
				return redirect()->back()->with('update_error','حدث عطل خلال تعديل هذه السلفة, يرجى المحاولة فى وقت لاحق');
			return redirect()->back()->with('success','تم تعديل السلفة بنجاح');
		}
		else
			abort('404');
	}

	/**
	 * Remove the specified advance from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Auth::user()->type=='admin')
		{
			$advance=Advance::findOrFail($id);
			$deleted=$advance->delete();
			if(!$deleted)
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذه السلفة, يرجى المحاولة فى وقت لاحق');
			return redirect()->back()->with('success','تم حذف السلفة بنجاح');
		}
		else
			abort('404');
	}

}
