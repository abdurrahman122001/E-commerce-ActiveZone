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
        $employee = Auth::guard('franchise_employee')->user();
        $user = Auth::user();

        if ($employee) {
            $vendors = Vendor::where('added_by_employee_id', $employee->id)
                ->withCount('orders')
                ->withSum('orders', 'grand_total')
                ->get();
            $layout = 'backend.franchise.employees.layout';
            $create_route = route('franchise.employee.vendors.create');
            $edit_route_prefix = 'franchise.employee.vendors';
            return view('vendors.index', compact('vendors', 'layout', 'create_route', 'edit_route_prefix'));
        }

        if (!$user) {
            return redirect()->route('login');
        }

        \Log::info('Vendor Index Query', [
            'user_id' => $user->id,
            'user_type' => $user->user_type,
            'franchise_id' => $user->franchise->id ?? 'null',
            'sub_franchise_id' => $user->sub_franchise->id ?? 'null'
        ]);

        $vendors = Vendor::query();
        $vendors->where(function($q) use ($user) {
            $q->where('user_id', $user->id);
            if ($user->franchise) {
                $q->orWhere('franchise_id', $user->franchise->id);
            }
            if ($user->sub_franchise) {
                $q->orWhere('sub_franchise_id', $user->sub_franchise->id);
            }
            if ($user->user_type == 'state_franchise' && $user->state_franchise) {
                $state_franchise_id = $user->state_franchise->id;
                $q->orWhereIn('franchise_id', function($query) use ($state_franchise_id) {
                    $query->select('id')->from('franchises')->where('state_franchise_id', $state_franchise_id);
                });
                $q->orWhereIn('sub_franchise_id', function($query) use ($state_franchise_id) {
                    $query->select('id')->from('sub_franchises')->where('state_franchise_id', $state_franchise_id);
                });
            }
        });

        $vendors = $vendors->withCount('orders')
            ->withSum('orders', 'grand_total')
            ->get();
        
        // Ensure every vendor has a shop record
        foreach($vendors as $v) {
            if ($v->user && !$v->user->shop) {
                $shop = new \App\Models\Shop();
                $shop->user_id = $v->user->id;
                $shop->name = $v->user->name;
                $shop->slug = \Str::slug($v->user->name) . '-' . $v->user->id;
                $shop->save();
            }
        }
        
        $layout = 'vendors.layouts.app';
        $create_route = route('vendors.create');
        $edit_route_prefix = 'vendors';
        if($user->user_type == 'franchise' || $user->user_type == 'sub_franchise' || $user->user_type == 'state_franchise'){
            $layout = 'franchise.layouts.app';
            $create_route = route('franchise.vendors.create');
            $edit_route_prefix = 'franchise.vendors';
        } elseif($user->user_type == 'admin' || $user->user_type == 'staff'){
            $layout = 'backend.layouts.app';
            $create_route = route('vendors.create');
            $edit_route_prefix = 'vendors';
        }
        
        if (Auth::guard('franchise_employee')->check()) {
            $edit_route_prefix = 'franchise.employee.vendors';
        }

        return view('vendors.index', compact('vendors', 'layout', 'create_route', 'edit_route_prefix'));
    }

    public function create()
    {
        $employee = Auth::guard('franchise_employee')->user();
        if ($employee) {
            $layout = 'backend.franchise.employees.layout';
            $action = route('franchise.employee.vendors.store');
            return view('vendors.create', compact('layout', 'action'));
        }

        $user = Auth::user();
        $layout = 'vendors.layouts.app';
        $action = route('vendors.store');

        if($user && ($user->user_type == 'franchise' || $user->user_type == 'sub_franchise' || $user->user_type == 'state_franchise')){
            $layout = 'franchise.layouts.app';
            $action = route('franchise.vendors.store');
        } elseif($user && ($user->user_type == 'admin' || $user->user_type == 'staff')){
            $layout = 'backend.layouts.app';
            $action = route('vendors.store');
        }
        
        return view('vendors.create', compact('layout', 'action'));
    }

    public function store(Request $request)
    {
        $isAdmin = (Auth::check() && (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'));
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'shop_name' => 'required|string|max:255',
            'address' => 'required|string',
        ];

        if ($isAdmin) {
            $rules['commission_percentage'] = 'required|numeric|min:0|max:100';
        } else {
            $rules['commission_percentage'] = 'nullable|numeric|min:0|max:100';
        }

        $validated = $request->validate($rules);

        \DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'user_type' => 'vendor',
                'verification_status' => 1,
            ]);

            $user->assignRole('Vendor');

            $vendor = new Vendor();
            $vendor->user_id = $user->id;
            $vendor->shop_name = $request->shop_name;
            $vendor->address = $request->address;
            
            if (Auth::guard('franchise_employee')->check()) {
                $employee = Auth::guard('franchise_employee')->user();
                $vendor->added_by_employee_id = $employee->id;
                $vendor->franchise_id = $employee->franchise_id;
                
                if ($employee->franchise_level == 'SUB') {
                    $vendor->sub_franchise_id = $employee->sub_franchise_id;
                    $sub = $employee->subFranchise;
                    $vendor->city_id = $sub ? $sub->city_id : null;
                    $vendor->state_id = $sub ? $sub->state_id : null;
                } else {
                    $franchise = $employee->franchise;
                    $vendor->city_id = $franchise ? $franchise->city_id : null;
                    $vendor->state_id = $franchise ? $franchise->state_id : null;
                }
            } elseif (Auth::check()) {
                $authUser = Auth::user();
                if ($authUser->user_type == 'franchise' && $authUser->franchise) {
                    $vendor->franchise_id = $authUser->franchise->id;
                    $vendor->city_id = $authUser->franchise->city_id;
                    $vendor->state_id = $authUser->franchise->state_id;
                } elseif ($authUser->user_type == 'sub_franchise' && $authUser->sub_franchise) {
                    $vendor->sub_franchise_id = $authUser->sub_franchise->id;
                    $vendor->franchise_id = $authUser->sub_franchise->franchise_id;
                    $vendor->city_id = $authUser->sub_franchise->city_id;
                    $vendor->state_id = $authUser->sub_franchise->state_id;
                }
            }
            
            $vendor->commission_percentage = $isAdmin ? ($request->commission_percentage ?? 0) : 0;
            $vendor->status = $isAdmin ? 'approved' : 'pending'; 
            $vendor->save();

            // Create shop for vendor
            $shop = new \App\Models\Shop();
            $shop->user_id = $user->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->address;
            $shop->slug = \Str::slug($request->shop_name) . '-' . $user->id;
            $shop->registration_approval = $isAdmin ? 1 : 0;
            $shop->save();

            \DB::commit();
            flash(translate('Vendor created successfully.'))->success();
            
            if (Auth::guard('franchise_employee')->check()) {
                return redirect()->route('franchise.employee.vendors.index');
            }

            if (Auth::user()->user_type == 'franchise' || Auth::user()->user_type == 'sub_franchise' || Auth::user()->user_type == 'state_franchise') {
                return redirect()->route('franchise.vendors.index');
            }
            return redirect()->route('vendors.index');
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Vendor Store Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            flash(translate('Failed to create vendor: ') . $e->getMessage())->error();
            return back()->withInput();
        }
    }
    
    public function dashboard() {
        $authUserId = Auth::user()->id;
        
        $data['total_products'] = \App\Models\Product::where('user_id', $authUserId)->count();
        $data['total_sales'] = \App\Models\OrderDetail::where('seller_id', $authUserId)->where('delivery_status', 'delivered')->sum('price');
        $data['pending_orders'] = \App\Models\OrderDetail::where('seller_id', $authUserId)->where('delivery_status', 'pending')->count();
        $data['delivered_orders'] = \App\Models\OrderDetail::where('seller_id', $authUserId)->where('delivery_status', 'delivered')->count();
        
        $data['this_month_pending_orders'] = \App\Models\OrderDetail::where('seller_id', $authUserId)
                                    ->whereDeliveryStatus('pending')
                                    ->whereYear('created_at', \Carbon\Carbon::now()->year)
                                    ->whereMonth('created_at', \Carbon\Carbon::now()->month)
                                    ->count();
        $data['this_month_cancelled_orders'] = \App\Models\OrderDetail::where('seller_id', $authUserId)
                                    ->whereDeliveryStatus('cancelled')
                                    ->whereYear('created_at', \Carbon\Carbon::now()->year)
                                    ->whereMonth('created_at', \Carbon\Carbon::now()->month)
                                    ->count();
        $data['this_month_on_the_way_orders'] = \App\Models\OrderDetail::where('seller_id', $authUserId)
                                    ->whereDeliveryStatus('on_the_way')
                                    ->whereYear('created_at', \Carbon\Carbon::now()->year)
                                    ->whereMonth('created_at', \Carbon\Carbon::now()->month)
                                    ->count();
        $data['this_month_delivered_orders'] = \App\Models\OrderDetail::where('seller_id', $authUserId)
                                    ->whereDeliveryStatus('delivered')
                                    ->whereYear('created_at', \Carbon\Carbon::now()->year)
                                    ->whereMonth('created_at', \Carbon\Carbon::now()->month)
                                    ->count();
                                    
        $data['this_month_sold_amount'] = \App\Models\Order::where('seller_id', $authUserId)
                                    ->wherePaymentStatus('paid')
                                    ->whereYear('created_at', \Carbon\Carbon::now()->year)
                                    ->whereMonth('created_at', \Carbon\Carbon::now()->month)
                                    ->sum('grand_total');
        $data['previous_month_sold_amount'] = \App\Models\Order::where('seller_id', $authUserId)
                                    ->wherePaymentStatus('paid')
                                    ->whereYear('created_at', \Carbon\Carbon::now()->year)
                                    ->whereMonth('created_at', (\Carbon\Carbon::now()->month-1))
                                    ->sum('grand_total');
        
        $data['products'] = \App\Models\Product::where('user_id', $authUserId)->orderBy('num_of_sale', 'desc')->limit(12)->get();
        $data['last_7_days_sales'] = \App\Models\Order::where('created_at', '>=', \Carbon\Carbon::now()->subDays(7))
                                ->where('seller_id', '=', $authUserId)
                                ->where('delivery_status', '=', 'delivered')
                                ->select(\DB::raw("sum(grand_total) as total, DATE_FORMAT(created_at, '%d %b') as date"))
                                ->groupBy(\DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->get()->pluck('total', 'date');

        // Additional stats
        $data['total_categories'] = \App\Models\Category::count();
        $data['total_brands'] = \App\Models\Brand::count();
        
        $data['sale_this_month'] = \App\Models\OrderDetail::where('seller_id', $authUserId)
                                        ->where('delivery_status', 'delivered')
                                        ->whereYear('created_at', \Carbon\Carbon::now()->year)
                                        ->whereMonth('created_at', \Carbon\Carbon::now()->month)
                                        ->sum('price');

        return view('vendors.dashboard', $data);
    }

    public function edit($id)
    {
        try {
            $vendor_id = decrypt($id);
        } catch (\Exception $e) {
            $vendor_id = $id;
        }
        $vendor = Vendor::findOrFail($vendor_id);
        $user = Auth::user();
        
        $layout = 'vendors.layouts.app';
        $update_route = route('vendors.update', $vendor->id);
        if($user->user_type == 'franchise' || $user->user_type == 'sub_franchise' || $user->user_type == 'state_franchise'){
            $layout = 'franchise.layouts.app';
            $update_route = route('franchise.vendors.update', $vendor->id);
        } elseif($user->user_type == 'admin' || $user->user_type == 'staff'){
            $layout = 'backend.layouts.app';
            $update_route = route('vendors.update', $vendor->id);
        }

        if (Auth::guard('franchise_employee')->check()) {
            $update_route = route('franchise.employee.vendors.update', $vendor->id);
        }

        return view('vendors.edit', compact('vendor', 'layout', 'update_route'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        $user = $vendor->user;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'shop_name' => 'required|string|max:255',
            'address' => 'required|string',
        ];

        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff' || Auth::user()->user_type == 'franchise' || Auth::user()->user_type == 'sub_franchise' || Auth::guard('franchise_employee')->check()) {
            $rules['commission_percentage'] = 'required|numeric|min:0|max:100';
        }

        if ($request->password) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($request->password) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        if (isset($validated['commission_percentage'])) {
            $vendor->commission_percentage = $validated['commission_percentage'];
        }
        $vendor->shop_name = $request->shop_name;
        $vendor->address = $request->address;
        $vendor->save();

        // Update shop
        $shop = $user->shop;
        if (!$shop) {
            $shop = new \App\Models\Shop();
            $shop->user_id = $user->id;
        }
        $shop->name = $request->shop_name;
        $shop->address = $request->address;
        $shop->slug = \Str::slug($request->shop_name) . '-' . $user->id;
        $shop->save();

        flash(translate('Vendor updated successfully.'))->success();
        
        if (Auth::guard('franchise_employee')->check()) {
            return redirect()->route('franchise.employee.vendors.index');
        }
        if (Auth::user()->user_type == 'franchise' || Auth::user()->user_type == 'sub_franchise' || Auth::user()->user_type == 'state_franchise') {
            return redirect()->route('franchise.vendors.index');
        }
        return redirect()->route('vendors.index');
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
        } elseif (Auth::user()->user_type == 'state_franchise') {
            if (Auth::user()->state_franchise) {
                $query->where('state_franchise_id', Auth::user()->state_franchise->id);
            }
        } elseif (Auth::user()->user_type == 'sub_franchise') {
             if (Auth::user()->sub_franchise) {
                $query->where('sub_franchise_id', Auth::user()->sub_franchise->id);
            }
        }


        $commission_history = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $layout = 'vendors.layouts.app';
        if(Auth::user()->user_type == 'franchise' || Auth::user()->user_type == 'sub_franchise' || Auth::user()->user_type == 'state_franchise'){
            $layout = 'franchise.layouts.app';
        } elseif(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
            $layout = 'backend.layouts.app';
        }

        return view('vendors.commission_history', compact('commission_history', 'layout'));
    }
}
