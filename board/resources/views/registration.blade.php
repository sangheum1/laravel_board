@extends('layout.layout')

@section('title', 'Registration')

@section('contents')
    <h2>regist</h2>
    @include('layout.errorsvalidate')
    <form action="{{route('users.registration.post')}}" method="post">
        @csrf
        <label for="name">name : </label>
        <input type="text" name="name" id="name">
        <br>
        <label for="email">Email : </label>
        <input type="text" name="email" id="email">
        <br>
        <label for="password">password : </label>
        <input type="password" name="password" id="password">
        <label for="passwordchk">password : </label>
        <input type="password" name="passwordchk" id="passwordchk">
        <br><br>
        <button type="submit">registration</button>
        <button type="button" onclick="location.href = '{{route('users.login')}}'">cancel</button>
    </form>
@endsection