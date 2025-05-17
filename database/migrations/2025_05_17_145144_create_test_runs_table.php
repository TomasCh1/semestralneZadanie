<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestRunsTable extends Migration
{
    public function up()
    {
        Schema::create('test_runs', function (Blueprint $t) {
            $t->increments('run_id');
            $t->unsignedBigInteger('user_id')->nullable();
            $t->char('anon_id', 36)->nullable();
            $t->timestamp('started_at')->useCurrent();
            $t->timestamp('finished_at')->nullable();
            $t->unsignedInteger('run_number')->nullable();
            $t->string('city', 100)->nullable();
            $t->string('state', 100)->nullable();

            $t->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $t->foreign('anon_id')->references('anon_id')->on('anonymous_users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_runs');
    }
}
