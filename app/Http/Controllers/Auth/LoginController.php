<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
     /**
     * The user has been authenticated.
     *
     * @
     * param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if($user->roles == 'admin'){
            return redirect()->route('admin.home');
        }
        else if($user->roles == 'guru'){
            return redirect()->route('teacher.home');
        }
        else if($user->roles == 'siswa'){
            return redirect()->route('student.home');
        }
    }
    
    public function redirectTo()
    {   
        if(auth()->user()){
            if(auth()->user()->roles == 'admin'){
                return redirect()->route('admin.home');
            }
            else if(auth()->user()->roles == 'guru'){
                return redirect()->route('teacher.home');
            }
            else if(auth()->user()->roles == 'siswa'){
                return redirect()->route('student.home');
            }
        }
        else{
            return view('auth.login');
        }
    }
}
