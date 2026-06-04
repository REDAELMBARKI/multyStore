<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default store if none exists
        if (Store::count() === 0) {
            Store::create([
                'name' => 'Default Store',
                'slug' => 'default',
            ]);
        }

        $this->call([
            SliderSeeder::class , 
            AppFactoryConfigSeeder::class,
            storeSettingSeeder::class,
            UserSeeder::class,
            TagSeeder::class,
            
            // Core Dependencies
            CategorySeeder::class,
            BadgeSeeder::class,
            
            // Content
            ProductSeeder::class,
            BannerSeeder::class,
            RuleBasedCollectionsSeeder::class,
            
            // Orchestration
            HomeLayoutOrcSeeder::class,
            
            // Other settings/support
            CouponSeeder::class,
            RewardScalingPromotionSeeder::class,
       
            OrderSeeder::class,
            CartSeeder::class,
            ShippingSettingSeeder::class,
            ShippingZoneSeeder::class,
            VariantOptionSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
