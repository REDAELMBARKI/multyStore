<?php

namespace App\Http\Controllers;

use App\Http\Requests\BannerRequest;
use App\Models\AppFactoryConfig;
use App\Models\Banner;
use App\Models\BannerSlot;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BannerController extends Controller
{
    public function index()
    {
       
        $banners = Banner::with('slots.mainMedia', 'slots.secondaryMedia')
            ->join('home_layout_orcs' , function($join){
                $join->on('home_layout_orcs.sortable_id' , '=' , 'banners.id')
                      ->where('home_layout_orcs.sortable_type'  , 'banner') ;
            })
            ->orderBy('home_layout_orcs.order')
            ->get();


        return Inertia::render('admin/pages/store/Banner/BannerEditor', [
            "banners" => $banners,
            "app_factory_config" => [],
            "selectedBanner" => null
        ]);
    }
    public function edit(Banner $banner)
    {
        $banners = Banner::with('slots.mainMedia', 'slots.secondaryMedia')
            ->join('home_layout_orcs', function($join) {
                $join->on('home_layout_orcs.sortable_id', '=', 'banners.id')
                    ->where('home_layout_orcs.sortable_type', 'banner');
            })
            ->orderBy('home_layout_orcs.order')
            ->get(['banners.*']);

        $app_factory_config = AppFactoryConfig::where("config_key", "LIKE", "banners.%")
                                                ->where("config_key", $banner->key)
                                                ->get(['id', 'payload'])
                                                ->map(function($config) { return array_merge($config->payload, ["id" => $config->id]); });
                                                
        return Inertia::render('admin/pages/store/Banner/BannerEditor', [
            "banners" => $banners,
            "app_factory_config" => $app_factory_config,
            "selectedBanner" => $banner
        ]);
    }


    public function update(Banner $banner, BannerRequest $banner_request)
    {
        DB::transaction(function () use ($banner, $banner_request) {
            $validated = $banner_request->validated();
            // Update main banner attributes
            $banner->update(collect($validated)->except('slots')->toArray());

            // Update slots
            if (isset($validated['slots'])) {
                foreach ($validated['slots'] as $slotData) {
                    $banner->slots()->updateOrCreate(
                        ['slot_key' => $slotData['slot_key']],
                        [
                            'is_visible' => $slotData['is_visible'],
                            'width' => $slotData['width'],
                            'bg_color' => $slotData['bg_color'] ?? null,
                            'elements' => $slotData['elements'],
                            'main_media_id' => $slotData['main_media']['id'] ?? null,
                            'secondary_media_id' => $slotData['secondary_media']['id'] ?? null,
                        ]
                    );
                }

                // ── Mark Media as Permanente ───────────────────────────────────── 
                $mediaIds = collect($validated['slots'])
                    ->flatMap(fn($s) => [
                        $s['main_media']['id'] ?? null,
                        $s['secondary_media']['id'] ?? null,
                    ])
                    ->filter()
                    ->unique();

                if ($mediaIds->isNotEmpty()) {
                     Media::whereIn('id', $mediaIds)->update(['is_temporary' => false]);
                }
            }
        });

        $app_factory_config = AppFactoryConfig::where("config_key", "LIKE", "banners.%")->get(['id', 'payload'])
            ->map(function ($config) {
                return array_merge($config->payload, ["id" => $config->id]); });

        $banners = Banner::with('slots.mainMedia', 'slots.secondaryMedia')
            ->join('home_layout_orcs', function($join) {
                $join->on('home_layout_orcs.sortable_id', '=', 'banners.id')
                    ->where('home_layout_orcs.sortable_type', 'banner');
            })
            ->orderBy('home_layout_orcs.order')
            ->get(['banners.*']);

        return Inertia::render('admin/pages/store/Banner/BannerEditor', [
            "banners" => $banners,
            "app_factory_config" => $app_factory_config,
            "selectedBanner" => $banner->fresh(['slots.mainMedia', 'slots.secondaryMedia'])
        ]);
    }


}
