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
            $status = null;
            if (Auth::user()->user_type == 'franchise') {
                $status = Auth::user()->franchise ? Auth::user()->franchise->status : null;
            } elseif (Auth::user()->user_type == 'sub_franchise') {
                $status = Auth::user()->sub_franchise ? Auth::user()->sub_franchise->status : null;
            }

            if (in_array($status, ['approved', 'pending'])) {
                if ($status == 'pending' && !$request->is('franchise/dashboard')) {
                    return redirect()->route('franchise.dashboard')->with('warning', translate('Your account is pending approval. Please wait for admin verification to access all features.'));
                }
                return $next($request);
            }
        }
        
        return redirect()->route('home')->with('error', translate('Access denied. You must be an approved franchise or sub-franchise.'));
    }
}
