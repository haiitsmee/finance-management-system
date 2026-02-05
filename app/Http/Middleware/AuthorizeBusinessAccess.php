<?php

namespace App\Http\Middleware;

use App\Models\Business;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeBusinessAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $businessSlug = $request->route('businesses');
        $business = Business::where('slug', $businessSlug)->first();
        
        if (auth()->user()->role === 'superadmin') {
            return $next($request);
        }
        
        $hasAccess = auth()->user()->businesses->contains('id', $business->id);

        if (!$hasAccess) {
            notify()->error('Tidak diijinkan untuk mengakses!', 'Akses Bisnis');
            return redirect('/login');
        }
        
        return $next($request);
    }
}
