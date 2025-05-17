<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChoicesTable extends Migration
{
    public function up()
    {
        Schema::create('choices', function (Blueprint $t) {
            $t->increments('choice_id');
            $t->unsignedInteger('question_id');
            $t->string('text', 255);
            $t->boolean('is_correct')->default(false);

            $t->foreign('question_id')->references('question_id')->on('questions');
        });
    }

    public function down()
    {
        Schema::dropIfExists('choices');
    }
}
