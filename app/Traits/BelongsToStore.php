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
                } elseif (auth()->check()) {
                    $model->store_id = auth()->user()->store_id;
                }
            }
        });

        static::addGlobalScope('store', function (Builder $builder) {
            $storeId = null;

            if (session()->has('store_id')) {
                $storeId = session()->get('store_id');
            } elseif (auth()->check()) {
                $storeId = auth()->user()->store_id;
            }

            if ($storeId) {
                $builder->where($builder->getQuery()->from . '.store_id', $storeId);
            } else {
                // If we are on a subdomain but no store_id is in session/auth yet
                // (e.g., first hit on a page), we should still filter by domain if possible
                $host = request()->getHost();
                $store = Store::where('domain', $host)->first();
                if ($store) {
                    $builder->where($builder->getQuery()->from . '.store_id', $store->id);
                }
            }
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
