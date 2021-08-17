<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Auth;

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
//    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('Admin'))
        {
            return redirect()->route('adminDashboard');
        }
        elseif ($user->hasRole('Employer'))
        {
            return redirect()->route('employeedashboard', encrypt($user->id));
        }
        elseif ($user->hasRole('Candidate'))
        {
            return redirect()->route('edit_profile', $user->id);

        }
        elseif ($user->hsRole('Sub Admin'))
        {
            return redirect()->route('subAdminDashboard');
        }
        else
        {
            return redirect()->back()->with('message', 'Invalid Credentials');
        }
    }
}
