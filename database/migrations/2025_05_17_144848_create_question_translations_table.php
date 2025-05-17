<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('question_translations', function (Blueprint $t) {
            $t->unsignedInteger('question_id');
            $t->char('lang_code', 2);
            $t->text('text_latex');
            $t->primary(['question_id','lang_code']);

            $t->foreign('question_id')->references('question_id')->on('questions');
            $t->foreign('lang_code')->references('lang_code')->on('languages');
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_translations');
    }
}
