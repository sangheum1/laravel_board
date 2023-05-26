<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>detail</title>
</head>
<body>
    <form action="{{route('boards.destroy', ['board' => $data->id])}}" method="post">
    {{-- 폼태그 액션에 boards.destroy, boards.update, boards.show로 바꾸더라도 url은 boards/3으로 똑같기때문에 method로 제어함 --}}

        @csrf
        @method('DELETE')
        <button type="submit">삭제</button>
    </form>
        <div>
            글번호 : {{$data->id}}
            <br>
            제목 : {{$data->title}}
            <br>
            내용 : {{$data->content}}
            <br>
            등록일자 : {{$data->created_at}}
            <br>
            수정일자 : {{$data->updated_at}}
            <br>
            조회수 : {{$data->hits}}
            <br>
        </div>
    <button type="button" onclick="location.href='{{route('boards.index')}}'">리스트페이지 이동</button>
    <button type="button" onclick="location.href='{{route('boards.edit', ['board' => $data->id])}}'">수정페이지로 이동</button>
</body>
</html>