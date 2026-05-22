<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyStore
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $storeManager = app(\App\Services\tenancy\StoreManager::class);

        // Find store by domain
        $store = \App\Models\Store::where('domain', $host)->first();

        if (!$store) {
            // For local development, if no store found, maybe use the first one or a default
            if (app()->environment('local')) {
                $store = \App\Models\Store::first();
            }
        }

        if (!$store && !$request->is('admin/*')) {
            // Only abort if not an admin route or we really need a store
            // abort(404, 'Store not found');
        }

        if ($store) {
            $storeManager->setStore($store);
        }

        return $next($request);
    }
}
