<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /*
      Change authenticating to be with username
    */
    public function username()
    {
      return 'username';
    }
    /*
    * show login form
    */
    public function getLogin()
    {
      // code...#
      if (Auth::check()) {
        // code...
        return redirect()->route("dashboard");
      }else {
        // code...
        return view("auth.login");
      }
    }
    public function authenticate(Request $request)
    {
      $credentials = $request->only('username', 'password');
      if (Auth::attempt($credentials,$request->remember)) {
         return redirect()->intended('dashboard');
      }else{
        return redirect()->back()->withInput()->with("invalid","أسم المستخدم او كلمة المرور غير صحيحة");
      }
    }
    public function logout()
    {
      Auth::logout();
      return redirect()->route('login');
    }
}
