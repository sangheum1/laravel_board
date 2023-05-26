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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('title',30);
            $table->string('content',2000);
            $table->integer('hits');
            $table->timestamps();
            // softDeletes() : 엘로퀀트 이용시에만 플래그 자동으로 설정해주고 쿼리빌더로 이용시 직접 플래그 바꿔줘야함
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boards');
    }
};
