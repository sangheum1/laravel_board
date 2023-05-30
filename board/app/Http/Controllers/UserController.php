<?php
/***********************************
 * 프로젝트명        : laravel_board
 * 디렉토리          : Controllers
 * 파일명            : UserController.php
 * 이력              : v001 0530 SH.Yun new
 ***********************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

use App\Models\User;

class UserController extends Controller
{
    function login() {
        return view('login');
    }

    function loginpost(Request $req) {
        // 유효성 체크
        $req->validate([
            'email' => 'required|email|max:100'
            , 'password' => 'required|regex:/^(?=.*[A-Za-z])(?=.*[@$!%*#?&])(?=.*[0-9]).{8,20}$/' // required_unless : 두개가 똑같은지 비교
        ]);

        // 유저 정보 습득
        $user = User::where('email', $req->email)->first();
        if(!$user || !(Hash::check($req->password, $user->password))) {
            $errors[] = '아이디와 비밀번호를 확인해 주세요.';
            return redirect()->back()->with('errors', collect($errors));
        }

        // 유저 인증작업
        // Auth::login($user); // 인증작업 에러 나오게 하려면 이거 없애면 됨
        // 인증이 맞는지 check : true,false 리턴
        if(Auth::check()) {
            session([$user->only('id')]); // 세션에 인증된 회원 pk 등록
            return redirect()->intended(route('boards.index')); // intended : 필요한 데이터들 빼고 clear 시켜줌
        } else {
            $errors[] = '인증작업 에러';
            return redirect()->back()->with('errors', collect($errors));
        }
    }

    function registration() {
        return view('registration');
    }

    function registrationpost(Request $req) {
        // 유효성체크
        $req->validate([
            'name' => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            , 'email' => 'required|email|max:100'
            , 'password' => 'same:passwordchk|regex:/^(?=.*[A-Za-z])(?=.*[@$!%*#?&])(?=.*[0-9]).{8,20}$/' // required_unless : 두개가 똑같은지 비교
        ]);

        $data['name'] = $req->name;
        $data['email'] = $req->input('email');
        $data['password'] = Hash::make($req->password); //Hash() : 암호화 시킴

        $user = User::create($data); // insert하는법
        if(!$user) {
            $errors[] = '시스템 에러가 발생하여 회원가입에 실패했습니다.';
            $errors[] = '잠시후 재시도 해주시기 바랍니다.';
            return redirect()->route('users.registration')->with('errors', collect($errors)); // $errors는 배열이라 collect로 객체화 시켜주기
        }

        // 회원가입 완료 로그인 페이지로 이동
        return redirect()->route('users.login')->withInput($arr['success']);

    }

}
