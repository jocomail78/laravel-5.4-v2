<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\VerifyEmail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest',
            ['except' => ['activateAccount']]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return redirect(route('verify-email-first'));
    }

    public function verifyEmailFirst(){
        return view('auth.verify');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $customMessages = [
            'password.regex'  => 'The password must have at least 8 characters, one uppercase, one lowercase, one number and one special character',
        ];
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'terms' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
            ]
        ], $customMessages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'terms_accepted_at' => date('Y-m-d H:i:s'),
            'password' => bcrypt($data['password']),
            'verify_token' => Str::random(40),
            'status' =>false,
        ]);

        $this->sendVerifyEmail($user);
        //Session::flash('success','Registration successful. Please confirm your email address.');
        return $user;
    }


    public static function sendVerifyEmail(User $user)
    {
        Mail::to($user->email)->send(new VerifyEmail($user));
    }

    public function resendActivationEmail($email)
    {
        $user = User::where('email',$email)->first();
        if(!$user){
            Session::flash('error','No user found with this email');
            return redirect('/login');
        }
        $user->verify_token = Str::random(40);
        $user->email_verified_at = null;
        $user->status = false;
        $user->save();
        $this->sendVerifyEmail($user);
        return redirect('/login')->with('success','Account confirmation email resent. Please verify your inbox');
    }

    public function activateAccount($email, $verifyToken)
    {
        $user = User::where('verify_token',$verifyToken)->where('email',$email)->first();
        if($user){
            //user found
            $user->verify_token = null;
            $user->status = true;
            $user->email_verified_at = date('Y-m-d h:i:s');
            $user->save();
            $this->guard()->login($user);
            return redirect($this->redirectPath())->with('success','Account verified. Logged in successfully.');
        }else{
            //no user found.
            return view('error.invalidToken');
        }
        $this->guard()->login($user);
    }
}
