<?php

namespace App\Http\Controllers;

use App\Models\AppFactoryConfig;
use App\Models\Banner;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::with('slots.mainMedia', 'slots.secondaryMedia')->orderBy('id')->get();
        
        return Inertia::render('admin/pages/store/Banner/BannerEditor', [
              "banners" => $banners,
              "app_factory_config" => [],
              "selectedBanner" => null
        ]);
    }
    public function edit(Banner $banner)
    {
        $banners = Banner::with('slots.mainMedia', 'slots.secondaryMedia')->orderBy('id')->get();
        $app_factory_config = AppFactoryConfig::where("config_key" , "LIKE", "banners.%")
                                                ->where("config_key" , $banner->key)
                                                ->get(['id' , 'payload'])
                                                ->map(function($config) { return array_merge($config->payload , ["id" => $config->id]) ; })  ;
        return Inertia::render('admin/pages/store/Banner/BannerEditor', [
              "banners" => $banners,
              "app_factory_config" => $app_factory_config,
              "selectedBanner" => $banner
        ]);
    }


    public function update(Banner $banner , Request $banner_request)
    {
        $banner->update($banner_request->validated()) ;
        $app_factory_config = AppFactoryConfig::where("config_key" , "LIKE", "banners.%")->get(['id' , 'payload'])
        ->map(function($config) { return array_merge($config->payload , ["id" => $config->id]) ; })  ;
        $banners = Banner::orderBy('id')->get();
        return Inertia::render('admin/pages/store/Banner/BannerEditor', [
              "banners" => $banners,
              "app_factory_config" => $app_factory_config,
        ]);
       
    }


}
