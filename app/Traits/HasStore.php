<?php

namespace App\Traits;

use App\Models\Store;
use App\Services\StoreManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

trait HasStore
{
    protected static function bootHasStore()
    {
        $storeManager = App::make(\App\Services\tenancy\StoreManager::class);
        $storeId = $storeManager->getStoreId();

        // Global scope to filter by store_id
        static::addGlobalScope('store', function (Builder $builder) use ($storeId) {
            if ($storeId) {
                $builder->where('store_id', $storeId);
            }
        });

        // Automatically set store_id when creating
        static::creating(function ($model) use ($storeId) {
            if ($storeId && !$model->store_id) {
                $model->store_id = $storeId;
            }
        });
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
