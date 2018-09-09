<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Contractor;
use Validator;
use Auth;
use Hash;

class UserController extends Controller {

	/**
	 * Display a listing of All Users.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		if(Auth::user()->type=='admin'){
			$users=User::all();
			$array=['active'=>'user','users'=>$users];
			return view('user.all',$array);
		}
		else
			abort('404');
	}

	/**
	 * Display a listing of Contractor Users.
	 *
	 * @return Response
	 */
	public function contractor()
	{
		//
		if(Auth::user()->type=='admin')
		{
			$users=User::where('type','contractor')->get();
			$array=['active'=>'user','users'=>$users];
			return view('user.all',$array);
		}
		else
			abort('404');
	}

	/**
	 * Display a listing of Admin Users.
	 *
	 * @return Response
	 */
	public function admin()
	{
		//
		if(Auth::user()->type=='admin')
		{
			$users=User::where('type','admin')->get();
			$array=['active'=>'user','users'=>$users];
			return view('user.all',$array);
		}
		else
			abort('404');
	}

	/**
	 * Show the form for creating a new user.
	 *
	 * @return Response
	 */
	public function create($id=null)
	{
		//
		if(Auth::user()->type=='admin')
		{
			if(isset($id)){
				$contractors=Contractor::where('id',$id)->where('user_id',null)->get();
				$array=['active'=>'user','contractors'=>$contractors,'cont'=>'cont'];
				return view('user.add',$array);
			}
			$contractors=Contractor::where('user_id',null)->get();
			$array=['active'=>'user','contractors'=>$contractors];
			return view('user.add',$array);
		}
		else
			abort('404');
	}

	/**
	 * Store a newly created user in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
		//
		if(Auth::user()->type=='admin')
		{
			//validation rules
			$rules=[
				'username'=>'required|alpha_dash',
				'password'=>'required|min:6',
				'repassword'=>'required|same:password',
				'type'=>'required|in:admin,contractor',
				'contractor_id'=>'required_if:type,contractor|exists:contractors,id'
			];
			//Validation Error Messages
			$error_messages=[
				'username.required'=>'يجب أدخال أسم المستخدم',
				'username.alpha_dash'=>'أسم المستخدم يجب أن يحتوى على أرقام و حروف و _ و - فقط',
				'password.required'=>'يجب أدخال كلمة المرور',
				'password.min'=>'كلمة المرور يجب الا تقل عن 6 حروف',
				'repassword.required'=>'يجب أعادة أدخال كلمة المرور',
				'repassword.same'=>'كلمة المرور يجب أن تكون متطابقة',
				'type.required'=>'يجب أختيار نوع الحساب',
				'type.in'=>'نوع الحساب يجب أن يكون مشرف أو مقاول فقط',
				'contractor_id.required_if'=>'لابد من أختيار المقاول الذى تريد أنشاء حساب له',
				'contractor_id.exists'=>'لابد من وجود هذا المقاول فى قاعدة البيانات'
			];
			//make validation
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$user=new User;
			$user->username=$req->input('username');
			$user->password=bcrypt($req->input('password'));
			$user->type=$req->input('type');
			$saved=$user->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذا الحساب يرجى المحاولة فى وقت لاحق')->withInput();
			}
			if($user->type=='contractor')
			{
				$contractor=Contractor::findOrFail($req->input('contractor_id'));
				$contractor->user_id=$user->id;
				$contractor->save();
			}
			return redirect()->route('alluser')->with('success','تم أضافة الحساب بنجاح');

		}
		else
			abort('404');
	}

	/**
	 * Display the specified user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		if(Auth::user()->type=='admin')
		{
			$user=User::findOrFail($id);
			$array=['active'=>'user','user'=>$user];
			return view('user.show',$array);
		}
		else
		{
			if(Auth::user()->id==$id){
				$user=User::findOrFail($id);
				$array=['active'=>'','user'=>$user];
				return view('user.show',$array);
			}
			else
				abort('404');

		}
	}

	/**
	 * Show the form for editing the specified user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
		if(Auth::user()->id==$id){
			$user=User::findOrFail($id);
			$array=['active'=>'user','user'=>$user];
			return view('user.edit',$array);
		}
		else
			abort('404');
	}

	/**
	 * Update the specified user in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $req,$id)
	{
		//
		if(Auth::user()->id==$id){
			$rules=[
				'oldpassword'=>'required|min:6',
				'password'=>'required|min:6',
				'repassword'=>'required|same:password'
			];
			$error_messages=[
				'oldpassword.required'=>'يجب أدخال كلمة المرور الحالية',
				'oldpassword.min'=>'كلمة المرور الحالية يجب الا تقل عن 6 حروف',
				'password.required'=>'يجب أدخال كلمة المرور الجديدة',
				'password.min'=>'كلمة المرور الجديدة يجب الا تقل عن 6 حروف',
				'repassword.required'=>'يجب أدخال كلمة المرور الجديدة',
				'repassword.same'=>'كلمة المرور يجب أن تكون متطابقة'
			];
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator);
			}

			$user=User::findOrFail($id);
			if(Hash::check($req->input('oldpassword'), $user->password)){
				$user->password=bcrypt($req->input('password'));
				$saved=$user->save();
				if(!$saved){
					return redirect()->back()->with('insert_error','حدث عطل خلال تغيير كلمة المرور يرجى المحاولة فى وقت لاحق');
				}
				return redirect()->route('showuser',$user->id)->with('success','تم تغيير كلمة المرور بنجاح');
			}
			return redirect()->back()->with('insert_error','كلمة المرور القديمة خاطئة');
		}
		else
			abort('404');
	}

	/**
	 * Remove the specified user from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Auth::user()->type=='admin'){
			$user=User::findOrFail($id);
			$deleted=$user->delete();
			if(!$deleted){
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا الحساب يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->back()->with('success','تم حذف الحساب بنجاح');
		}
	}

}
