<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Store;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        
        // Find the store by its unique domain signature
        $store = Store::where('domain', $host)->first();
        
        // Set session domain to allow cross-subdomain sessions
        $parts = explode('.', $host);
        if (count($parts) >= 2) {
            $baseDomain = implode('.', array_slice($parts, -2));
            config(['session.domain' => '.' . $baseDomain]);
        }

        if ($store) {
            $request->attributes->set('tenant_store', $store);
            // Set global URL default for Laravel and Ziggy
            // Use the host without the port for the parameter
            \Illuminate\Support\Facades\URL::defaults(['tenant' => $host]);
        }

        $response = $next($request);

        // Sync session after StartSession middleware has run
        if ($store) {
            session(['store_id' => $store->id]);
        } else {
            // Clear store_id if not on a tenant domain to prevent context leaking
            if (session()->has('store_id')) {
                session()->forget('store_id');
            }
        }

        return $response;
    }
}
