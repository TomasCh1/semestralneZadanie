<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionAreasTable extends Migration
{
    public function up()
    {
        Schema::create('question_areas', function (Blueprint $t) {
            $t->unsignedInteger('question_id');
            $t->unsignedInteger('area_id');
            $t->primary(['question_id','area_id']);

            $t->foreign('question_id')->references('question_id')->on('questions');
            $t->foreign('area_id')->references('area_id')->on('areas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_areas');
    }
}
