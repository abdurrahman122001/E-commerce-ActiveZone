<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FranchiseEmployee;
use App\Models\EmployeePayout;
use App\Models\User;
use Auth;

class FranchiseEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $sort_search = null;
        $franchise_id = $request->franchise_id;
        
        $employees = FranchiseEmployee::orderBy('name', 'asc');
        
        if ($request->has('search')){
            $sort_search = $request->search;
            $employees = $employees->where(function($q) use ($sort_search) {
                $q->where('name', 'like', '%'.$sort_search.'%')
                  ->orWhere('email', 'like', '%'.$sort_search.'%');
            });
        }

        if ($franchise_id) {
            $employees = $employees->where('franchise_id', $franchise_id);
        }
        
        $employees = $employees->paginate(15);
        $franchises = \App\Models\Franchise::all();
        
        return view('backend.franchise.employees.admin_index', compact('employees', 'sort_search', 'franchises', 'franchise_id'));
    }

    public function vendor_registrations(Request $request)
    {
        $franchise_id = $request->franchise_id;
        $employee_id = $request->employee_id;
        $date_range = $request->date_range;
        
        $vendors = \App\Models\Vendor::query();

        if (Auth::user()->user_type == 'admin') {
            $vendors = $vendors->whereNotNull('franchise_id');
        }

        if ($franchise_id) {
            $vendors = $vendors->where('franchise_id', $franchise_id);
        }

        if ($employee_id) {
            $vendors = $vendors->where('added_by_employee_id', $employee_id);
        }

        if ($date_range) {
            $dates = explode(' to ', $date_range);
            $start_date = date('Y-m-d 00:00:00', strtotime($dates[0]));
            if (isset($dates[1])) {
                $end_date = date('Y-m-d 23:59:59', strtotime($dates[1]));
            } else {
                $end_date = date('Y-m-d 23:59:59', strtotime($dates[0]));
            }
            $vendors = $vendors->whereBetween('created_at', [$start_date, $end_date]);
        }

        $vendors = $vendors->latest()->paginate(15);
        $franchises = \App\Models\Franchise::all();
        $employees = FranchiseEmployee::all();

        return view('backend.franchise.employees.vendor_registrations', compact('vendors', 'franchises', 'employees', 'franchise_id', 'employee_id', 'date_range'));
    }

    public function payout_modal(Request $request)
    {
        $employee = FranchiseEmployee::findOrFail($request->id);
        return view('backend.franchise.employees.payout_modal', compact('employee'));
    }

    public function payout_store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:salary,bonus',
            'payment_method' => 'required'
        ]);

        EmployeePayout::create([
            'employee_id' => $request->employee_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'payment_method' => $request->payment_method,
            'remark' => $request->remark
        ]);

        flash(translate('Payout processed successfully'))->success();
        return back();
    }

    public function payout_history($id)
    {
        $employee = FranchiseEmployee::findOrFail(decrypt($id));
        $payouts = EmployeePayout::where('employee_id', $employee->id)->latest()->paginate(15);
        return view('backend.franchise.employees.payout_history', compact('employee', 'payouts'));
    }

    public function sales_report(Request $request)
    {
        $franchise_id = $request->franchise_id;
        $sub_franchise_id = $request->sub_franchise_id;
        $date_range = $request->date_range;

        $histories = \App\Models\VendorCommissionHistory::query();

        if ($franchise_id) {
            $histories = $histories->where('franchise_id', $franchise_id);
        }

        if ($sub_franchise_id) {
            $histories = $histories->where('sub_franchise_id', $sub_franchise_id);
        }

        if ($date_range) {
            $dates = explode(' to ', $date_range);
            $start_date = date('Y-m-d 00:00:00', strtotime($dates[0]));
            if (isset($dates[1])) {
                $end_date = date('Y-m-d 23:59:59', strtotime($dates[1]));
            } else {
                $end_date = date('Y-m-d 23:59:59', strtotime($dates[0]));
            }
            $histories = $histories->whereBetween('created_at', [$start_date, $end_date]);
        }

        $histories = $histories->latest()->paginate(15);
        $franchises = \App\Models\Franchise::all();
        $sub_franchises = \App\Models\SubFranchise::all();

        return view('backend.franchise.sales_report', compact('histories', 'franchises', 'sub_franchises', 'franchise_id', 'sub_franchise_id', 'date_range'));
    public function approve($id)
    {
        $employee = FranchiseEmployee::findOrFail($id);
        $employee->status = 'approved';
        $employee->is_active = 1;
        $employee->save();

        flash(translate('Employee approved successfully'))->success();
        return back();
    }

    public function reject($id)
    {
        $employee = FranchiseEmployee::findOrFail($id);
        $employee->status = 'rejected';
        $employee->is_active = 0;
        $employee->save();

        flash(translate('Employee rejected successfully'))->success();
        return back();
    }
}
