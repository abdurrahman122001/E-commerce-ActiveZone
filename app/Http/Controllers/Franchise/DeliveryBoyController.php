<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DeliveryBoy;
use Hash;
use Auth;

class DeliveryBoyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = Auth::guard('franchise_employee')->user();
        
        $query = DeliveryBoy::latest();
        
        if ($employee) {
            if ($employee->franchise_id) {
                // If it's a franchise employee, they see all for that franchise
                $query->where('franchise_id', $employee->franchise_id);
            } elseif ($employee->sub_franchise_id) {
                // If sub-franchise employee, only for that sub-franchise
                $query->where('sub_franchise_id', $employee->sub_franchise_id);
            }
        } else {
            if ($user->user_type == 'state_franchise') {
                // State Franchise: See all delivery boys within their state's franchises and sub-franchises
                $franchise_user_ids = \App\Models\Franchise::where('state_franchise_id', $user->state_franchise->id)->pluck('user_id');
                $sub_franchise_user_ids = \App\Models\SubFranchise::where('state_franchise_id', $user->state_franchise->id)->pluck('user_id');
                
                $query->where(function($q) use ($franchise_user_ids, $sub_franchise_user_ids) {
                    $q->whereIn('franchise_id', $franchise_user_ids)
                      ->orWhereIn('sub_franchise_id', $sub_franchise_user_ids);
                });
            } elseif ($user->user_type == 'franchise') {
                // City Franchise: See their own and their sub-franchises' delivery boys
                $sub_franchise_user_ids = \App\Models\SubFranchise::where('franchise_id', $user->franchise->id)->pluck('user_id');
                $query->where(function($q) use ($user, $sub_franchise_user_ids) {
                    $q->where('franchise_id', $user->id)
                      ->orWhereIn('sub_franchise_id', $sub_franchise_user_ids);
                });
            } elseif ($user->user_type == 'sub_franchise') {
                // Sub-Franchise: See only their own
                $query->where('sub_franchise_id', $user->id);
            }
        }
        
        $delivery_boys = $query->paginate(10);
        return view('franchise.delivery_boy.index', compact('delivery_boys'));
    }

    public function create()
    {
        $areas = $this->get_filtered_areas();
        return view('franchise.delivery_boy.create', compact('areas'));
    }

    private function get_filtered_areas()
    {
        $user = auth()->user();
        $employee = Auth::guard('franchise_employee')->user();
        
        if ($employee) {
            if ($employee->franchise_id) {
                $franchise = \App\Models\Franchise::where('user_id', $employee->franchise_id)->first();
                if($franchise) return \App\Models\Area::where('city_id', $franchise->city_id)->get();
            } else {
                $sub_franchise = \App\Models\SubFranchise::where('user_id', $employee->sub_franchise_id)->first();
                if($sub_franchise) return \App\Models\Area::where('city_id', $sub_franchise->city_id)->get();
            }
        } else {
            if ($user->user_type == 'franchise' && $user->franchise) {
                return \App\Models\Area::where('city_id', $user->franchise->city_id)->get();
            } elseif ($user->user_type == 'sub_franchise' && $user->sub_franchise) {
                return \App\Models\Area::where('city_id', $user->sub_franchise->city_id)->get();
            }
        }
        return [];
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->user_type = 'delivery_boy';
        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->save();

        $delivery_boy = new DeliveryBoy();
        $delivery_boy->user_id = $user->id;
        
        if (Auth::guard('franchise_employee')->check()) {
            $employee = Auth::guard('franchise_employee')->user();
            $delivery_boy->franchise_id = $employee->franchise_id;
            $delivery_boy->sub_franchise_id = $employee->sub_franchise_id;
        } else {
            if (Auth::user()->user_type == 'franchise') {
                $delivery_boy->franchise_id = Auth::user()->id;
            } else {
                $delivery_boy->sub_franchise_id = Auth::user()->id;
                if(Auth::user()->sub_franchise && Auth::user()->sub_franchise->franchise) {
                    $delivery_boy->franchise_id = Auth::user()->sub_franchise->franchise->user_id;
                }
            }
        }
        
        $delivery_boy->location = $request->location;
        $delivery_boy->status = 0; // Pending approval by admin
        $delivery_boy->save();

        flash(translate('Delivery Boy has been registered successfully. Waiting for admin approval.'))->success();
        
        if (Auth::guard('franchise_employee')->check()) {
            return redirect()->route('franchise.employee.delivery_boys.index');
        }
        return redirect()->route('franchise.delivery_boys.index');
    }

    public function edit($id)
    {
        $delivery_boy = DeliveryBoy::findOrFail($id);
        $areas = $this->get_filtered_areas();
        return view('franchise.delivery_boy.edit', compact('delivery_boy', 'areas'));
    }

    public function update(Request $request, $id)
    {
        $delivery_boy = DeliveryBoy::findOrFail($id);
        $user = $delivery_boy->user;

        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'phone' => 'required|unique:users,phone,'.$user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if($request->password != null){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $delivery_boy->location = $request->location;
        $delivery_boy->save();

        flash(translate('Delivery Boy has been updated successfully.'))->success();
        
        if (Auth::guard('franchise_employee')->check()) {
            return redirect()->route('franchise.employee.delivery_boys.index');
        }
        return redirect()->route('franchise.delivery_boys.index');
    }
}
