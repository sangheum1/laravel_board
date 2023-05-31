@if(count($errors) > 0)
    {{-- validate의 error 값의 모든것을 가져오기 --}}
    @foreach($errors->all() as $error)
        <div>{{$error}}</div>
    @endforeach
@endif

@if(session()->has('error'))
    <div>{!!session('error')!!}</div>
@endif