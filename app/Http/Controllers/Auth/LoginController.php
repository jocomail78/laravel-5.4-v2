<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Lockout;
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

//    protected $maxAttempts = 10;
//    protected $decayMinutes = 0.5;

    protected $maxAttempts = 3;
    protected $decayMinutes = 3;

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

    /**
     * Fire an event when a lockout occurs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function fireLockoutEvent(Request $request)
    {
        $ip = $request->ip();
        //Log goes to /storage/logs/laravel.log
        Log::warning('Too many failed login attempt from IP '.$request->ip());
        event(new Lockout($request));
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if(!$user->status){
            $this->guard()->logout();
            $route = route('resendActivationEmail', ["email" => $user->email]);
            return back()->with('error', 'You have to confirm your email address before logging in.')
                ->with('htmlMessage','Click <a href="'.$route.'">here</a> if you want to resend the activation email.');
        }
    }
}
