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
        $tables = [
            'users',
            'categories',
            'products',
            'orders',
            'badges',
            'tags',
            'coupons',
            'promotions',
            'product_attributes',
            'shipping_settings',
            'shipping_zones',
            'shipping_zone_cities',
            'media',
            'variants_options_settings',
            'google_sheets',
            'store_settings',
            'roles',
            'banners',
            'banner_slots',
            'home_layout_orcs',
            'sliders',
            'slides',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'store_id')) {
                    $table->unsignedBigInteger('store_id')->nullable()->after('id');
                    $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'categories',
            'products',
            'orders',
            'badges',
            'tags',
            'coupons',
            'promotions',
            'product_attributes',
            'shipping_settings',
            'shipping_zones',
            'shipping_zone_cities',
            'media',
            'variants_options_settings',
            'google_sheets',
            'store_settings',
            'roles',
            'banners',
            'banner_slots',
            'home_layout_orcs',
            'sliders',
            'slides',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['store_id']);
                $table->dropColumn('store_id');
            });
        }
    }
};
