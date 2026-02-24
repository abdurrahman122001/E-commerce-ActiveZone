<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubFranchise;
use App\Models\User;
use App\Models\City;
use App\Models\Area;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\FranchisePackage;
use Auth;

class SubFranchiseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->user_type == 'franchise') {
            $franchise = $user->franchise;
            if (!$franchise) {
                flash(translate('Franchise record not found.'))->error();
                return back();
            }
            $subFranchises = SubFranchise::where('franchise_id', $franchise->id)->with('user', 'city', 'area', 'franchise_package')->paginate(15);
        } elseif ($user->user_type == 'state_franchise') {
            $stateFranchise = $user->state_franchise;
            if (!$stateFranchise) {
                flash(translate('State Franchise record not found.'))->error();
                return back();
            }
            $subFranchises = SubFranchise::where('state_franchise_id', $stateFranchise->id)->with('user', 'city', 'area', 'franchise_package')->paginate(15);
        } else {
            flash(translate('Access denied.'))->error();
            return back();
        }
        
        return view('franchise.sub_franchise.index', compact('subFranchises'));
    }

    public function create()
    {
        $user = Auth::user();
        $states = [];
        if ($user->user_type == 'franchise' && $user->franchise) {
            $states = \App\Models\State::where('id', $user->franchise->state_id)->get();
        } elseif ($user->user_type == 'state_franchise' && $user->state_franchise) {
            $states = \App\Models\State::where('id', $user->state_franchise->state_id)->get();
        } else {
            flash(translate('Access denied.'))->error();
            return back();
        }

        $packages = FranchisePackage::all();
        return view('franchise.sub_franchise.create', compact('states', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'area_id' => 'required|exists:areas,id',
            'franchise_package_id' => 'required|exists:franchise_packages,id',
        ]);

        $existingSubFranchise = SubFranchise::where('area_id', $request->area_id)->where('status', '!=', 'rejected')->first();
        if ($existingSubFranchise) {
            flash(translate('A sub-franchise already exists for the selected area.'))->error();
            return back()->withInput();
        }

        $authUser = Auth::user();
        $franchise_id = null;
        $state_franchise_id = null;

        if ($authUser->user_type == 'franchise') {
            $franchise_id = $authUser->franchise->id;
            $state_franchise_id = $authUser->franchise->state_franchise_id;
        } elseif ($authUser->user_type == 'state_franchise') {
            $state_franchise_id = $authUser->state_franchise->id;
        }

        \DB::beginTransaction();
        try {
            // Create User
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->user_type = 'sub_franchise';
            $user->verification_status = 0;
            $user->save();
            
            // Handle ID Proof Upload
            $id_proof_path = null;
            if ($request->hasFile('id_proof')) {
                $id_proof_path = $request->file('id_proof')->store('uploads/franchise/id_proofs', 'public');
            }

            $subFranchise = new SubFranchise();
            $subFranchise->user_id = $user->id;
            $subFranchise->state_id = $request->state_id;
            $subFranchise->city_id = $request->city_id;
            $subFranchise->area_id = $request->area_id;
            $subFranchise->referral_code = 'SF' . strtoupper(Str::random(8));
            $subFranchise->business_experience = $request->business_experience;
            $subFranchise->id_proof = $id_proof_path;
            $subFranchise->franchise_package_id = $request->franchise_package_id;
            $subFranchise->status = 'pending';
            $subFranchise->franchise_id = $franchise_id;
            $subFranchise->state_franchise_id = $state_franchise_id;
            $subFranchise->save();

            \DB::commit();
            flash(translate('Sub-Franchise created successfully. It is now pending for admin approval.'))->success();
            return redirect()->route('franchise.sub_franchises.index');

        } catch (\Exception $e) {
            \DB::rollback();
            flash(translate('Something went wrong: ') . $e->getMessage())->error();
            return back()->withInput();
        }
    }

    public function set_commission(Request $request)
    {
        $sub = SubFranchise::findOrFail($request->id);
        $sub->commission_percentage = $request->commission_percentage;
        $sub->save();

        flash(translate('Commission updated successfully'))->success();
        return back();
    }

    public function login($id)
    {
        try {
            $sub_id = decrypt($id);
        } catch (\Exception $e) {
            $sub_id = $id;
        }
        $subFranchise = SubFranchise::findOrFail($sub_id);
        if (Auth::user()->user_type == 'state_franchise') {
            if ($subFranchise->state_franchise_id != Auth::user()->state_franchise->id) {
                flash(translate('Access denied.'))->error();
                return back();
            }
        } elseif (Auth::user()->user_type == 'franchise') {
            if ($subFranchise->franchise_id != Auth::user()->franchise->id) {
                flash(translate('Access denied.'))->error();
                return back();
            }
        } else {
            flash(translate('Access denied.'))->error();
            return back();
        }
        
        $user = $subFranchise->user;
        if ($user) {
            Auth::login($user, true);
            return redirect()->route('franchise.dashboard');
        }
        
        flash(translate('User not found.'))->error();
        return back();
    }
}
