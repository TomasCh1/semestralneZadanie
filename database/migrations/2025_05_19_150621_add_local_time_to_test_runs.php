<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // database/migrations/xxxx_add_local_time_to_test_runs.php
        Schema::table('test_runs', function (Blueprint $table) {
            $table->dateTime('started_at_local')->nullable();
            $table->string('timezone', 50)->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_runs', function (Blueprint $table) {
            //
        });
    }
};
