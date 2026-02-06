<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsFranchise
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
        if (Auth::check() && !Auth::user()->banned) {
            if (Auth::user()->user_type == 'franchise') {
                if (Auth::user()->franchise && (Auth::user()->franchise->status == 'approved' || Auth::user()->franchise->status == 'pending')) {
                    return $next($request);
                }
            } elseif (Auth::user()->user_type == 'sub_franchise') {
                if (Auth::user()->sub_franchise && (Auth::user()->sub_franchise->status == 'approved' || Auth::user()->sub_franchise->status == 'pending')) {
                    return $next($request);
                }
            }
        }
        
        return redirect()->route('home')->with('error', 'Access denied. You must be an approved franchise or sub-franchise.');
    }
}
