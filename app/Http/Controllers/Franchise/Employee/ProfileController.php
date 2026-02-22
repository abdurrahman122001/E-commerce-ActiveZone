<?php

namespace App\Http\Controllers\Franchise\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Artisan;

class ProfileController extends Controller
{
    public function index()
    {
        $employee = Auth::guard('franchise_employee')->user();
        return view('backend.franchise.employees.profile', compact('employee'));
    }

    public function update(Request $request)
    {
        $employee = Auth::guard('franchise_employee')->user();
        $employee->name = $request->name;
        
        if ($request->password != null && ($request->password == $request->password_confirmation)) {
            $employee->password = Hash::make($request->password);
        }

        $employee->bank_name = $request->bank_name;
        $employee->bank_acc_name = $request->bank_acc_name;
        $employee->bank_acc_no = $request->bank_acc_no;
        $employee->bank_routing_no = $request->bank_routing_no;

        if ($employee->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            flash(translate('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Something went wrong!'))->error();
        return back();
    }
}
