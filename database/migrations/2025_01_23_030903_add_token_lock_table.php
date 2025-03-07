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
        Schema::table('locks', function (Blueprint $table) {
            $table->string('token', 72)->nullable();
        });
        Schema::table('locks', function (Blueprint $table) {
            $table->unsignedBigInteger('uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('locks','token' );
        Schema::dropColumns('locks','uid' );
    }
};
