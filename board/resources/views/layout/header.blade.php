<h2>Header</h2>

{{-- 로그인 상태 --}}
@auth
    <div><a href="{{route('users.logout')}}">logout</a></div>
    <div><a href="{{route('users.update')}}">정보변경</a></div>
@endauth

{{-- 비 로그인 상태 --}}
@guest
    <div><a href="{{route('users.login')}}">login</a></div>
@endguest
<hr>