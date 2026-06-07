<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Banner;
use App\Models\BannerSlot;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\HomeLayoutOrc;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\RuleBasedCollection;
use App\Models\Slider;
use App\Models\Store;
use App\Models\StoreSetting;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewStoreDefaultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $storeId = null): void
    {
        if (!$storeId) {
            $storeId = Store::first()->id;
        }

        if (!$storeId) {
            return;
        }

        DB::transaction(function () use ($storeId) {
            // 0. Use Global Badges (Avoid duplicates due to unique name constraint)
            $badgesData = ['None', 'New', 'Hot', 'Sale', 'Limited', 'Featured'];
            $badgeMap = [];
            foreach ($badgesData as $bName) {
                $badge = Badge::withoutGlobalScopes()->where('name', $bName)->first();
                if ($badge) {
                    $badgeMap[$bName] = $badge->id;
                }
            }

            // 1. Seed Categories & Subcategories
            $categoriesData = [
                "Fashion" => ["Men", "Women", "Kids", "Accessories"],
                "Electronics" => ["Smartphones", "Laptops", "Audio"],
                "Beauty" => ["Skincare", "Makeup", "Perfumes"],
                "Home" => ["Decor", "Kitchen", "Furniture"]
            ];

            $categoryMap = [];
            foreach ($categoriesData as $catName => $subs) {
                $category = Category::updateOrCreate(
                    ['store_id' => $storeId, 'name' => $catName],
                    ['slug' => Str::slug($catName) . '-' . $storeId]
                );
                $categoryMap[$catName] = $category->id;

                foreach ($subs as $subName) {
                    Category::updateOrCreate(
                        ['store_id' => $storeId, 'name' => $subName, 'parent_id' => $category->id],
                        ['slug' => Str::slug($subName) . '-' . $storeId]
                    );
                }
            }

            // 2. Seed Tags
            $tags = ['New Arrival', 'Trending', 'Limited Edition', 'Best Seller', 'Sale'];
            foreach ($tags as $tagName) {
                Tag::updateOrCreate(
                    ['store_id' => $storeId, 'name' => $tagName],
                    ['slug' => Str::slug($tagName) . '-' . $storeId]
                );
            }

            // 3. Seed Store Settings
            $defaultSettings = [
                ['key' => 'tva_enabled', 'value' => false],
                ['key' => 'tva_rate', 'value' => 20],
                ['key' => 'admin_theme_style', 'value' => 'orangeNight'],
                ['key' => 'store_theme_style', 'value' => 'luxuryNoir'],
                ['key' => 'currency', 'value' => 'MAD'],
                ['key' => 'cod_enabled', 'value' => true],
                ['key' => 'payment_enabled', 'value' => true],
                ['key' => 'store_card_config', 'value' => [
                    'cardId' => 'card-6',
                    'showPrice' => true,
                    'showRating' => true,
                    'showBorder' => true,
                    'isRounded' => true,
                    'borderRadius' => '10px',
                ]],
            ];

            foreach ($defaultSettings as $setting) {
                StoreSetting::updateOrCreate(
                    ['store_id' => $storeId, 'key' => $setting['key']],
                    ['value' => $setting['value']]
                );
            }

            // 4. Seed Banners (Copied from BannerSeeder)
            $bannersData = [
                [
                    "banner" => [
                        'name'          => 'Spring Luxury 2026',
                        'key'           => 'spring_2026',
                        'slug'          => 'spring-2026-' . $storeId,
                        'direction'     => 'ltr',
                        'is_active'     => true,
                        'aspect_ratio'  => '21:9',
                        'border_radius' => '12px',
                        'bg_color'      => '#f3f4f6',
                    ],
                    "slots" => [
                        [
                            'slot_key'   => 'left',
                            'width'      => "65",
                            'is_visible' => true,
                            'bg_color'   => '#ffffff',
                            'elements'   => [
                                'eyebrow'   => ['text' => 'EDITORIAL', 'color' => '#6b7280', 'visible' => true],
                                'title'     => ['text' => 'The Spring Luxe Edit', 'color' => '#111827', 'visible' => true],
                                'paragraph' => ['text' => 'Experience the intersection of comfort and sophistication with our latest seasonal release.', 'color' => '#4b5563', 'visible' => true],
                                'button'    => ['text' => 'SHOP THE COLLECTION', 'bg_color' => '#111827', 'text_color' => '#ffffff', 'visible' => true],
                            ],
                        ],
                        [
                            'slot_key'          => 'right',
                            'width'             => "35",
                            'is_visible'        => true,
                            'image'             => 'https://images.pexels.com/photos/1039439/pexels-photo-1039439.jpeg?auto=compress&cs=tinysrgb&w=1200',
                        ],
                    ],
                ],
            ];

            $seededBanners = [];
            foreach ($bannersData as $item) {
                $bannerData = $item['banner'];
                $bannerData['store_id'] = $storeId;
                $banner = Banner::updateOrCreate(
                    ['store_id' => $storeId, 'key' => $bannerData['key']],
                    $bannerData
                );
                $seededBanners[$bannerData['key']] = $banner->id;
                foreach ($item['slots'] as $slotData) {
                    if (isset($slotData['image'])) {
                        $media = Media::create([
                            'url' => $slotData['image'],
                            'media_type' => 'image',
                            'mediaable_type' => 'App\Models\Banner',
                            'collection' => 'banner',
                        ]);
                        $slotData['main_media_id'] = $media->id;
                        unset($slotData['image']);
                    }
                    $banner->slots()->updateOrCreate(
                        ['slot_key' => $slotData['slot_key']],
                        $slotData
                    );
                }
            }

            // 5. Seed Sliders (Copied from SliderSeeder)
            $slider = Slider::updateOrCreate(
                ['store_id' => $storeId, 'name' => 'Home Hero Slider'],
                [
                    'is_active' => true,
                    'autoplay_speed' => 5000,
                    'show_arrows' => true,
                    'show_dots' => true,
                ]
            );

            $slides = [
                [
                    'image_url' => 'https://images.pexels.com/photos/1926769/pexels-photo-1926769.jpeg?auto=compress&cs=tinysrgb&w=1400',
                    'tag' => 'New Collection · SS 2025',
                    'title' => "The Art of\nSlow Fashion",
                    'subtitle' => 'Curated pieces that transcend seasons',
                    'cta_text' => 'Explore Collection',
                    'cta_link' => '/collections/new-arrivals',
                    'panel_label' => 'Exclusive',
                    'panel_title' => 'Jewelry Noir',
                    'panel_bg' => 'rgba(18,30,50,0.92)',
                    'order' => 1,
                ],
            ];

            foreach ($slides as $slideData) {
                $slider->slides()->updateOrCreate(
                    ['title' => $slideData['title']],
                    $slideData
                );
            }

            // 6. Seed Rule Based Collections (Copied from RuleBasedCollectionsSeeder)
            $collectionsData = [
                [
                    'name' => 'New Season Arrivals',
                    'slug' => 'new-season-arrivals-' . $storeId,
                    'key' => 'home.new_arrivals',
                    'is_active' => true,
                    'layout_config' => [
                        'displayLimit' => 12,
                        'gap' => 24,
                        'paddingInline' => 0
                    ],
                    'card_config' => [
                        'aspectRatio' => '3/4',
                        'borderRadius' => 0,
                        'showPrice' => true,
                        'showBadge' => true,
                        'textAlign' => 'left',
                        'hoverEffect' => 'zoom'
                    ],
                    'rules' => [
                        ['field' => 'badge', 'operator' => '=', 'value' => 'New']
                    ],
                ],
                [
                    'name' => 'The Featured Edit',
                    'slug' => 'the-featured-edit-' . $storeId,
                    'key' => 'home.featured',
                    'is_active' => true,
                    'layout_config' => [
                        'displayLimit' => 12,
                        'gap' => 24,
                        'paddingInline' => 0
                    ],
                    'card_config' => [
                        'aspectRatio' => '3/4',
                        'borderRadius' => 0,
                        'showPrice' => true,
                        'showBadge' => true,
                        'textAlign' => 'left',
                        'hoverEffect' => 'zoom'
                    ],
                    'rules' => [
                        ['field' => 'badge', 'operator' => '=', 'value' => 'Featured']
                    ],
                ],
                [
                    'name' => 'Performance Footwear',
                    'slug' => 'performance-footwear-' . $storeId,
                    'key' => 'home.shoes',
                    'is_active' => true,
                    'layout_config' => [
                        'displayLimit' => 10,
                        'gap' => 24,
                        'paddingInline' => 0
                    ],
                    'card_config' => [
                        'aspectRatio' => '1/1',
                        'borderRadius' => 12,
                        'showPrice' => true,
                        'showBadge' => true,
                        'textAlign' => 'center',
                        'hoverEffect' => 'zoom'
                    ],
                    'rules' => [
                        ['field' => 'category', 'operator' => '=', 'value' => 'Fashion']
                    ],
                ],
            ];

            $seededCollections = [];
            foreach ($collectionsData as $cData) {
                $collection = RuleBasedCollection::updateOrCreate(
                    ['store_id' => $storeId, 'key' => $cData['key']],
                    array_merge($cData, ['store_id' => $storeId])
                );
                $seededCollections[$cData['key']] = $collection->id;
            }

            // 6.1 Seed Home Layout Orchestrator
            $sections = [
                ['id' => $seededBanners['spring_2026'] ?? null, 'type' => 'banner', 'order' => 1],
                ['id' => $seededCollections['home.new_arrivals'] ?? null, 'type' => 'product_collection', 'order' => 2],
                ['id' => $seededCollections['home.featured'] ?? null, 'type' => 'product_collection', 'order' => 3],
                ['id' => $seededCollections['home.shoes'] ?? null, 'type' => 'product_collection', 'order' => 4],
            ];

            foreach ($sections as $section) {
                if ($section['id']) {
                    HomeLayoutOrc::updateOrCreate(
                        [
                            'store_id' => $storeId,
                            'sortable_id' => $section['id'],
                            'sortable_type' => $section['type']
                        ],
                        ['order' => $section['order']]
                    );
                }
            }

            // 7. Seed 18 Default Products (6 per collection)
            $productTypes = [
                ['badge' => $badgeMap['New'] ?? 2, 'cat' => 'Fashion', 'prefix' => 'New Arrival'],
                ['badge' => $badgeMap['Featured'] ?? 6, 'cat' => 'Fashion', 'prefix' => 'Featured'],
                ['badge' => $badgeMap['None'] ?? 1, 'cat' => 'Fashion', 'prefix' => 'Shoe'],
            ];

            $count = 1;
            foreach ($productTypes as $type) {
                for ($j = 1; $j <= 6; $j++) {
                    $product = Product::updateOrCreate(
                        ['store_id' => $storeId, 'slug' => Str::slug("{$type['prefix']}-{$j}") . '-' . $storeId],
                        [
                            'name' => "{$type['prefix']} #{$j}",
                            'brand' => 'MicroMarket',
                            'description' => "This is a premium {$type['prefix']} product description for item #{$j}.",
                            'status' => 'published',
                            'ready_to_publish' => true,
                            'is_featured' => $type['badge'] == ($badgeMap['Featured'] ?? 6),
                            'is_visible' => true,
                            'category_niche_id' => $categoryMap[$type['cat']] ?? null,
                            'badge_id' => $type['badge'],
                        ]
                    );

                    ProductVariant::updateOrCreate(
                        ['product_id' => $product->id, 'sku' => "SKU-PROD-{$count}-" . $storeId],
                        [
                            'price' => rand(100, 1000),
                            'compare_price' => rand(1100, 1500),
                            'stock' => 50,
                            'is_default' => true,
                            'is_single' => true,
                        ]
                    );

                    // Add thumbnail
                    Media::updateOrCreate(
                        [
                            'mediaable_id' => $product->id,
                            'mediaable_type' => 'App\Models\Product',
                            'collection' => 'thumbnail',
                        ],
                        [
                            'url' => "https://picsum.photos/seed/{$product->id}/800/1200",
                            'media_type' => 'image',
                        ]
                    );

                    $count++;
                }
            }

            // 8. Seed 3 Promotions
            $promotions = [
                ['name' => 'Welcome Sale', 'type' => 'percentage', 'value' => 10, 'minimum_order_amount' => 500],
                ['name' => 'Flash Deal', 'type' => 'percentage', 'value' => 25, 'minimum_order_amount' => 1000],
                ['name' => 'Free Delivery', 'type' => 'free_shipping', 'value' => 0, 'minimum_order_amount' => 300],
            ];

            foreach ($promotions as $pData) {
                Promotion::updateOrCreate(
                    ['store_id' => $storeId, 'name' => $pData['name']],
                    array_merge($pData, [
                        'is_active' => true,
                        'valid_from' => now(),
                        'valid_until' => now()->addMonths(1),
                    ])
                );
            }

            // 9. Seed 3 Coupons
            $coupons = [
                ['code' => 'WELCOME10', 'description' => '10% Off your first order', 'type' => 'percentage', 'value' => 10],
                ['code' => 'SAVE50', 'description' => '50 MAD Off', 'type' => 'fixed', 'value' => 50],
                ['code' => 'VIPONLY', 'description' => 'Exclusive 20% Discount', 'type' => 'percentage', 'value' => 20],
            ];

            foreach ($coupons as $cData) {
                Coupon::updateOrCreate(
                    ['store_id' => $storeId, 'code' => $cData['code']],
                    array_merge($cData, [
                        'is_active' => true,
                        'valid_from' => now(),
                        'valid_until' => now()->addMonths(1),
                        'max_uses_per_user' => 1,
                    ])
                );
            }
        });
    }
}
