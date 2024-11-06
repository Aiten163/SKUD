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
        Schema::table('doors', function (Blueprint $table) {
            $table->time('unlock_duration')->nullable()->default('01:45:00');
            $table->time('warn_duration')->nullable()->default('00:05:00');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('doors',['unlock_duration','unlock_duration']);
    }
};
