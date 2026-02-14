<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FranchiseEmployee;
use App\Models\EmployeePayout;
use App\Models\User;

class FranchiseEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $sort_search = null;
        $employees = FranchiseEmployee::orderBy('name', 'asc');
        
        if ($request->has('search')){
            $sort_search = $request->search;
            $employees = $employees->where('name', 'like', '%'.$sort_search.'%')
                                   ->orWhere('email', 'like', '%'.$sort_search.'%');
        }
        
        $employees = $employees->paginate(15);
        return view('backend.franchise.employees.admin_index', compact('employees', 'sort_search'));
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
}
