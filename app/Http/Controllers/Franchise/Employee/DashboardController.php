<?php

namespace App\Http\Controllers\Franchise\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Vendor;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $employee = Auth::guard('franchise_employee')->user();
        
        // Registration Stats
        $today = \Carbon\Carbon::today();
        $this_week = [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()];
        $this_month = [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()];
        $this_year = [\Carbon\Carbon::now()->startOfYear(), \Carbon\Carbon::now()->endOfYear()];

        $vendors_today = Vendor::where('added_by_employee_id', $employee->id)->whereDate('created_at', $today)->count();
        $vendors_week = Vendor::where('added_by_employee_id', $employee->id)->whereBetween('created_at', $this_week)->count();
        $vendors_month = Vendor::where('added_by_employee_id', $employee->id)->whereBetween('created_at', $this_month)->count();
        $vendors_year = Vendor::where('added_by_employee_id', $employee->id)->whereBetween('created_at', $this_year)->count();

        // Main List with Filters
        $vendors_query = Vendor::where('added_by_employee_id', $employee->id);
        
        $date_range = $request->date_range;
        if ($date_range) {
            $dates = explode(' to ', $date_range);
            $start_date = date('Y-m-d 00:00:00', strtotime($dates[0]));
            if (isset($dates[1])) {
                $end_date = date('Y-m-d 23:59:59', strtotime($dates[1]));
            } else {
                $end_date = date('Y-m-d 23:59:59', strtotime($dates[0]));
            }
            $vendors_query->whereBetween('created_at', [$start_date, $end_date]);
        }

        $vendors_count = Vendor::where('added_by_employee_id', $employee->id)->count(); // Keep total count separate
        $vendors = $vendors_query->latest()->paginate(10);
        
        return view('backend.franchise.employees.dashboard', compact(
            'employee', 'vendors_today', 'vendors_week', 'vendors_month', 'vendors_year', 
            'vendors_count', 'vendors', 'date_range'
        ));
    }

    public function payouts()
    {
        $employee = Auth::guard('franchise_employee')->user();
        $payouts = \App\Models\EmployeePayout::where('employee_id', $employee->id)->latest()->paginate(10);
        return view('backend.franchise.employees.payouts', compact('employee', 'payouts'));
    }

    public function sales_report(Request $request)
    {
        $sort_search = null;
        $delivery_status = null;
        $date_range = null;

        $employee = Auth::guard('franchise_employee')->user();
        
        $history = \App\Models\VendorCommissionHistory::query();
        
        if ($employee->franchise_level == 'FRAN') {
            $history->where('franchise_id', $employee->franchise_id);
        } else {
            $history->where('sub_franchise_id', $employee->sub_franchise_id);
        }

        if ($request->has('search')) {
            $sort_search = $request->search;
            $history->whereHas('order_detail', function ($query) use ($sort_search) {
                $query->whereHas('order', function ($q) use ($sort_search) {
                    $q->where('code', 'like', '%' . $sort_search . '%');
                });
            });
        }
        
        if ($request->delivery_status) {
            $delivery_status = $request->delivery_status;
            $history->whereHas('order_detail', function ($query) use ($delivery_status) {
                $query->where('delivery_status', $delivery_status);
            });
        }

        if ($request->date_range) {
            $date_range = $request->date_range;
            $dates = explode(' to ', $date_range);
            $start_date = date('Y-m-d 00:00:00', strtotime($dates[0]));
            if (isset($dates[1])) {
                $end_date = date('Y-m-d 23:59:59', strtotime($dates[1]));
            } else {
                $end_date = date('Y-m-d 23:59:59', strtotime($dates[0]));
            }
            $history->whereBetween('created_at', [$start_date, $end_date]);
        }

        $histories = $history->latest()->paginate(15);
        
        return view('franchise.sales_report', compact('histories', 'sort_search', 'delivery_status', 'date_range'));
    }
}
