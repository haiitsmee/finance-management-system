<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetBusinessFromRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $businessSlug = $request->route('businesses');

            if ($businessSlug) {
                $business = \App\Models\Business::where('slug', $businessSlug)->first();
            } else {
                if (auth()->user()->role === 'superadmin') {
                    $business = \App\Models\Business::first();
                } else {
                    $business = auth()->user()->businesses()->first();
                }
            }

            if ($business) {
                session([
                    'current_business' => $business->slug,
                    'current_business_name' => $business->name,
                    'current_business_id' => $business->id,
                ]);
            }
        }

        return $next($request);
    }
}
