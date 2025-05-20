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
            $t->unsignedInteger('lang_id');
            $t->primary(['question_id','lang_id']);

            $t->foreign('question_id')->references('question_id')->on('questions');
            $t->foreign('lang_id')->references('lang_id')->on('languages');
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_translations');
    }
}
