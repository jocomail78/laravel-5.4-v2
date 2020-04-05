@extends('layouts.app')

@section('content')
    @if(count($terms) > 0)
        <div class="inner cover">
            <h1 class="cover-heading">Terms and conditions</h1>
            @foreach($terms as $term)
                @if ($loop->first)
                    <p>{{$term->content}}</p>
                    <hr>
                    @if(count($terms) > 1)
                        <p>Previous versions of the terms and conditions:</p><br>
                    @endif
                @else
                    <a href="/terms/{{$term->id}}">Published at {{$term->published_at}}</a><br>
                @endif
            @endforeach
        {{ $terms->links() }}
        </div>

    @else
        <p>No terms and conditions found.</p>
    @endif
@endsection
