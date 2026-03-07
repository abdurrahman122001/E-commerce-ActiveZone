<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->user_type == 'seller' || Auth::user()->user_type == 'vendor') && !Auth::user()->banned) {
            $vendor = Auth::user()->vendor;
            if ($vendor && $vendor->status != 'approved') {
                $allowed_routes = [
                    'vendor.dashboard',
                    'vendor.packages.index',
                    'vendor.package.purchase',
                    'seller.dashboard',
                ];
                if ($request->route() && !in_array($request->route()->getName(), $allowed_routes)) {
                    return redirect()->route('vendor.dashboard');
                }
            }
            return $next($request);
        }
        else{
            abort(404);
        }
    }
}
