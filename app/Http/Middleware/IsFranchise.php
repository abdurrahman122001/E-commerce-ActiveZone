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
            } elseif (Auth::user()->user_type == 'state_franchise') {
                $status = Auth::user()->state_franchise ? Auth::user()->state_franchise->status : null;
            }

            if (in_array($status, ['approved', 'pending'])) {
                if ($status == 'approved') {
                    $invalid_at = null;
                    if (Auth::user()->user_type == 'franchise' && Auth::user()->franchise) {
                        $invalid_at = Auth::user()->franchise->invalid_at;
                    } elseif (Auth::user()->user_type == 'sub_franchise' && Auth::user()->sub_franchise) {
                        $invalid_at = Auth::user()->sub_franchise->invalid_at;
                    } elseif (Auth::user()->user_type == 'state_franchise' && Auth::user()->state_franchise) {
                        $invalid_at = Auth::user()->state_franchise->invalid_at;
                    }
                    
                    if ($invalid_at != null && strtotime($invalid_at) < strtotime(date('Y-m-d'))) {
                        if (!$request->is('franchise/dashboard')) {
                            return redirect()->route('franchise.dashboard')->with('error', translate('Your franchise package has expired. Please contact admin.'));
                        }
                    }
                }

                if ($status == 'pending' && !$request->is('franchise/dashboard') && !$request->is('franchise/verification-info-update')) {
                    return redirect()->route('franchise.dashboard')->with('warning', translate('Your account is pending approval. Please wait for admin verification to access all features.'));
                }
                return $next($request);
            }
        }
        
        return redirect()->route('home')->with('error', translate('Access denied. You must be an approved state, city, or sub-franchise.'));
    }
}
