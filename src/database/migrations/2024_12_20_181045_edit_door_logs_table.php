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
        Schema::table('door_logs', function (Blueprint $table) {
            $table->timestamp('time')->useCurrent()->change();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('door_logs', function (Blueprint $table) {
            $table->timestamp('time')->default(now())->change();
        });
    }
};
