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
        Schema::table('store_settings', function (Blueprint $table) {
            // Drop the old unique constraint on 'key'
            $table->dropUnique('store_settings_key_unique');
            
            // Add a new composite unique constraint
            $table->unique(['store_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropUnique(['store_id', 'key']);
            $table->unique('key');
        });
    }
};
