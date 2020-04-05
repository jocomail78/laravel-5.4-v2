<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::paginate(10);
        return view('users.list')->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        if(!$user){
            return redirect('/dashboard')->with('error','User does not exists');
        }
        return view('users.edit')->with('user',$user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if(!$user){
            return redirect('/dashboard')->with('error','User does not exist');
        }

        $customMessages = [
            'password.regex'  => 'The password must have at least 8 characters, one uppercase, one lowercase, one number and one special character',
        ];
        $this->validate($request,[
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|max:255',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
            ],
            'email_verified_at' => 'nullable|date',
            'terms_accepted_at' => 'nullable|date',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
        ], $customMessages);
        $emailChanged = false;
        if($user->email != $request->email){
            $emailChanged = true;
            $user->verify_token = Str::random(40);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if(isset($request->status) && intval($request->status)){
            $user->status = true;
        }else{
            $user->status = false;
        }
        $user->email_verified_at = $request->email_verified_at;
        $user->terms_accepted_at = $request->terms_accepted_at;
        $user->created_at = $request->created_at;
        $user->updated_at = $request->updated_at;

        if(isset($request->password)){
            $user->password = bcrypt($request->password);
        }
        $user->save();
        if($emailChanged){
            RegisterController::sendVerifyEmail($user);
        }

        return redirect('/dashboard')->with('success','User updated successfully');
    }

    public function emailChanged($user)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userId = intval($id);

        if(auth()->user()->id ==$userId){
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete yourself'
            ]);
        }


        $user = User::find($userId);
        if($user){
            $user->delete();
        }
        return response()->json([
            'success' => true,
            'message' => 'User deleted'
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unverify($id)
    {
        $userId = intval($id);
        $loggedInUser = auth()->user();
        if($loggedInUser){
            if($loggedInUser->id == $userId){
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot unverify yourself'
                ]);
            }
        }


        $user = User::find($userId);
        if($user){
            $user->status = false;
            $user->email_verified_at = null;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'User unverified'
        ]);
    }

    public function search($term)
    {
        if(!strlen($term)){
            $users = User::all()->paginate(10);
            $isSearch = false;
        }else{
            $users = DB::table('users')
                ->where(DB::raw('LOWER(name)'),'LIKE','%'.strtolower($term).'%')
                ->orWhere(DB::raw('LOWER(email)'),'LIKE','%'.strtolower($term).'%')
                ->orWhere(DB::raw('LOWER(phone)'),'LIKE','%'.strtolower($term).'%')
                //->get();
                ->paginate(1);
            $isSearch = true;
        }
        return view('users.list')->with('users',$users)->with('isSearch',$isSearch);
    }

}
