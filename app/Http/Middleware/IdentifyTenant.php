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
        \Illuminate\Support\Facades\Log::info("IdentifyTenant: Handling host: {$host}");
        
        // Find the store by its unique domain signature stored in the database
        $store = Store::where('domain', $host)->first();
     
        if ($store) {
            \Illuminate\Support\Facades\Log::info("IdentifyTenant: Store found: {$store->id}");
            // 1. Force the APP_URL to be EXACTLY what the store's domain is
            $scheme = $request->getScheme();
            $port = $request->getPort();
            $dynamicUrl = $scheme . '://' . $host . ($port ? ':' . $port : '');
            
            config(['app.url' => $dynamicUrl]);

            // 2. Set the SESSION_DOMAIN to allow cross-subdomain sessions
            $parts = explode('.', $host);
            if (count($parts) >= 2) {
                $baseDomain = implode('.', array_slice($parts, -2));
                
                // Special handling for localhost: browsers often don't like '.localhost'
                if ($baseDomain === 'localhost') {
                    config(['session.domain' => null]);
                    \Illuminate\Support\Facades\Log::info("IdentifyTenant: Setting session domain to null (localhost)");
                } else {
                    config(['session.domain' => '.' . $baseDomain]);
                    \Illuminate\Support\Facades\Log::info("IdentifyTenant: Setting session domain to: .{$baseDomain}");
                }
            }

            // 3. Mark this store as active in the session for the BelongsToStore trait
            session(['store_id' => $store->id]);
        } else {
            \Illuminate\Support\Facades\Log::warning("IdentifyTenant: No store found for host: {$host}");
            if ($host === 'unistore.localhost') {
                config(['app.url' => $request->getSchemeAndHttpHost()]);
            }
        }

        return $next($request);
    }
}
