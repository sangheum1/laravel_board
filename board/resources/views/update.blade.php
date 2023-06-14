@extends('layout.layout')

@section('title', 'update')

@section('contents')
    <h2>update</h2>
    @include('layout.errorsvalidate')
    <form action="{{route('users.update.post')}}" method="post">
        @csrf
        <label for="name">name : </label>
        <input type="text" name="name" id="name" value="{{count($errors) > 0 ? old('title') : $data->name}}">
        <br>
        <label for="email">Email : </label>
        <input type="text" name="email" id="email" value="{{count($errors) > 0 ? old('title') : $data->email}}">
        <br>
        <label for="bpassword">Before password : </label>
		<input type="password" name="bpassword" id="bpassword">
        <br>
        <label for="password">new password : </label>
        <input type="password" name="password" id="password">
        <br>
        <label for="passwordchk">새 password 체크 : </label>
        <input type="password" name="passwordchk" id="passwordchk">
        <br><br>
        <button type="submit">수정하기</button>
        <button type="button" onclick="location.href = '{{route('boards.index')}}'">취소</button>
    </form>
@endsection