@extends('layouts.app')

@section('content')
    <div class="inner cover">
        <div class="panel panel-default btn-">
            <div class="panel-heading">Create new terms of services</div>
            <div class="panel-body">
                {!! Form::open(['action' => ['TermsController@update', $term->id], 'method' => 'POST']) !!}
                    <div class="form-group">
                        {{Form::label('title','Title')}}
                        {{Form::text('title',$term->title, ['class' => 'form-control', 'placeholder' => 'Title'])}}
                        @include('inc.error_line',['fieldname' => 'title'])
                    </div>
                    <div class="form-group">
                        {{Form::label('content','Content')}}
                        {{Form::textarea('content',$term->content, ['class' => 'form-control', 'placeholder' => 'Body text'])}}
                        @include('inc.error_line',['fieldname' => 'body'])
                    </div>
                    <div class="form-group">
                        <label>{{Form::checkbox('publish','1')}} Publish automatically</label>
                        @include('inc.error_line',['fieldname' => 'publish'])
                    </div>
                    <div class="clearfix"></div>
                {{Form::hidden('_method','PUT')}}
                {{Form::submit('Submit',['class' => 'form-control btn btn-success'])}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
