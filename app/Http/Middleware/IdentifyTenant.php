<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Store;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\URL ;

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
        
        if ($store) {
            $request->attributes->set('tenant_store', $store);
            
            // Set session data. IdentifyTenant runs after StartSession (in append),
            // so we can safely use the session helper here.
            session(['store_id' => $store->id]);
            
            // Set global URL default for Laravel and Ziggy
            URL::defaults(['tenant' => $host]);
        } else {
            // Clear store_id if not on a tenant domain to prevent context leaking
            if (session()->has('store_id')) {
                session()->forget('store_id');
            }

            if (str_ends_with($host, ".localhost")) {
                abort(404);
            }
        }

        return $next($request);
    }
}
