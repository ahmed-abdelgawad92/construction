<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
		$users=User::where('deleted',0)->orderBy('name')->get();
		$array=['active'=>'user','users'=>$users];
		return view('user.all',$array);
	}

	/**
	 * Show the form for creating a new user.
	 *
	 * @return Response
	 */
	public function create($id=null)
	{
		//
		if(Auth::user()->privilege== 3)
		{
			$array=['active'=>'user'];
			return view('user.add',$array);
		}
		else{
			return view('errors.privilege',['msg'=>'ليس من صلاحياتك ان تضيف مستخدم جديد']);
		}
	}

	/**
	 * Store a newly created user in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
		//
		if(Auth::user()->privilege== 3)
		{
			//validation rules
			$rules=[
				'name'=>'required',
				'username'=>'required|alpha_dash|min:3',
				'password'=>'required|min:6',
				'repassword'=>'required|same:password',
				'privilege'=>'required|in:1,2,3'
			];
			//Validation Error Messages
			$error_messages=[
				'name.required'=>'يجب ادخال الاسم بالكامل',
				'username.required'=>'يجب أدخال أسم المستخدم',
				'username.alpha_dash'=>'أسم المستخدم يجب أن يحتوى على أرقام و حروف و _ و - فقط',
				'username.min'=>'أسم المستخدم يجب أن يتكون علي الاقل من ٣ حروف',
				'password.required'=>'يجب أدخال كلمة المرور',
				'password.min'=>'كلمة المرور يجب الا تقل عن 6 حروف',
				'repassword.required'=>'يجب أعادة أدخال كلمة المرور',
				'repassword.same'=>'كلمة المرور يجب أن تكون متطابقة',
				'privilege.required'=>'يجب أختيار نوع الحساب',
				'privilege.in'=>'نوع الحساب يجب أن يكون admin, organizer, or user'
			];
			//make validation
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$user=new User;
			$user->name=$req->input('name');
			$user->username=$req->input('username');
			$user->password=bcrypt($req->input('password'));
			$user->type="admin";
			$user->privilege=$req->input("privilege");
			$saved=$user->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذا الحساب يرجى المحاولة فى وقت لاحق')->withInput();
			}
			$log=new Log;
			$log->table="users";
			$log->action="create";
			$log->record_id=$user->id;
			$log->user_id=Auth::user()->id;
			$log->description="قام بأضافة حساب لمستخدم جديد ".$user->name.' من نوع '.$user->getType();
			$log->save();
			return redirect()->route('alluser')->with('success','تم أضافة الحساب بنجاح');
		}
		else{
			return view('errors.privilege',['msg'=>'ليس من صلاحياتك ان تضيف مستخدم جديد']);
		}
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
		if(Auth::user()->privilege >= 2 || Auth::user()->id == $id)
		{
			$user=User::findOrFail($id);
			$logs = $user->logs()->orderBy('created_at','desc')->take(5)->get();
			$array=['active'=>'user','user'=>$user, 'logs'=>$logs];
			return view('user.show',$array);
		}
		else
		{
			return view('errors.privilege',['msg'=>'ليس من صلاحياتك ان تطلع على بيانات المستخدم']);
		}
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
				'name'=>'required'
			];
			$error_messages=[
				'name.required'=>'يجب أدخال الأسم بالكامل'
			];
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails()){
				return redirect()->back()->withErrors($validator);
			}
			$user=User::findOrFail($id);
			if ($req->input("name")== $user->name) {
				return redirect()->back()->with("info","لا يوجد تغيير حتى يتم تعديله");
			}
			$description = "قام بتغيير الأسم بالكامل لنفسه من ".$user->name." الى ".$req->input("name");
			$user->name = $req->input("name");
			$saved=$user->save();
			if(!$saved){
				return redirect()->back()->with('insert_error','حدث عطل خلال تغيير الأسم, يرجى المحاولة فى وقت لاحق');
			}
			$log=new Log;
			$log->table="users";
			$log->action="update";
			$log->record_id=$user->id;
			$log->user_id=Auth::user()->id;
			$log->description=$description;
			$log->save();
			return redirect()->route('showuser',$user->id)->with('success','تم تغيير الأسم بالكامل بنجاح');
		}
		else{
			abort('404');
		}
	}


	/**
	 * Show the form for editing the specified user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function editPassword($id)
	{
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
	public function updatePassword(Request $req,$id)
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
				$log=new Log;
				$log->table="users";
				$log->action="update";
				$log->record_id=Auth::user()->id;
				$log->user_id=Auth::user()->id;
				$log->description="";
				$log->save();
				return redirect()->route('showuser',$user->id)->with('success','تم تغيير كلمة المرور بنجاح');
			}
			return redirect()->back()->with('insert_error','كلمة المرور القديمة خاطئة');
		}
		else
			abort('404');
	}


	/**
	 * Enable the specified user in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 public function enable($id)
	 {
		 if (Auth::user()->privilege > 2 && Auth::user()->id != $id) {
			 $user = User::findOrFail($id);
			 if($user->enable == 1){
				 $user->enable = 0;
				 $saved = $user->save();
				 //check if saved correctly
				 if (!$saved) {
				 	return redirect()->back()->with("insert_error","حدث عطل خلال تفعيل هذا المستخدم, من فضلك حاول مرة أخرى");
				 }
				 $log=new Log;
				 $log->table="users";
				 $log->action="update";
				 $log->record_id=$id;
				 $log->user_id=Auth::user()->id;
				 $log->description="قام بتفعيل المستخدم ".$user->username;
				 $log->save();
				 return redirect()->back()->with("success","تم تفعيل المستخدم ".$user->username." بنجاح");
			 }
			 return redirect()->back()->with("info","هذا المستخدم مُفعل بالفعل");
		 }else{
			 return view('errors.privilege',['msg'=>'ليس من صلاحياتك تفعيل المستخدم']);
		 }
	 }
	 /**
 	 * Disable the specified user in storage.
 	 *
 	 * @param  int  $id
 	 * @return Response
 	 */
	 public function disable($id)
	 {
		 if (Auth::user()->privilege > 2 && Auth::user()->id != $id) {
			 $user = User::findOrFail($id);
			 if($user->enable == 0){
				 $user->enable = 1;
				 $saved = $user->save();
				 //check if saved correctly
				 if (!$saved) {
				 	return redirect()->back()->with("insert_error","حدث عطل خلال تعطيل هذا المستخدم , من فضلك حاول مرة أخرى");
				 }
				 $log=new Log;
				 $log->table="users";
				 $log->action="update";
				 $log->record_id=$id;
				 $log->user_id=Auth::user()->id;
				 $log->description="قام بتعطيل المستخدم ".$user->username;
				 $log->save();
				 return redirect()->back()->with("success","تم تعطيل المستخدم ".$user->username." بنجاح");
			 }
			 return redirect()->back()->with("info","هذا المستخدم مُعطل بالفعل");
		 }else{
			 return view('errors.privilege',['msg'=>'ليس من صلاحياتك تعطيل المستخدم']);
		 }
	 }

	/**
	 * Remove the specified user from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Auth::user()->privilege == 3 && Auth::user()->id != $id){
			$user=User::findOrFail($id);
			$logs = $user->logs;
			try{
				DB::beginTransaction();
				foreach ($logs as $log) {
					$log->deleted = 1;
					$log->save();
				}
				$user->deleted = 1;
				$user->save();
				DB::commit();
			}
			catch(Exception $e){
				DB::rollBack();
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا الحساب يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->back()->with('success','تم حذف الحساب بنجاح');
		}else{
			return view('errors.privilege',['msg'=>'ليس من صلاحياتك حذف هذا المستخدم']);
		}
	}

}
