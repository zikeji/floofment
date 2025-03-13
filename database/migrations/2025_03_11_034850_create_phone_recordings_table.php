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
        Schema::create('phone_recordings', function (Blueprint $table) {
            $table->char('sid', 34)->primary();
            $table->enum('status', ['started', 'recorded', 'available'])->default('started');
            $table->string('from', 16);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_recordings');
    }
};
