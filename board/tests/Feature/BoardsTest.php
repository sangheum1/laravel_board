<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Boards;

class BoardsTest extends TestCase
{
    // php artisan make:test BoardsTest => test 페이지 생성
    // 이름의 끝이 Test로 끝날것

    use RefreshDatabase; // 테스트 완료후 DB 초기화를 위해 사용
    use DatabaseMigrations; // DB 마이그레이션(테이블 자동 생성)

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_게스트_리다이렉트() // 메소드는 test로 무조건 시작
    {
        $response = $this->get('/boards'); // boards 갈때 get방식으로 request요청

        $response->assertRedirect('users/login'); // 비로그인시 redirect 가는지 확인
    }

    public function test_index_유저인증()
    {
        // 테스트용 유저 생성
        $user = new user([
            'email' => 'aa@aa.aa'
            ,'name' => '테스트'
            , 'password' => 'asdasd'
        ]);
        $user->save();

        $response = $this->actingAs($user)->get('/boards');

        $this->assertAuthenticatedAs($user);
    }

    public function test_index_유저인증_뷰반환()
    {
        // 테스트용 유저 생성
        $user = new user([
            'email' => 'aa@aa.aa'
            ,'name' => '테스트'
            , 'password' => 'asdasd'
        ]);
        $user->save(); // 테스트 유저 업데이트

        $response = $this->actingAs($user)->get('/boards'); // boards 갈때 user 정보 가져와서 가는지

        $response->assertViewIs('list');
    }

    public function test_index_유저인증_뷰반환_데이터확인()
    {
        // 테스트용 유저 생성
        $user = new user([
            'email' => 'aa@aa.aa'
            ,'name' => '테스트'
            , 'password' => 'asdasd'
        ]);
        $user->save();

        // 테스트용 유저 생성
        $board1 = new Boards([
            'title' => 'test1'
            , 'content' => 'content1'
        ]);
        $board1->save();

        $board2 = new Boards([
            'title' => 'test2'
            , 'content' => 'content2'
        ]);
        $board2->save();

        $response = $this->actingAs($user)->get('/boards'); // boards 갈때 user 정보 가져와서 가는지

        $response->assertViewHas('data'); // view 안에 data가 들어있는지 확인
        $response->assertSee('test1'); // test1 있는지 확인
        $response->assertSee('test2');
    }
}
