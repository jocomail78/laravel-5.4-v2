@extends('layouts.app')

@section('content')
    <div class="inner cover">
        <h1 class="cover-heading">Token invalid</h1>
        <p>Your activation URL is invalid. Please double check if you've copied the URL properly, or please try to redo the activation process by clicking here:</p>
        <a class="btn btn-primary" href="/terms">Resend activation email</a>
    </div>
@endsection
