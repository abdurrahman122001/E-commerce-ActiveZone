<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Franchise;
use App\Models\User;
use App\Models\City;
use App\Models\FranchisePackage;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CityFranchiseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->user_type != 'state_franchise') {
            flash(translate('Access denied.'))->error();
            return back();
        }

        $stateFranchise = $user->state_franchise;
        if (!$stateFranchise) {
            flash(translate('State Franchise record not found.'))->error();
            return back();
        }

        $cityFranchises = Franchise::where('state_franchise_id', $stateFranchise->id)
            ->with('user', 'city', 'franchise_package')
            ->paginate(15);

        return view('franchise.city_franchise.index', compact('cityFranchises'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->user_type != 'state_franchise') {
            flash(translate('Access denied.'))->error();
            return back();
        }

        $stateFranchise = $user->state_franchise;
        $states = \App\Models\State::where('id', $stateFranchise->state_id)->get();
        $packages = FranchisePackage::where('package_type', 'franchise')->get();
        
        return view('franchise.city_franchise.create', compact('states', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'city_id' => 'required|exists:cities,id',
            'franchise_package_id' => 'required|exists:franchise_packages,id',
        ]);

        $existingFranchise = Franchise::where('city_id', $request->city_id)->where('status', '!=', 'rejected')->first();
        if ($existingFranchise) {
            flash(translate('A city franchise already exists for the selected city.'))->error();
            return back()->withInput();
        }

        $stateFranchise = Auth::user()->state_franchise;

        \DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->user_type = 'franchise';
            $user->verification_status = 0;
            $user->save();
            
            $id_proof_path = null;
            if ($request->hasFile('id_proof')) {
                $id_proof_path = $request->file('id_proof')->store('uploads/franchise/id_proofs', 'public');
            }

            $franchise = new Franchise();
            $franchise->user_id = $user->id;
            $franchise->state_id = $stateFranchise->state_id;
            $franchise->city_id = $request->city_id;
            $franchise->referral_code = 'CF' . strtoupper(Str::random(8));
            $franchise->business_experience = $request->business_experience;
            $franchise->id_proof = $id_proof_path;
            $franchise->franchise_package_id = $request->franchise_package_id;
            $franchise->status = 'pending';
            $franchise->state_franchise_id = $stateFranchise->id;
            $franchise->save();

            \DB::commit();
            flash(translate('City Franchise created successfully. It is now pending for admin approval.'))->success();
            return redirect()->route('franchise.city_franchises.index');

        } catch (\Exception $e) {
            \DB::rollback();
            flash(translate('Something went wrong: ') . $e->getMessage())->error();
            return back()->withInput();
        }
    }

    public function login($id)
    {
        try {
            $f_id = decrypt($id);
        } catch (\Exception $e) {
            $f_id = $id;
        }
        $franchise = Franchise::findOrFail($f_id);
        if ($franchise->state_franchise_id != Auth::user()->state_franchise->id) {
            flash(translate('Access denied.'))->error();
            return back();
        }
        
        $user = $franchise->user;
        if ($user) {
            Auth::login($user, true);
            return redirect()->route('franchise.dashboard');
        }
        
        flash(translate('User not found.'))->error();
        return back();
    }
}
