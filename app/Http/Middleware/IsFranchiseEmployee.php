<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsFranchiseEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guard('franchise_employee')->check() && auth()->guard('franchise_employee')->user()->is_active) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Please login to access this area or your account is inactive');
    }
}
