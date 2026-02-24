<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommissionWithdrawRequest;
use App\Models\VendorCommissionHistory;
use App\Models\Franchise;
use App\Models\SubFranchise;
use App\Models\Vendor;
use App\Models\FranchiseEmployee;
use Auth;

class CommissionWithdrawController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user_type = $user->user_type;
        $user_id = null;
        $balance = 0;

        if ($user_type == 'franchise') {
            $user_id = $user->franchise->id;
            $balance = $user->franchise->balance;
        } elseif ($user_type == 'state_franchise') {
            $user_id = $user->state_franchise->id;
            $balance = $user->state_franchise->balance;
        } elseif ($user_type == 'sub_franchise') {
            $user_id = $user->sub_franchise->id;
            $balance = $user->sub_franchise->balance;
        } elseif ($user_type == 'vendor') {
            $vendor = Vendor::where('user_id', $user->id)->first();
            $user_id = $vendor->id;
            $balance = $vendor->balance;
        }

        $requests = CommissionWithdrawRequest::where('user_id', $user_id)
            ->where('user_type', $user_type)
            ->latest()
            ->paginate(10);

        return view('franchise.withdraw_requests.index', compact('requests', 'balance'));
    }

    public function employee_index()
    {
        $employee = Auth::guard('franchise_employee')->user();
        $user_id = $employee->id;
        $user_type = 'employee';
        $balance = $employee->balance;

        $requests = CommissionWithdrawRequest::where('user_id', $user_id)
            ->where('user_type', $user_type)
            ->latest()
            ->paginate(10);

        return view('franchise.withdraw_requests.index', compact('requests', 'balance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $guard = 'web';
        if (Auth::guard('franchise_employee')->check()) {
            $user = Auth::guard('franchise_employee')->user();
            $guard = 'franchise_employee';
        }

        $user_type = '';
        $user_id = null;
        $balance = 0;
        $bank_info = [];

        if ($guard == 'franchise_employee') {
            $user_type = 'employee';
            $user_id = $user->id;
            $balance = $user->balance;
            $bank_info = [
                'bank_name' => $user->bank_name,
                'bank_acc_name' => $user->bank_acc_name,
                'bank_acc_no' => $user->bank_acc_no,
                'bank_routing_no' => $user->bank_routing_no,
            ];
        } else {
            $user_type = $user->user_type;
            if ($user_type == 'franchise') {
                $user_id = $user->franchise->id;
                $balance = $user->franchise->balance;
                $bank_info = [
                    'bank_name' => $user->franchise->bank_name,
                    'bank_acc_name' => $user->franchise->bank_acc_name,
                    'bank_acc_no' => $user->franchise->bank_acc_no,
                    'bank_routing_no' => $user->franchise->bank_routing_no,
                ];
            } elseif ($user_type == 'state_franchise') {
                $user_id = $user->state_franchise->id;
                $balance = $user->state_franchise->balance;
                $bank_info = [
                    'bank_name' => $user->state_franchise->bank_name,
                    'bank_acc_name' => $user->state_franchise->bank_acc_name,
                    'bank_acc_no' => $user->state_franchise->bank_acc_no,
                    'bank_routing_no' => $user->state_franchise->bank_routing_no,
                ];
            } elseif ($user_type == 'sub_franchise') {
                $user_id = $user->sub_franchise->id;
                $balance = $user->sub_franchise->balance;
                $bank_info = [
                    'bank_name' => $user->sub_franchise->bank_name,
                    'bank_acc_name' => $user->sub_franchise->bank_acc_name,
                    'bank_acc_no' => $user->sub_franchise->bank_acc_no,
                    'bank_routing_no' => $user->sub_franchise->bank_routing_no,
                ];
            } elseif ($user_type == 'vendor') {
                $vendor = Vendor::where('user_id', $user->id)->first();
                $user_id = $vendor->id;
                $balance = $vendor->balance;
                $bank_info = [
                    'bank_name' => $vendor->bank_name,
                    'bank_acc_name' => $vendor->bank_acc_name,
                    'bank_acc_no' => $vendor->bank_acc_no,
                    'bank_routing_no' => $vendor->bank_routing_no,
                ];
            }
        }

        if ($request->amount > $balance) {
            flash(translate('Insufficient balance'))->error();
            return back();
        }

        $withdraw_request = new CommissionWithdrawRequest();
        $withdraw_request->user_id = $user_id;
        $withdraw_request->user_type = $user_type;
        $withdraw_request->amount = $request->amount;
        $withdraw_request->bank_name = $bank_info['bank_name'];
        $withdraw_request->bank_acc_name = $bank_info['bank_acc_name'];
        $withdraw_request->bank_acc_no = $bank_info['bank_acc_no'];
        $withdraw_request->bank_routing_no = $bank_info['bank_routing_no'];
        $withdraw_request->message = $request->message;
        $withdraw_request->save();

        flash(translate('Withdrawal request has been submitted successfully'))->success();
        return back();
    }

    // Admin Methods
    public function admin_index(Request $request)
    {
        $status = $request->status;
        $withdraw_requests = CommissionWithdrawRequest::query();
        if ($status) {
            $withdraw_requests->where('status', $status);
        }
        $requests = $withdraw_requests->latest()->paginate(15);
        return view('backend.withdraw_requests.index', compact('requests', 'status'));
    }

    public function admin_approve($id)
    {
        $withdraw_request = CommissionWithdrawRequest::findOrFail($id);
        if ($withdraw_request->status != 'pending') {
            flash(translate('Request is already processed'))->error();
            return back();
        }

        $withdraw_request->status = 'approved';
        $withdraw_request->save();

        // Approved: earnign should be set as zero (Full balance clear as per user instruction)
        // Actually, let's just subtract the amount? 
        // User said: "after approved thear earning should be set as zero"
        // I will follow the instruction literally.

        if ($withdraw_request->user_type == 'franchise') {
            $franchise = Franchise::find($withdraw_request->user_id);
            $franchise->balance = 0;
            $franchise->save();
            // Mark history as paid
            VendorCommissionHistory::where('franchise_id', $franchise->id)->update(['franchise_payout_status' => 'paid']);
        } elseif ($withdraw_request->user_type == 'state_franchise') {
            $state_franchise = \App\Models\StateFranchise::find($withdraw_request->user_id);
            $state_franchise->balance = 0;
            $state_franchise->save();
            VendorCommissionHistory::where('state_franchise_id', $state_franchise->id)->update(['state_franchise_payout_status' => 'paid']);
        } elseif ($withdraw_request->user_type == 'sub_franchise') {
            $sub_franchise = SubFranchise::find($withdraw_request->user_id);
            $sub_franchise->balance = 0;
            $sub_franchise->save();
            VendorCommissionHistory::where('sub_franchise_id', $sub_franchise->id)->update(['sub_franchise_payout_status' => 'paid']);
        } elseif ($withdraw_request->user_type == 'vendor') {
            $vendor = Vendor::find($withdraw_request->user_id);
            $vendor->balance = 0;
            $vendor->save();
            VendorCommissionHistory::where('vendor_id', $vendor->id)->update(['vendor_payout_status' => 'paid']);
        } elseif ($withdraw_request->user_type == 'employee') {
            $employee = FranchiseEmployee::find($withdraw_request->user_id);
            $employee->balance = 0;
            $employee->save();
            // Need to handle employee in history - wait, history has employee_commission_amount but not employee_id?
            // Actually, I should probably check how employees are linked to specific records.
            // In my calculated commission, I used $vendor->added_by_employee_id.
            // So I should find records where vendor's added_by_employee_id matches this employee.
            VendorCommissionHistory::whereHas('vendor', function($q) use ($employee) {
                $q->where('added_by_employee_id', $employee->id);
            })->update(['employee_payout_status' => 'paid']);
        }

        flash(translate('Withdrawal request approved and balance cleared'))->success();
        return back();
    }

    public function admin_reject(Request $request, $id)
    {
        $withdraw_request = CommissionWithdrawRequest::findOrFail($id);
        $withdraw_request->status = 'rejected';
        $withdraw_request->admin_note = $request->admin_note;
        $withdraw_request->save();

        flash(translate('Withdrawal request rejected'))->success();
        return back();
    }
}
