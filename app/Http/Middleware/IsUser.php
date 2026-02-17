<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsUser
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
        if (Auth::check() && 
                (Auth::user()->user_type == 'customer' || 
                Auth::user()->user_type == 'seller' || 
                Auth::user()->user_type == 'vendor' || 
                Auth::user()->user_type == 'franchise' || 
                Auth::user()->user_type == 'sub_franchise' || 
                Auth::user()->user_type == 'delivery_boy') ) {
            
            if (Auth::user()->user_type == 'delivery_boy') {
                if (Auth::user()->delivery_boy && Auth::user()->delivery_boy->status == 1) {
                    return $next($request);
                } else {
                    return redirect()->route('delivery_boy.pending');
                }
            }
            
            return $next($request);
        }
        else{
            session(['link' => url()->current()]);
            return redirect()->route('user.login');
        }
    }
}
