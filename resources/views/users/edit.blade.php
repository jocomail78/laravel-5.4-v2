@extends('layouts.app')

@section('content')
    <div class="inner cover">
        <div class="panel panel-default">
            <div class="panel-heading">Edit user {{$user->name}}</div>

            <div class="panel-body">
                {!! Form::open(['action' => ['UsersController@update',$user->id], 'method' => 'POST']) !!}
                <div class="form-group">
                    {{Form::label('name','Name')}}
                    {{Form::text('name',$user->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
                    @include('inc.error_line',['fieldname' => 'name'])
                </div>
                <div class="form-group">
                    {{Form::label('email','Email')}}
                    {{Form::email('email',$user->email, ['class' => 'form-control', 'placeholder' => 'Email'])}}
                    @include('inc.error_line',['fieldname' => 'email'])
                </div>
                <div class="form-group">
                    {{Form::label('phone','Phone')}}
                    {{Form::text('phone',$user->phone, ['class' => 'form-control', 'placeholder' => 'Phone'])}}
                    @include('inc.error_line',['fieldname' => 'phone'])
                </div>
                <hr>
                <div class="form-group">
                    {{Form::label('password','New password (leave blank if you don\'t want to change it)')}}
                    {{Form::input('password','password','',['type' => 'password', 'class' => 'form-control', 'placeholder' => 'Password'])}}
                    @include('inc.error_line',['fieldname' => 'password'])
                </div>
                <div class="form-group">
                    {{Form::label('password_confirmed','New password confirm (leave blank if you don\'t want to change it)')}}
                    {{Form::input('password','password_confirmed','',['type' => 'password', 'class' => 'form-control', 'placeholder' => 'Password confirmed'])}}
                    @include('inc.error_line',['fieldname' => 'password_confirmed'])
                </div>
                <hr>
                <div class="form-group">
                    <label>{{Form::checkbox('status','1',$user->status)}} User email address verified</label>
                    @include('inc.error_line',['fieldname' => 'status'])
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    {{Form::label('email_verified_at','Email address verified at ')}}
                    {{Form::text('email_verified_at',$user->email_verified_at, ['class' => 'form-control', 'placeholder' => 'YYYY-mm-dd HH:ii:ss'])}}
                    @include('inc.error_line',['fieldname' => 'email_verified_at'])
                </div>
                <div class="form-group">
                    {{Form::label('terms_accepted_at','Terms and services accepted at')}}
                    {{Form::text('terms_accepted_at',$user->terms_accepted_at, ['class' => 'form-control', 'placeholder' => 'YYYY-mm-dd HH:ii:ss'])}}
                    @include('inc.error_line',['fieldname' => 'terms_accepted_at'])
                </div>
                <div class="form-group">
                    {{Form::label('created_at','Created at')}}
                    {{Form::text('created_at',$user->created_at, ['class' => 'form-control', 'placeholder' => 'YYYY-mm-dd HH:ii:ss'])}}
                    @include('inc.error_line',['fieldname' => 'created_at'])
                </div>
                <div class="form-group">
                    {{Form::label('updated_at','Updated at')}}
                    {{Form::text('updated_at',$user->updated_at, ['class' => 'form-control', 'placeholder' => 'YYYY-mm-dd HH:ii:ss'])}}
                    @include('inc.error_line',['fieldname' => 'updated_at'])
                </div>
                {{Form::hidden('_method','PUT')}}
                {{Form::submit('Submit',['class' => 'btn btn-primary'])}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
