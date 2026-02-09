<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::where('user_id', Auth::user()->id)->orWhere('franchise_id', Auth::user()->franchise->id ?? null)->orWhere('sub_franchise_id', Auth::user()->sub_franchise->id ?? null)
            ->withCount('orders')
            ->withSum('orders', 'grand_total')
            ->get();
        
        $layout = 'vendors.layouts.app';
        if(Auth::user()->user_type == 'franchise' || Auth::user()->user_type == 'sub_franchise'){
            $layout = 'franchise.layouts.app';
        } elseif(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
            $layout = 'backend.layouts.app';
        }

        return view('vendors.index', compact('vendors', 'layout'));
    }

    public function create()
    {
        $layout = 'vendors.layouts.app';
        if(Auth::user()->user_type == 'franchise' || Auth::user()->user_type == 'sub_franchise'){
            $layout = 'franchise.layouts.app';
        } elseif(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
            $layout = 'backend.layouts.app';
        }
        return view('vendors.create', compact('layout'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'commission_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => 'vendor',
        ]);

        $user->assignRole('Vendor');

        $vendor = new Vendor();
        $vendor->user_id = $user->id;
        
        if (Auth::user()->franchise) {
             $vendor->franchise_id = Auth::user()->franchise->id;
        } elseif (Auth::user()->sub_franchise) {
             $vendor->sub_franchise_id = Auth::user()->sub_franchise->id;
        }
        
        $vendor->commission_percentage = $validated['commission_percentage'];
        $vendor->status = 'approved'; 
        $vendor->save();

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }
    
    public function dashboard() {
        $vendor = Vendor::where('user_id', Auth::id())->first();
        $total_orders = 0;
        $total_sales = 0;
        
        if ($vendor) {
            $total_orders = $vendor->orders()->count();
            $total_sales = $vendor->orders()->sum('grand_total'); // Assuming grand_total exists in orders
        }
        
        return view('vendors.dashboard', compact('total_orders', 'total_sales'));
    }

    public function commissionHistory()
    {
        $query = \App\Models\VendorCommissionHistory::query();

        if (Auth::user()->user_type == 'vendor') {
            $vendor = Vendor::where('user_id', Auth::id())->first();
            if ($vendor) {
                $query->where('vendor_id', $vendor->id);
            }
        } elseif (Auth::user()->user_type == 'franchise') {
            if (Auth::user()->franchise) {
                $query->where('franchise_id', Auth::user()->franchise->id);
            }
        } elseif (Auth::user()->user_type == 'sub_franchise') {
             if (Auth::user()->sub_franchise) {
                $query->where('sub_franchise_id', Auth::user()->sub_franchise->id);
            }
        }


        $commission_history = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $layout = 'vendors.layouts.app';
        if(Auth::user()->user_type == 'franchise' || Auth::user()->user_type == 'sub_franchise'){
            $layout = 'franchise.layouts.app';
        } elseif(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
            $layout = 'backend.layouts.app';
        }

        return view('vendors.commission_history', compact('commission_history', 'layout'));
    }
}
