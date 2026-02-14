<?php

namespace App\Http\Controllers\Franchise\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Vendor;

class DashboardController extends Controller
{
    public function index()
    {
        $employee = Auth::guard('franchise_employee')->user();
        $vendors_count = Vendor::where('added_by_employee_id', $employee->id)->count();
        $vendors = Vendor::where('added_by_employee_id', $employee->id)->latest()->paginate(10);
        
        return view('backend.franchise.employees.dashboard', compact('employee', 'vendors_count', 'vendors'));
    }

    public function payouts()
    {
        $employee = Auth::guard('franchise_employee')->user();
        $payouts = \App\Models\EmployeePayout::where('employee_id', $employee->id)->latest()->paginate(10);
        return view('backend.franchise.employees.payouts', compact('employee', 'payouts'));
    }
}
