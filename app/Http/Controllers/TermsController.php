<?php

namespace App\Http\Controllers;

use App\Events\TermsChanged;
use App\Mail\TermsChangedEmail;
use Illuminate\Http\Request;
use App\Term;
use App\User;
use Illuminate\Support\Facades\Mail;


class TermsController extends Controller
{
    private $adminUserIdList = [1];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',
            ['except' => ['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
         * TODO - Why we have subject in this way
         * and why we don't have when going through queue
        $u = User::find(auth()->user()->id);
        Mail::to('electrum89@gmail.com')
            ->send(new TermsChangedEmail($u));
        */

        $loggedInUser = auth()->user();
        if($loggedInUser){
            if(in_array($loggedInUser->id,$this->adminUserIdList)){
                $terms = Term::orderBy('published_at','desc')->paginate(10);
                return view('terms.admin_index')->with('terms',$terms);
            }
        }
        $terms = Term::whereNotNull('published_at')->orderBy('published_at','desc')->paginate(10);
        return view('terms.index')->with('terms',$terms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loggedInUser = auth()->user();
        if($loggedInUser) {
            if (!in_array($loggedInUser->id, $this->adminUserIdList)) {
                return redirect('/terms')->with('error','Unauthorized access');
            }
        }
        return view('terms.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loggedInUser = auth()->user();
        if($loggedInUser) {
            if (!in_array($loggedInUser->id, $this->adminUserIdList)) {
                return redirect('/terms')->with('error','Unauthorized access');
            }
        }

        $this->validate($request,[
            'title' => 'required',
            'content' => 'required',
        ]);
        $messageEnd = '';
        $term = new Term;
        $term->title = $request->input('title');
        $term->content = $request->input('content');
        if(!is_null($request->input('publish')) && intval($request->input('publish'))){
            $term->published_at = date('Y-m-d H:i:s');
            event(new TermsChanged());
            $messageEnd = ' and published';
        }
        $term->save();
        return redirect('/terms')->with('success','New terms of services created'.$messageEnd);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $term = Term::find($id);
        if(!$term){
            return redirect('/terms')->with('error','The term you are trying to access does not exists.Perhaps it was deleted meanwhile.');
        }
        if(is_null($term->published_at)){
            return redirect('/terms')->with('error','Unauthorized access');
        }
        return view('terms.show')
            ->with('term',$term);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loggedInUser = auth()->user();
        if($loggedInUser) {
            if (!in_array($loggedInUser->id, $this->adminUserIdList)) {
                return redirect('/terms')->with('error','Unauthorized access');
            }
        }
        $term = Term::find($id);
        if(!$term){
            return redirect('/terms')->with('error','The terms you are trying to edit does not exists. Perhaps it was deleted meanwhile.');
        }
        return view('terms.edit')
            ->with('term',$term);
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
        $loggedInUser = auth()->user();
        if($loggedInUser) {
            if (!in_array($loggedInUser->id, $this->adminUserIdList)) {
                return redirect('/terms')->with('error','Unauthorized access');
            }
        }

        $term = Term::find($id);
        if(!$term){
            return redirect('/terms')->with('error','Term does not exists.');
        }
        if(!is_null($term->published_at)){
            return redirect('/terms')->with('error','You are not allowed to edit an already published term.');
        }

        $this->validate($request,[
            'title' => 'required',
            'content' => 'required',
        ]);
        $messageEnd = '';
        $term->title = $request->input('title');
        $term->content = $request->input('content');
        if(!is_null($request->input('publish')) && intval($request->input('publish'))){
            $term->published_at = date('Y-m-d H:i:s');
            event(new TermsChanged());
            $messageEnd = ' and published';
        }
        $term->save();
        return redirect('/terms')->with('success','Terms of services updated'.$messageEnd);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loggedInUser = auth()->user();
        if($loggedInUser) {
            if (!in_array($loggedInUser->id, $this->adminUserIdList)) {
                return redirect('/terms')->with('error','Unauthorized access');
            }
        }

        $term = Term::find($id);
        if($term){
            $term->delete();
        }
        return response()->json([
            'success' => true,
            'message' => 'Term deleted'
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publish($id)
    {
        $loggedInUser = auth()->user();
        if($loggedInUser) {
            if (!in_array($loggedInUser->id, $this->adminUserIdList)) {
                return redirect('/terms')->with('error','Unauthorized access');
            }
        }
        $term = Term::find($id);
        if(!$term){
            return redirect('/terms')->with('error','Term does not exists. Perhaps it was deleted meanwhile');
        }

        if(!is_null($term->published_at)){
            return redirect('/terms')->with('error','Term already published');
        }

        $term->published_at = date('Y-m-d H:i:s');
        event(new TermsChanged());
        $term->save();

        return redirect('/terms')->with('success','Terms of services published. ');
    }
}
