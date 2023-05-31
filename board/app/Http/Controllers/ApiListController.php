<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Boards;

class ApiListController extends Controller
{
    function getlist($id) {
        $Board = Boards::find($id);
        return response()->json($Board, 200);
    }

    function postlist(Request $req) {
        // 유효성 체크 필요(2차)

        $boards = new Boards([
            'title' => $req->title
            , 'content' => $req->content
        ]);
        $boards->save(); // update
        // return response()->json($boards, 200);


        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id', 'title');
        return $arr;



    }
}
