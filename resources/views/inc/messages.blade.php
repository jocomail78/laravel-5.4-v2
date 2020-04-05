@include('inc.errors')
@if(session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{session('error')}}
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning">
        {{session('warning')}}
    </div>
@endif

@if(session('htmlMessage'))
    <div class="alert alert-info">
        {!! session('htmlMessage') !!}
    </div>
@endif
