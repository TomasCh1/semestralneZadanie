<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    public function up()
    {
        Schema::create('areas', function (Blueprint $t) {
            $t->increments('area_id');
            $t->string('name', 50)->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('areas');
    }
}
