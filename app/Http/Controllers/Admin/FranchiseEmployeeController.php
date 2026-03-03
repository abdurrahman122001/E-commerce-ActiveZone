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
        $sub_franchise_id = $request->sub_franchise_id;
        $employee_id = $request->employee_id;
        $date_range = $request->date_range;
        $vendors = \App\Models\Vendor::with(['user', 'franchise_package', 'referrer']);
        if (Auth::user()->user_type == 'admin') {
            // Show vendors linked to any level of franchise
            $vendors = $vendors->where(function($q) {
                $q->whereNotNull('franchise_id')
                  ->orWhereNotNull('sub_franchise_id')
                  ->orWhereNotNull('state_franchise_id');
            });
        } elseif (Auth::user()->user_type == 'state_franchise') {
            $state_franchise_id = Auth::user()->state_franchise->id;
            $vendors = $vendors->where(function($q) use ($state_franchise_id) {
                $q->where('state_franchise_id', $state_franchise_id)
                  ->orWhereIn('franchise_id', function($query) use ($state_franchise_id) {
                      $query->select('id')->from('franchises')->where('state_franchise_id', $state_franchise_id);
                  });
            });
        }

        if ($sub_franchise_id) {
            // Filter by specific sub-franchise
            $vendors = $vendors->where('sub_franchise_id', $sub_franchise_id);
        } elseif ($franchise_id) {
            // Filter by franchise: include direct franchise vendors AND sub-franchise vendors
            $subFranchiseIds = \App\Models\SubFranchise::where('franchise_id', $franchise_id)->pluck('id');
            $vendors = $vendors->where(function($q) use ($franchise_id, $subFranchiseIds) {
                $q->where('franchise_id', $franchise_id)
                  ->orWhereIn('sub_franchise_id', $subFranchiseIds);
            });
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
        $sub_franchises = \App\Models\SubFranchise::when($franchise_id, function($q) use ($franchise_id) {
            return $q->where('franchise_id', $franchise_id);
        })->get();
        $employees = FranchiseEmployee::all();

        return view('backend.franchise.employees.vendor_registrations', compact('vendors', 'franchises', 'sub_franchises', 'employees', 'franchise_id', 'sub_franchise_id', 'employee_id', 'date_range'));
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

        if (Auth::user()->user_type == 'state_franchise') {
            $histories = $histories->where('state_franchise_id', Auth::user()->state_franchise->id);
        }

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
    }
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

    public function login($id)
    {
        try {
            $employee_id = decrypt($id);
        } catch (\Exception $e) {
            $employee_id = $id;
        }
        $employee = FranchiseEmployee::findOrFail($employee_id);
        
        // Security check: Only Admin or the parent Franchise Owner can login
        if (Auth::user()->user_type == 'admin' || 
            (Auth::user()->user_type == 'franchise' && $employee->franchise_id == Auth::user()->franchise->id)) {
            
            Auth::guard('franchise_employee')->login($employee, true);
            return redirect()->route('franchise.employee.dashboard');
        }
        
        flash(translate('Access denied.'))->error();
        return back();
    }
    
    public function set_commission(Request $request)
    {
        $employee = FranchiseEmployee::findOrFail($request->id);
        $employee->commission_percentage = $request->commission_percentage;
        $employee->commission_type = $request->commission_type;
        $employee->save();

        return 1;
    }

    public function approveVendor($id)
    {
        $vendor = \App\Models\Vendor::with(['franchise_package', 'referrer', 'sub_franchise.franchise.state_franchise', 'franchise.state_franchise', 'state_franchise'])->findOrFail($id);
        $vendor->status = 'approved';
        $vendor->save();

        // Also approve the shop
        if ($vendor->user && $vendor->user->shop) {
            $vendor->user->shop->registration_approval = 1;
            $vendor->user->shop->save();
        }

        // Distribute package commissions if vendor has purchased a package
        if ($vendor->franchise_package_id && $vendor->franchise_package) {
            $package_price = $vendor->franchise_package->price;

            // --- 1. Sub-Franchise Commission ---
            $sub_franchise = $vendor->sub_franchise;
            if ($sub_franchise) {
                $sf_pct = $sub_franchise->commission_percentage > 0 
                    ? (float) $sub_franchise->commission_percentage 
                    : (float) get_setting('sub_franchise_commission_on_vendor_package');
                
                if ($sf_pct > 0) {
                    $sf_comm_type = $sub_franchise->commission_percentage > 0 
                        ? ($sub_franchise->commission_type ?? 'percentage') 
                        : (get_setting('sub_franchise_commission_on_vendor_package_type') ?? 'percentage');

                    if ($sf_comm_type == 'flat') {
                        $sf_amount = $sf_pct;
                    } else {
                        $sf_amount = ($package_price * $sf_pct) / 100;
                    }
                    $sub_franchise->balance = ($sub_franchise->balance ?? 0) + $sf_amount;
                    $sub_franchise->save();

                    \App\Models\PackageCommissionHistory::create([
                        'sub_franchise_id'   => $sub_franchise->id,
                        'franchise_id'        => $sub_franchise->franchise_id,
                        'state_franchise_id'  => $sub_franchise->state_franchise_id,
                        'vendor_id'           => $vendor->id,
                        'franchise_package_id'=> $vendor->franchise_package_id,
                        'amount'              => $sf_amount,
                        'percentage'          => $sf_pct,
                        'type'                => 'vendor_package',
                        'beneficiary_type'    => 'sub_franchise',
                    ]);
                }
            }

            // --- 2. City Franchise Commission ---
            $franchise = $vendor->franchise ?? ($sub_franchise ? $sub_franchise->franchise : null);
            if ($franchise) {
                $cf_pct = $franchise->commission_percentage > 0 
                    ? (float) $franchise->commission_percentage 
                    : (float) get_setting('franchise_commission_on_vendor_package');
                
                if ($cf_pct > 0) {
                    $cf_comm_type = $franchise->commission_percentage > 0 
                        ? ($franchise->commission_type ?? 'percentage') 
                        : (get_setting('franchise_commission_on_vendor_package_type') ?? 'percentage');

                    if ($cf_comm_type == 'flat') {
                        $cf_amount = $cf_pct;
                    } else {
                        $cf_amount = ($package_price * $cf_pct) / 100;
                    }
                    $franchise->balance = ($franchise->balance ?? 0) + $cf_amount;
                    $franchise->save();

                    \App\Models\PackageCommissionHistory::create([
                        'franchise_id'        => $franchise->id,
                        'sub_franchise_id'    => $sub_franchise ? $sub_franchise->id : null,
                        'state_franchise_id'  => $franchise->state_franchise_id,
                        'vendor_id'           => $vendor->id,
                        'franchise_package_id'=> $vendor->franchise_package_id,
                        'amount'              => $cf_amount,
                        'percentage'          => $cf_pct,
                        'type'                => 'vendor_package',
                        'beneficiary_type'    => 'franchise',
                    ]);
                }
            }

            // --- 3. State Franchise Commission ---
            $state_franchise = $vendor->state_franchise
                ?? ($franchise ? $franchise->state_franchise : null)
                ?? ($sub_franchise ? $sub_franchise->state_franchise : null);
            if ($state_franchise) {
                $stf_pct = $state_franchise->commission_percentage > 0 
                    ? (float) $state_franchise->commission_percentage 
                    : (float) get_setting('state_franchise_commission_on_vendor_package');
                
                if ($stf_pct > 0) {
                    $stf_comm_type = $state_franchise->commission_percentage > 0 
                        ? ($state_franchise->commission_type ?? 'percentage') 
                        : (get_setting('state_franchise_commission_on_vendor_package_type') ?? 'percentage');

                    if ($stf_comm_type == 'flat') {
                        $stf_amount = $stf_pct;
                    } else {
                        $stf_amount = ($package_price * $stf_pct) / 100;
                    }
                    $state_franchise->balance = ($state_franchise->balance ?? 0) + $stf_amount;
                    $state_franchise->save();

                    \App\Models\PackageCommissionHistory::create([
                        'state_franchise_id'  => $state_franchise->id,
                        'franchise_id'        => $franchise ? $franchise->id : null,
                        'sub_franchise_id'    => $sub_franchise ? $sub_franchise->id : null,
                        'vendor_id'           => $vendor->id,
                        'franchise_package_id'=> $vendor->franchise_package_id,
                        'amount'              => $stf_amount,
                        'percentage'          => $stf_pct,
                        'type'                => 'vendor_package',
                        'beneficiary_type'    => 'state_franchise',
                    ]);
                }
            }
        }

        // --- 4. Vendor Referral Commission ---
        if (get_setting('vendor_referral_commission_activation') == 1 && $vendor->referred_by_id && $vendor->franchise_package_id && $vendor->franchise_package) {
            // Prevent duplicate commissions for the same referred vendor
            $exists = \App\Models\VendorReferralCommissionHistory::where('referred_vendor_id', $vendor->id)->exists();
            if (!$exists) {
                $referrer = $vendor->referrer;
                if ($referrer) {
                $package = $vendor->franchise_package;
                
                // Precedence: Referrer override > Package setting > Global setting
                if ($referrer->referral_commission_value > 0) {
                    $ref_commission_type  = $referrer->referral_commission_type ?? 'percentage';
                    $ref_commission_value = (float) $referrer->referral_commission_value;
                } elseif ($package->referral_commission > 0) {
                    $ref_commission_type  = $package->referral_commission_type;
                    $ref_commission_value = (float) $package->referral_commission;
                } else {
                    $ref_commission_type  = get_setting('vendor_referral_commission_type') ?? 'percentage';
                    $ref_commission_value = (float) (get_setting('vendor_referral_commission_value') ?? 0);
                }

                if ($ref_commission_value > 0) {
                    if ($ref_commission_type == 'flat') {
                        $ref_amount = $ref_commission_value;
                    } else {
                        $ref_amount = ($package->price * $ref_commission_value) / 100;
                    }

                    // Credit referral balance
                    $referrer->referral_balance = ($referrer->referral_balance ?? 0) + $ref_amount;
                    $referrer->save();

                    // Record history
                    \App\Models\VendorReferralCommissionHistory::create([
                        'referrer_vendor_id'   => $referrer->id,
                        'referred_vendor_id'   => $vendor->id,
                        'franchise_package_id' => $vendor->franchise_package_id,
                        'commission_type'      => $ref_commission_type,
                        'commission_value'     => $ref_commission_value,
                        'amount'               => $ref_amount,
                        'payout_status'        => 'pending',
                    ]);
                }
            }
        }
    }

    flash(translate('Vendor approved successfully.'))->success();
    return back();
}

    public function rejectVendor($id)
    {
        $vendor = \App\Models\Vendor::findOrFail($id);
        $vendor->status = 'rejected';
        $vendor->save();

        // Also reject the shop
        if ($vendor->user && $vendor->user->shop) {
            $vendor->user->shop->registration_approval = 0;
            $vendor->user->shop->save();
        }

        flash(translate('Vendor rejected.'))->success();
        return back();
    }
}
