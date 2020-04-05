@extends('layouts.app')

@section('content')
    <div class="inner cover">
        <div class="panel panel-default">
            <div class="panel-heading">Dashboard</div>

            <div class="panel-body">
                <input class="form-control js-user-search" type="text" value="" placeholder="Start typing for search">
                <hr>
                <div class="user-list-holder">
                    @include('users.list')
                </div>
            </div>
        </div>
    </div>
@endsection
