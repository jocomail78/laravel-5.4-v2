{{-- Form validation error line --}}
@if($errors->has($fieldname))
    @foreach($errors->get($fieldname) as $error)
        <p style="color:red;">{{ $error}}</p>
    @endforeach
@endif
