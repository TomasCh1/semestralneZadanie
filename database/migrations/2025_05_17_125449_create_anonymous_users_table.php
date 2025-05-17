<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnonymousUsersTable extends Migration
{
    public function up()
    {
        Schema::create('anonymous_users', function (Blueprint $t) {
            $t->char('anon_id', 36)->primary();
            $t->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('anonymous_users');
    }
}