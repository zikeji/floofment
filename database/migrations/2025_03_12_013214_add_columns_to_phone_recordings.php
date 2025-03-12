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
        Schema::table('phone_recordings', function (Blueprint $table) {
            $table->string('called', 16)->index();
            $table->index('from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phone_recordings', function (Blueprint $table) {
            $table->dropColumn('called');
            $table->dropIndex('phone_recordings_from_index');
        });
    }
};
