<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\User;
use Artisan;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('franchise.profile.index', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }

        if ($request->has('avatar')) {
            $user->avatar_original = $request->avatar;
        }

        if ($user->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            flash(translate('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Something went wrong!'))->error();
        return back();
    }
}
