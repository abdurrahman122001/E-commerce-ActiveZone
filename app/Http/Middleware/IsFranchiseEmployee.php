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
        $employee = auth()->guard('franchise_employee')->user();
        
        if ($employee && $employee->status != 'rejected' && $employee->is_active) {
            
            // If pending, only allow dashboard
            if ($employee->status == 'pending') {
                $allowed_routes = ['franchise.employee.dashboard', 'franchise.employee.logout'];
                if (!in_array($request->route()->getName(), $allowed_routes)) {
                    flash(translate('Your account is pending approval. Please wait for admin to approve your account.'))->warning();
                    return redirect()->route('franchise.employee.dashboard');
                }
            }
            
            return $next($request);
        }

        auth()->guard('franchise_employee')->logout();
        return redirect()->route('login')->with('error', 'Your account is inactive or rejected. Please contact administrator.');
    }
}
