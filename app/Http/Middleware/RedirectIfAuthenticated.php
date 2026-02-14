<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (Auth::guard('franchise_employee')->check()) {
                return redirect()->route('franchise.employee.dashboard');
            }

            $user = Auth::user();
            
            // Redirect based on user type - check vendor first
            if ($user && $user->user_type == 'vendor') {
                return redirect()->route('vendor.dashboard');
            } elseif ($user && ($user->user_type == 'admin' || $user->user_type == 'staff')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user && $user->user_type == 'seller') {
                return redirect()->route('seller.dashboard');
            } elseif ($user && in_array($user->user_type, ['franchise', 'sub_franchise'])) {
                return redirect()->route('franchise.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
