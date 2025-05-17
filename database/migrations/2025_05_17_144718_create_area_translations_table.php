<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('area_translations', function (Blueprint $t) {
            $t->unsignedInteger('area_id');
            $t->char('lang_code', 2);
            $t->string('name', 100);
            $t->primary(['area_id','lang_code']);

            $t->foreign('area_id')->references('area_id')->on('areas')->onDelete('cascade');
            $t->foreign('lang_code')->references('lang_code')->on('languages')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('area_translations');
    }
}
