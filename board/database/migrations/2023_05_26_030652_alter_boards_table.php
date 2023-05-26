<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 기본 테이블에서 컬럼 추가
        // 패키지 관리자 설치 : composer require doctrine/dbal (테이블에 컬럼 추가 할 경우에)

        Schema::table('boards', function (Blueprint $table) {
            $table->integer('hits')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
