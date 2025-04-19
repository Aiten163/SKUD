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
        Schema::create('door_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('time')->default(now());
            $table->foreignId('door_id')->nullable()->constrained('doors');
            $table->foreignId('card_id')->nullable()->constrained('doors');
            $table->string('action', 32)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('door_logs');
    }
};
