<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Franchise;
use App\Models\SubFranchise;
use App\Models\User;
use App\Models\City;
use App\Models\Area;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Auth;

class FranchiseController extends Controller
{
    // Show Registration Form
    public function showRegistrationForm()
    {
        $cities = City::where('status', 1)->get();
        return view('frontend.franchise.registration', compact('cities'));
    }

    // Process Registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
            'franchise_type' => 'required|in:city_franchise,sub_franchise',
            'city_id' => 'required|exists:cities,id',
            'area_id' => 'required_if:franchise_type,sub_franchise',
            'investment_capacity' => 'required|numeric',
            'id_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        \DB::beginTransaction();
        try {
            // Create User
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->user_type = $request->franchise_type == 'city_franchise' ? 'franchise' : 'sub_franchise';
            $user->verification_status = 1;
            $user->save();
            
            // Handle ID Proof Upload
            $id_proof_path = null;
            if ($request->hasFile('id_proof')) {
                $id_proof_path = $request->file('id_proof')->store('uploads/franchise/id_proofs', 'public');
            }

            if ($request->franchise_type == 'city_franchise') {
                $franchise = new Franchise();
                $franchise->user_id = $user->id;
                $franchise->city_id = $request->city_id;
                $franchise->franchise_name = $request->name . ' Franchise';
                $franchise->referral_code = 'CF' . strtoupper(Str::random(8));
                $franchise->investment_capacity = $request->investment_capacity;
                $franchise->business_experience = $request->business_experience;
                $franchise->id_proof = $id_proof_path;
                $franchise->save();
            } else {
                $subFranchise = new SubFranchise();
                $subFranchise->user_id = $user->id;
                $subFranchise->city_id = $request->city_id;
                $subFranchise->area_id = $request->area_id;
                $subFranchise->referral_code = 'SF' . strtoupper(Str::random(8));
                $subFranchise->investment_capacity = $request->investment_capacity;
                $subFranchise->business_experience = $request->business_experience;
                $subFranchise->id_proof = $id_proof_path;
                
                // Link to Parent Franchise if exists for the city
                $parentFranchise = Franchise::where('city_id', $request->city_id)->where('status', 'approved')->first();
                if ($parentFranchise) {
                    $subFranchise->franchise_id = $parentFranchise->id;
                }

                $subFranchise->save();
            }

            \DB::commit();
            flash(translate('Application submitted successfully! Please wait for approval.'))->success();
            return redirect()->route('home');

        } catch (\Exception $e) {
            \DB::rollback();
            flash(translate('Something went wrong: ') . $e->getMessage())->error();
            return back()->withInput();
        }
    }

    // Admin: List Franchises
    public function index()
    {
        $franchises = Franchise::with('user', 'city')->paginate(15);
        return view('backend.franchise.index', compact('franchises'));
    }
    
    // Admin: List Sub Franchises
    public function indexSub()
    {
        $subFranchises = SubFranchise::with('user', 'city', 'area', 'franchise')->paginate(15);
        return view('backend.franchise.sub_index', compact('subFranchises'));
    }
    
    // Admin: Create Franchise Form
    public function createFranchise()
    {
        $cities = City::where('status', 1)->get();
        return view('backend.franchise.create', compact('cities'));
    }

    // Admin: Store Franchise
    public function storeFranchise(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'city_id' => 'required|exists:cities,id',
            'investment_capacity' => 'required|numeric',
        ]);

        // Create User
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->user_type = 'franchise';
        $user->verification_status = 1;
        $user->save();
        
        // Handle ID Proof Upload
        $id_proof_path = null;
        if ($request->hasFile('id_proof')) {
            $id_proof_path = $request->file('id_proof')->store('uploads/franchise/id_proofs', 'public');
        }

        $franchise = new Franchise();
        $franchise->user_id = $user->id;
        $franchise->city_id = $request->city_id;
        $franchise->franchise_name = $request->name . ' Franchise';
        $franchise->referral_code = Str::random(10);
        $franchise->investment_capacity = $request->investment_capacity;
        $franchise->business_experience = $request->business_experience;
        $franchise->id_proof = $id_proof_path;
        $franchise->status = 'approved';
        $franchise->save();

        flash(translate('Franchise created successfully'))->success();
        return redirect()->route('admin.franchises.index');
    }

    // Admin: Create Sub-Franchise Form
    public function createSubFranchise()
    {
        $cities = City::where('status', 1)->get();
        return view('backend.franchise.create_sub', compact('cities'));
    }

    // Admin: Store Sub-Franchise
    public function storeSubFranchise(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'city_id' => 'required|exists:cities,id',
            'area_id' => 'required|exists:areas,id',
            'investment_capacity' => 'required|numeric',
        ]);

         // Create User
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->user_type = 'sub_franchise';
        $user->verification_status = 1;
        $user->save();
        
        // Handle ID Proof Upload
        $id_proof_path = null;
        if ($request->hasFile('id_proof')) {
            $id_proof_path = $request->file('id_proof')->store('uploads/franchise/id_proofs', 'public');
        }

        $subFranchise = new SubFranchise();
        $subFranchise->user_id = $user->id;
        $subFranchise->city_id = $request->city_id;
        $subFranchise->area_id = $request->area_id;
        $subFranchise->referral_code = Str::random(10);
        $subFranchise->investment_capacity = $request->investment_capacity;
        $subFranchise->business_experience = $request->business_experience;
        $subFranchise->id_proof = $id_proof_path;
        $subFranchise->status = 'approved';

        // Link to Parent Franchise if exists for the city
        $parentFranchise = Franchise::where('city_id', $request->city_id)->where('status', 'approved')->first();
        if ($parentFranchise) {
            $subFranchise->franchise_id = $parentFranchise->id;
        }

        $subFranchise->save();

        flash(translate('Sub-Franchise created successfully'))->success();
        return redirect()->route('admin.sub_franchises.index');
    }

    public function approve($id, $type) {
         if($type == 'franchise') {
             $franchise = Franchise::findOrFail($id);
             $franchise->status = 'approved';
             $franchise->save();
         } else {
             $sub = SubFranchise::findOrFail($id);
             $sub->status = 'approved';
             $sub->save();
         }
         flash(translate('Approved Successfully'))->success();
         return back();
    }
    
     public function reject($id, $type) {
         if($type == 'franchise') {
             $franchise = Franchise::findOrFail($id);
             $franchise->status = 'rejected';
             $franchise->save();
         } else {
             $sub = SubFranchise::findOrFail($id);
             $sub->status = 'rejected';
             $sub->save();
         }
         flash(translate('Rejected Successfully'))->success();
         return back();
    }

}
