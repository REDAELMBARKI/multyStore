<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionRequest;
use App\Models\AppFactoryConfig;
use App\Models\RuleBasedCollection;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RuleBasedCollectionController extends Controller
{  

    public function index()
    {
        $collections = RuleBasedCollection::orderBy('id')->get();
        // Updated to match the actual keys used in the seeder (home.X, etc.)
        $app_factory_config = AppFactoryConfig::where("config_key" , "LIKE", "home.%")
                                                ->orWhere("config_key", "LIKE", "collections.%")
                                                ->get(['id' , 'config_key', 'payload'])
                                                ->map(function($config) { 
                                                    return array_merge($config->payload , [
                                                        "id" => $config->id,
                                                        "config_key" => $config->config_key
                                                    ]); 
                                                });

        return Inertia::render('admin/pages/store/RuleBasedCollections/CollectionEditor', [
              "collections" => $collections,
              "app_factory_config" => $app_factory_config,
              "selectedCollection" => null
        ]);
    }
    public function edit(RuleBasedCollection $collection)
    {
        $collections = RuleBasedCollection::orderBy('id')->get();
        $app_factory_config = AppFactoryConfig::where("config_key" , "LIKE", "home.%")
                                                ->orWhere("config_key", "LIKE", "collections.%")
                                                ->get(['id' , 'config_key', 'payload'])
                                                ->map(function($config) { 
                                                    return array_merge($config->payload , [
                                                        "id" => $config->id,
                                                        "config_key" => $config->config_key
                                                    ]); 
                                                });
       
        return Inertia::render('admin/pages/store/RuleBasedCollections/CollectionEditor', [
              "collections" => $collections,
              "app_factory_config" => $app_factory_config,
              "selectedCollection" => $collection
        ]);
    }


    public function update(RuleBasedCollection $collection, CollectionRequest $request)
    {
        $collection->update($request->validated());

        // Refresh model to get latest data
        $collection->refresh();

        $collections = RuleBasedCollection::orderBy('id')->get();
        $app_factory_config = AppFactoryConfig::where("config_key" , "LIKE", "home.%")
            ->orWhere("config_key", "LIKE", "collections.%")
            ->get(['id', 'config_key', 'payload'])
            ->map(function ($config) {
                return array_merge($config->payload, [
                    "id" => $config->id,
                    "config_key" => $config->config_key
                ]);
            });

        return Inertia::render('admin/pages/store/RuleBasedCollections/CollectionEditor', [
            "collections" => $collections,
            "app_factory_config" => $app_factory_config,
            "selectedCollection" => $collection
        ]);
    }


    public function reorder(Request $request, RuleBasedCollection $collection)
    {
        // Reorder removed as per previous banner logic
        return back();
    }



}
