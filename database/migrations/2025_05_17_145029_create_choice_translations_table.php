<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChoiceTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('choice_translations', function (Blueprint $t) {
            $t->unsignedInteger('choice_id');
            $t->unsignedInteger('lang_id');
            $t->string('text', 255);
            $t->primary(['choice_id','lang_id']);

            $t->foreign('choice_id')->references('choice_id')->on('choices');
            $t->foreign('lang_id')->references('lang_id')->on('languages');
        });
    }

    public function down()
    {
        Schema::dropIfExists('choice_translations');
    }
}
