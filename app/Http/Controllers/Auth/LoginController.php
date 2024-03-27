<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/home';

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
        // Check for specific roles and redirect accordingly
        if ($user->hasRole('super_admin')) {
            return redirect()->route('dashboard-super-admin');
        } else if ($user->hasRole('finance_admin')) {
            return redirect()->route('dashboard-finance-admin');
        } else if ($user->hasRole('admin')) {
            return redirect()->route('dashboard-admin');
        } else {
            // Default redirect for other roles (or error handling)
            return redirect()->route('dashboard-agent');
        }
    }
}
