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
        if (Auth::guard('franchise_employee')->check()) {
            $employee = Auth::guard('franchise_employee')->user();
            if ($employee->franchise_id) {
                $delivery_boys = DeliveryBoy::where('franchise_id', $employee->franchise_id)->latest()->paginate(10);
            } else {
                $delivery_boys = DeliveryBoy::where('sub_franchise_id', $employee->sub_franchise_id)->latest()->paginate(10);
            }
        } else {
            $user = Auth::user();
            if ($user->user_type == 'franchise') {
                $delivery_boys = DeliveryBoy::where('franchise_id', $user->id)->latest()->paginate(10);
            } else {
                $delivery_boys = DeliveryBoy::where('sub_franchise_id', $user->id)->latest()->paginate(10);
            }
        }
        return view('franchise.delivery_boy.index', compact('delivery_boys'));
    }

    public function create()
    {
        return view('franchise.delivery_boy.create');
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
            }
        }
        
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
        return view('franchise.delivery_boy.edit', compact('delivery_boy'));
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

        flash(translate('Delivery Boy has been updated successfully.'))->success();
        
        if (Auth::guard('franchise_employee')->check()) {
            return redirect()->route('franchise.employee.delivery_boys.index');
        }
        return redirect()->route('franchise.delivery_boys.index');
    }
}
