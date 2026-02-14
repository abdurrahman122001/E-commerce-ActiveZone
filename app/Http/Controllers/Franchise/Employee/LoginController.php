<?php

namespace App\Http\Controllers\Franchise\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\FranchiseEmployee;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('franchise_employee')->check()) {
            return redirect()->route('franchise.employee.dashboard');
        }
        return view('backend.franchise.employees.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('franchise_employee')->attempt(['email' => $request->email, 'password' => $request->password], $request->has('remember'))) {
            $user = Auth::guard('franchise_employee')->user();
            if (!$user->is_active) {
                Auth::guard('franchise_employee')->logout();
                return back()->with('error', 'Your account is inactive.');
            }
            return redirect()->route('franchise.employee.dashboard');
        }

        return back()->with('error', 'Invalid credentials.');
    }

    public function logout()
    {
        Auth::guard('franchise_employee')->logout();
        return redirect()->route('franchise.employee.login');
    }
}
