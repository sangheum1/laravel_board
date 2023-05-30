<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>write</title>
</head>
<body>
    @include('layout.errorsvalidate')

    <form action="{{route('boards.store')}}" method="post">
        @csrf
        <label for="title">제목 : </label>
        {{-- request를 할때 그 데이터들을 세션에 임시로 저장하고 그것을 불러오는 메소드 old() 사용 --}}
        <input type="text" name="title" id="title" value="{{old('title')}}">
        <br>
        <label for="content">내용 : </label>
        <textarea name="content" id="content">{{old('content')}}</textarea>
        <br>
        <button type="submit">작성</button>
    </form>
</body>
</html>