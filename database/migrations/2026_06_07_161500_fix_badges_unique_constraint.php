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
        Schema::table('badges', function (Blueprint $table) {
            // Remove the global unique index on name
            $table->dropUnique(['name']);
            // Add a composite unique index on name and store_id
            $table->unique(['name', 'store_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('badges', function (Blueprint $table) {
            $table->dropUnique(['name', 'store_id']);
            $table->unique(['name']);
        });
    }
};
