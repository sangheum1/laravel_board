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

use Illuminate\Support\Facades\session;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    function login() {
        // $arr['key'] = 'test';
        // $arr['kim'] = 'park';
        // Log::emergency('emergency', $arr);
        // Log::alert('alert', $arr);
        // Log::critical('critical', $arr);
        // Log::error('error', $arr);
        // Log::warning('warning', $arr);
        // Log::notice('notice', $arr);
        // Log::info('info', $arr);
        // Log::debug('debug', $arr); // .env 에서 stack => daily로 변경해서 오류 확인하고 싶을때 사용 그리고 log_level : warning설정
        return view('Iogin');
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
            $error = '아이디와 비밀번호를 확인해 주세요.';
            return redirect()->back()->with('error', $error);
        }

        // 유저 인증작업
        Auth::login($user); // 인증작업 에러 나오게 하려면 이거 없애면 됨
        // 인증이 맞는지 check : true,false 리턴
        if(Auth::check()) {
            session($user->only('id')); // 세션에 인증된 회원 pk 등록
            return redirect()->intended(route('boards.index')); // intended : 필요한 데이터들 빼고 clear 시켜줌(인증이 되었을 경우 middleware로 가는거고 middleware에 설정 안했으면 boards.index로 넘어감)
        } else {
            $error = '인증작업 에러';
            return redirect()->back()->with('error', $error);
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
            $error = '시스템 에러가 발생하여 회원가입에 실패했습니다.<br>잠시후 재시도 해주시기 바랍니다.';
            return redirect()->route('users.registration')->with('error', $error);
        }

        // 회원가입 완료 로그인 페이지로 이동
        return redirect()->route('users.login')->with('success' , '가입하신 이메일로 로그인후 사용해 주시기 바랍니다.'); // 세션에 값을 넣음

    }

    function logout() {
        session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
    }

    function withdraw() {
        $id = session('id');
        // User::find(Auth::User()->id); id 정보 가져오는 다른 방법


        // return var_dump(session()->all(), $id) // session 확인용, 탈퇴할때 request 받아서 post로 줘야함
        $result = User::destroy($id); // softdelete사용 (탈퇴가 완료되었을땐 완료메시지와 함께 리다이렉트하고 탈퇴시 에러가 나면 에러 처리및 메시지처리=>2차 project)
        session::flush();
        Auth::logout();
        return redirect()->route('users.login');
    }

    function update() {
        if(auth()->guest()) {
            return redirect()->route('users.login');
        }
        $id = session('id');
        $user = User::find($id);
        return view('update')->with('data', $user);
    }

    function updatepost(Request $req) {
        $arrKey = []; // 수정할 항목을 담는 배열 변수

        $baseUser = User::find(Auth::User()->id); // 기존 데이터 획득

        // 기존 패스워드 체크
        if(!Hash::check($req->bpassword, $baseUser->password)) {
            return redirect()->back()->with('error', '기존 비밀번호를 확인해 주세요');
        }

        // 수정할 항목을 배열에 담는 처리 => 수정하고 싶은 항목만 들어감
        if($req->name !== $baseUser->name) {
            $arrKey[] = 'name';
        }
        if($req->email !== $baseUser->email) {
            $arrKey[] = 'email';
        }
        if(isset($req->password)) {
            $arrKey[] = 'password';
        }

        // 유효성 체크를 하는 모든 항목 리스트
        $chkList = [
            'name' => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            , 'email' => 'required|email|max:100'
            , 'bpassword' => 'regex:/^(?=.*[A-Za-z])(?=.*[@$!%*#?&])(?=.*[0-9]).{8,20}$/' // 기존의 비밀번호(나랑 다름)
            , 'password' => 'same:passwordchk|regex:/^(?=.*[A-Za-z])(?=.*[@$!%*#?&])(?=.*[0-9]).{8,20}$/'
        ];

        // 유효성 체크할 항목 세팅하는 처리
        $arrchk['bpassword'] = $chkList['bpassword'];
        foreach($arrKey as $val) {
            $arrchk[$val] = $chkList[$val];
        }

        // 유효성 체크
        $req->validate($arrchk);

        // 수정할 데이터 세팅
        foreach($arrKey as $val) {
            if($val === 'password') {
                $baseUser->$val = Hash::make($req->$val);
                continue;
            }
            $baseUser->$val = $req->$val;
        }
        $baseUser->save(); // update

        return redirect()->route('users.login');










        $id = session('id');
        $user = User::where('email', $req->email)->first();
        if($user) {
            $error = '중복된 이메일 입니다';
            return redirect()->back()->with('error', $error);
        }

        if(isset($req->password)) {
            $req->validate([
                'name' => 'required|regex:/^[가-힣]+$/|min:2|max:30'
                , 'email' => 'required|email|max:100'
                , 'password' => 'same:passwordchk|regex:/^(?=.*[A-Za-z])(?=.*[@$!%*#?&])(?=.*[0-9]).{8,20}$/'
            ]);
            User::find($id)->update(['name'=> $req->name, 'email'=> $req->email, 'password'=> Hash::make($req->password)]);
        } else {
            $req->validate([
                'name' => 'required|regex:/^[가-힣]+$/|min:2|max:30'
                , 'email' => 'required|email|max:100'
            ]);
            User::find($id)->update(['name'=> $req->name, 'email'=> $req->email]);
        }


        return redirect()->route('boards.index');
    }

}
