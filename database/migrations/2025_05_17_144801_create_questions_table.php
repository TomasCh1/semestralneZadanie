<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $t) {
            $t->increments('question_id');
            $t->enum('type', ['MCQ','TEXT']);
            $t->text('question_text');
            $t->text('correct_answer')->nullable();
            $t->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
