<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestRunQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('test_run_questions', function (Blueprint $t) {
            $t->increments('run_q_id');
            $t->unsignedInteger('run_id');
            $t->unsignedInteger('question_id');
            $t->smallInteger('shown_order');
            $t->timestamp('started_at');
            $t->timestamp('answered_at')->nullable();
            $t->boolean('is_correct')->default(false);
            $t->string('user_answer', 255)->nullable();
            $t->integer('time_spent_sec')->default(0);

            $t->foreign('run_id')->references('run_id')->on('test_runs');
            $t->foreign('question_id')->references('question_id')->on('questions');
            $t->index(['run_id','question_id'], 'idx_run_question');
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_run_questions');
    }
}
