<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Boards;

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Boards::select(['id', 'title', 'hits', 'created_at', 'updated_at'])->orderBy('hits', 'desc')->get();
        return view('list')->with('data',$result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // 작성페이지
        $boards = new Boards([      // insert 할때 new 사용
            'title' => $req->input('title')
            , 'content' => $req->input('content')
        ]);
        $boards->save(); // insert
        return redirect('/boards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id); // id컬럼의 값을 select, find는 실패시 false를 리턴 해줌
        $boards->hits++; // update 내용 작성
        $boards->save(); // update 완료
        return view('detail')->with('data', Boards::findOrFail($id)); // findOrFail은 실패할 경우 404페이지로 이동
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 수정 페이지 출력
        $boards = Boards::find($id);
        return view('edit')->with('data', $boards);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        // 쿼리빌더
        // DB::table('Boards')->where('id','=', $id)->update([
        //     'title'=> $req->title
        //     , 'content' => $req->content
        // ]);
        // $boards = Boards::find($id);
        // return view('detail')->with('data',$boards);

        // 첫번째 방법
        // $result = Boards::find($id);
        // $result->title = $req->title;
        // $result->content = $req->content;
        // $result->save();
        // // return view('detail')->with('data',Boards::findOrFail($id));  // 업데이트할때 오류가 떴을때 $req로 받으면 오류가 나서 id를 select하기(오류가 안나면 $req랑 $id 값이 똑같음)
        // return redirect('/boards/'.$id);
        // // return redirect()->route('boards.show', ['board' => $id]); // 위랑 밑 둘 중에 아무거나 가능
        
        // 두번째 방법
        // $boards = Boards::find($id);
        Boards::find($id)->update(['title'=> $req->title, 'content'=> $req->content]);
        return redirect('/boards/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Boards::find($id)->update(['deleted_at'=> now()]);
        return redirect('/boards');


        // Boards::destory($id); // pk를 안에 적어야함 (DB::delete() : 레코드 자체가 삭제)
        // return redirect('/boards');



        // $boards = Boards::find($id)->delete();
        // return redirect('/boards');
        
    }
}
