<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Boards;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Validator;

class ApiListController extends Controller
{
    function getlist($id) {
        // // 모든 데이터를 다 보내는 방법
        // $Board = Boards::find($id);
        // return response()->json($Board, 200);
        $board = Http::get('localhost/api/list/6');
        return dd(json_decode($board, true));


        // $response = Http::get('http://localhost/api/list/6');
        // $data = $response->json();
        // dd($data);
    }

    function postlist(Request $req) {
        // 유효성 체크 필요(2차)

        // 우리가 필요한 데이터들만 보내는 방법
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

    function putlist(Request $req, $id) {
        // // 내가 한 방식
        // $rules = array(
        //     'title' => 'required|between:3,30'
        //     , 'content' => 'required|max:1000'
        // );
        // // 유효성 검사
        // $validator = Validator::make($req->all(), $rules);
        // if($validator->fails()){
        //     return $validator->errors();
        // } else {
        //     $boards = Boards::find($id);
        //     Boards::find($id)->update(['title'=> $req->title, 'content'=> $req->content]);
        //     $arr[] = $boards->only('title', 'content');
        //     return $arr;
        // }





        // 선생님이랑 한 방식
        $arrData = [
            'code' => '0'
            , 'msg' => ''
        ];

        $data = $req->only('title', 'content');
        $data['id'] = $id;
        // 유효성 체크
        $validator = Validator::make($data, [
            'id' => 'required|integer|exists:boards'
            , 'title' => 'required|between:3,30'
            , 'content' => 'required|max:1000'
        ]);

        if($validator->fails()) {
            $arrData['code'] = 'E01'; // E01는 실패
            $arrData['msg'] = 'Validate Error';
            $arrData['errmsg'] = $validator->errors()->all(); // validator 자체 에러 출력메시지(errors), errors에서 all을 사용시 안에 키의 배열값은 생략
        } else {
        // 업데이트 처리
        $board = Boards::find($id);
        $board->title = $req->title;
        $board->content = $req->content;
        $board->save();
        $arrData['code'] = '0';
        $arrData['msg'] = 'success';
        }
        return $arrData;
    }

    function deletelist($id) {
        $arrData = [
            'code' => '0'
            , 'msg' => ''
        ];
        $data['id'] = $id;

        // 유효성 체크
        $validator = Validator::make($data, [
            'id' => 'required|integer|exists:boards'
        ]);

        if($validator->fails()) {
            $arrData['code'] = 'E01'; // E01는 실패
            $arrData['msg'] = 'Validate Error';
            $arrData['errmsg'] = $validator->errors()->all(); // validator 자체 에러 출력메시지(errors), errors에서 all을 사용시 안에 키의 배열값은 생략
        } else {
            $data = Boards::find($id);
            if($data) {
            $board = $data->delete();
            $arrData['code'] = '0';
            $arrData['msg'] = 'success';
            } else {
                $arrData['code'] = 'E02';
                $arrData['msg'] = 'already deleted';
            }
        }
        return $arrData;
    }
}
