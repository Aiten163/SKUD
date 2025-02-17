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
        Schema::create('doors', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('room')->nullable();
            $table->unsignedInteger('building')->nullable();
            $table->unsignedInteger('owner')->nullable();
            $table->tinyInteger('level')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doors');
    }
};
