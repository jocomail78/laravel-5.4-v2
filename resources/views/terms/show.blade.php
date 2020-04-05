@extends('layouts.app')

@section('content')
    @if($term)
        <div class="inner cover">
            <h1 class="cover-heading">Terms and conditions</h1>
            <p>{{$term->content}}</p>
            <hr>
            <a class="btn btn-primary" href="/terms">Go back</a>
        </div>
    @else
        <p>No terms and conditions found.</p>
    @endif
@endsection
