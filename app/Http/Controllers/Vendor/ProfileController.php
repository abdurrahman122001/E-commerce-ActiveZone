<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Artisan;
use App\Models\User;
use App\Models\Vendor;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->first();
        return view('vendors.profile.index', compact('user', 'vendor'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }

        if ($request->has('avatar')) {
            $user->avatar_original = $request->avatar;
        }

        if ($user->save()) {
            $vendor = Vendor::where('user_id', $user->id)->first();
            if ($vendor) {
                $vendor->bank_name = $request->bank_name;
                $vendor->bank_acc_name = $request->bank_acc_name;
                $vendor->bank_acc_no = $request->bank_acc_no;
                $vendor->bank_routing_no = $request->bank_routing_no;
                $vendor->save();
            }

            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            flash(translate('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Something went wrong!'))->error();
        return back();
    }
}
