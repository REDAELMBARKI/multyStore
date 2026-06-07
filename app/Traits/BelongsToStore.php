<?php

namespace App\Traits;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToStore
{
    protected static function bootBelongsToStore(): void
    {
        static::creating(function ($model) {
            if (!$model->store_id) {
                if (session()->has('store_id')) {
                    $model->store_id = session()->get('store_id');
                }
            }
        });

        static::addGlobalScope('store', function (Builder $builder) {
            $storeId = null;

            if (request()->attributes->has('tenant_store')) {
                $storeId = request()->attributes->get('tenant_store')->id;
            } elseif (session()->has('store_id')) {
                $storeId = session()->get('store_id');
            }

            if ($storeId) {
                $builder->where($builder->getQuery()->from . '.store_id', $storeId);
            }
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
