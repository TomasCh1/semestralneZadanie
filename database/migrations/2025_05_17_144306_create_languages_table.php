<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    public function up()
    {
        Schema::create('languages', function (Blueprint $t) {
            $t->increments('lang_id');
            $t->char('lang_code', 2);
            $t->string('lang_name', 20);
        });
    }

    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
