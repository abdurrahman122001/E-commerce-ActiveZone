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
use App\Models\FranchisePackage;
use App\Models\State;
use App\Models\Vendor;
use App\Models\FranchiseEmployee;
use App\Models\StateFranchise;
use Auth;

class FranchiseController extends Controller
{
    // Show Franchise Landing Page
    public function showLandingPage()
    {
        $packages = FranchisePackage::where('status', 1)->where('package_type', 'franchise')->get();
        return view('frontend.franchise.landing', compact('packages'));
    }

    // Show Sub-Franchise Landing Page
    public function showSubFranchiseLandingPage()
    {
        $packages = FranchisePackage::where('status', 1)->where('package_type', 'sub_franchise')->get();
        return view('frontend.franchise.sub_landing', compact('packages'));
    }

    // Show Registration Form
    // Show Registration Form
    public function showRegistrationForm(Request $request)
    {
        $states = State::where('country_id', 101)->where('status', 1)->get();
        $type = $request->type;
        // Load packages matching the franchise type
        $packageType = match($type) {
            'state_franchise' => 'state_franchise',
            'sub_franchise' => 'sub_franchise',
            default => 'franchise',
        };
        $packages = FranchisePackage::where('package_type', $packageType)->where('status', 1)->get();
        return view('frontend.franchise.registration', compact('states', 'packages', 'type'));
    }

    public function showStateRegistrationForm()
    {
        $states = State::where('country_id', 101)->where('status', 1)->get();
        $packages = FranchisePackage::where('package_type', 'state_franchise')->where('status', 1)->get();
        $type = 'state_franchise';
        return view('frontend.franchise.registration', compact('states', 'packages', 'type'));
    }

    // Process Registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
            'franchise_type' => 'required|in:state_franchise,city_franchise,sub_franchise',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'nullable|required_if:franchise_type,city_franchise,sub_franchise|exists:cities,id',
            'area_id' => 'nullable|required_if:franchise_type,sub_franchise|exists:areas,id',
            'franchise_package_id' => 'required|exists:franchise_packages,id',
            'id_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->franchise_type == 'state_franchise') {
            $existingStateFranchise = StateFranchise::where('state_id', $request->state_id)->where('status', '!=', 'rejected')->first();
            if ($existingStateFranchise) {
                flash(translate('A franchise already exists for the selected state.'))->error();
                return back()->withInput();
            }
        } elseif ($request->franchise_type == 'city_franchise') {
            $existingFranchise = Franchise::where('city_id', $request->city_id)->where('status', '!=', 'rejected')->first();
            if ($existingFranchise) {
                flash(translate('A franchise already exists for the selected city.'))->error();
                return back()->withInput();
            }
        } elseif ($request->franchise_type == 'sub_franchise') {
            $existingSubFranchise = SubFranchise::where('area_id', $request->area_id)->where('status', '!=', 'rejected')->first();
            if ($existingSubFranchise) {
                flash(translate('A sub-franchise already exists for the selected area.'))->error();
                return back()->withInput();
            }
        }

        \DB::beginTransaction();
        try {
            // Create User
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->user_type = $request->franchise_type == 'state_franchise' ? 'state_franchise' : ($request->franchise_type == 'city_franchise' ? 'franchise' : 'sub_franchise');
            $user->verification_status = 0; // Default to unverified
            $user->save();
            
            // Handle ID Proof Upload
            $id_proof_path = null;
            if ($request->hasFile('id_proof')) {
                $id_proof_path = $request->file('id_proof')->store('uploads/franchise/id_proofs', 'public');
            }

            if ($request->franchise_type == 'state_franchise') {
                $stateFranchise = new StateFranchise();
                $stateFranchise->user_id = $user->id;
                $stateFranchise->state_id = $request->state_id;
                $stateFranchise->franchise_name = $request->name . ' State Franchise';
                $stateFranchise->referral_code = 'STF' . strtoupper(Str::random(8));
                $stateFranchise->business_experience = $request->business_experience;
                $stateFranchise->id_proof = $id_proof_path;
                $stateFranchise->franchise_package_id = $request->franchise_package_id;
                $stateFranchise->status = 'pending';
                $stateFranchise->save();

                // Automatically link existing city and sub franchises in this state
                Franchise::where('state_id', $request->state_id)->update(['state_franchise_id' => $stateFranchise->id]);
                SubFranchise::where('state_id', $request->state_id)->update(['state_franchise_id' => $stateFranchise->id]);

            } elseif ($request->franchise_type == 'city_franchise') {
                $franchise = new Franchise();
                $franchise->user_id = $user->id;
                $franchise->state_id = $request->state_id;
                $franchise->city_id = $request->city_id;
                $franchise->franchise_name = $request->name . ' Franchise';
                $franchise->referral_code = 'CF' . strtoupper(Str::random(8));
                $franchise->business_experience = $request->business_experience;
                $franchise->id_proof = $id_proof_path;
                $franchise->franchise_package_id = $request->franchise_package_id;
                $franchise->status = 'pending';

                // Link to State Franchise if exists
                $stateFranchise = StateFranchise::where('state_id', $request->state_id)->first();
                if ($stateFranchise) {
                    $franchise->state_franchise_id = $stateFranchise->id;
                }

                $franchise->save();
            } else {
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
                
                // Link to Parent Franchise if exists for the city (any status)
                $parentFranchise = Franchise::where('city_id', $request->city_id)->first();
                if ($parentFranchise) {
                    $subFranchise->franchise_id = $parentFranchise->id;
                }

                // Link to State Franchise if exists
                $stateFranchise = StateFranchise::where('state_id', $request->state_id)->first();
                if ($stateFranchise) {
                    $subFranchise->state_franchise_id = $stateFranchise->id;
                }

                $subFranchise->save();
            }

            \DB::commit();
            
            // Auto-login the user and redirect to dashboard
            Auth::login($user);
            flash(translate('Registration successful! Your account is pending admin approval.'))->success();
            return redirect()->route('franchise.dashboard');

        } catch (\Exception $e) {
            \DB::rollback();
            flash(translate('Something went wrong: ') . $e->getMessage())->error();
            return back()->withInput();
        }
    }

    // Admin: List State Franchises
    public function indexState()
    {
        $stateFranchises = StateFranchise::with('user', 'state', 'franchise_package')->paginate(15);
        return view('backend.franchise.state_index', compact('stateFranchises'));
    }

    // Admin: List Franchises (City Franchises)
    public function index()
    {
        $franchises = Franchise::with('user', 'city', 'franchise_package', 'state_franchise')->paginate(15);
        return view('backend.franchise.index', compact('franchises'));
    }
    
    // Admin: List Sub Franchises
    public function indexSub()
    {
        $subFranchises = SubFranchise::with('user', 'city', 'area', 'franchise', 'franchise_package', 'state_franchise')->paginate(15);
        return view('backend.franchise.sub_index', compact('subFranchises'));
    }
    
    // Admin: Create State Franchise Form
    public function createStateFranchise()
    {
        $states = State::where('country_id', 101)->where('status', 1)->get();
        $packages = FranchisePackage::where('package_type', 'state_franchise')->get();
        return view('backend.franchise.state_create', compact('states', 'packages'));
    }

    // Admin: Store State Franchise
    public function storeStateFranchise(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'state_id' => 'required|exists:states,id',
            'franchise_package_id' => 'required|exists:franchise_packages,id',
        ]);

        $existingStateFranchise = StateFranchise::where('state_id', $request->state_id)->where('status', '!=', 'rejected')->first();
        if ($existingStateFranchise) {
            flash(translate('A state franchise already exists for the selected state.'))->error();
            return back()->withInput();
        }

        // Create User
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->user_type = 'state_franchise';
        $user->verification_status = 1;
        $user->save();
        
        // Handle ID Proof Upload
        $id_proof_path = null;
        if ($request->hasFile('id_proof')) {
            $id_proof_path = $request->file('id_proof')->store('uploads/franchise/id_proofs', 'public');
        }

        $stateFranchise = new StateFranchise();
        $stateFranchise->user_id = $user->id;
        $stateFranchise->state_id = $request->state_id;
        $stateFranchise->franchise_name = $request->name;
        $stateFranchise->referral_code = 'SF-' . strtoupper(Str::random(10));
        $stateFranchise->business_experience = $request->business_experience;
        $stateFranchise->id_proof = $id_proof_path;
        $stateFranchise->franchise_package_id = $request->franchise_package_id;
        $stateFranchise->status = 'approved';
        $stateFranchise->save();

        flash(translate('State Franchise created successfully'))->success();
        return redirect()->route('admin.state_franchises.index');
    }

    // Admin: Create Franchise Form
    public function createFranchise()
    {
        $states = State::where('country_id', 101)->where('status', 1)->get();
        $packages = FranchisePackage::where('package_type', 'franchise')->get();
        return view('backend.franchise.create', compact('states', 'packages'));
    }

    // Admin: Store Franchise
    public function storeFranchise(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'franchise_package_id' => 'required|exists:franchise_packages,id',
        ]);

        $existingFranchise = Franchise::where('city_id', $request->city_id)->where('status', '!=', 'rejected')->first();
        if ($existingFranchise) {
            flash(translate('A franchise already exists for the selected city.'))->error();
            return back()->withInput();
        }

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
        $franchise->state_id = $request->state_id;
        $franchise->city_id = $request->city_id;
        
        // Link to State Franchise
        $stateFranchise = StateFranchise::where('state_id', $request->state_id)->where('status', 'approved')->first();
        if ($stateFranchise) {
            $franchise->state_franchise_id = $stateFranchise->id;
        }

        $franchise->franchise_name = $request->name . ' Franchise';
        $franchise->referral_code = Str::random(10);
        $franchise->business_experience = $request->business_experience;
        $franchise->id_proof = $id_proof_path;
        $franchise->franchise_package_id = $request->franchise_package_id;
        $franchise->status = 'approved';
        $franchise->save();

        flash(translate('Franchise created successfully'))->success();
        return redirect()->route('admin.franchises.index');
    }

    // Admin: Create Sub-Franchise Form
    public function createSubFranchise()
    {
        $states = State::where('country_id', 101)->where('status', 1)->get();
        $packages = FranchisePackage::where('package_type', 'sub_franchise')->get();
        return view('backend.franchise.create_sub', compact('states', 'packages'));
    }

    // Admin: Store Sub-Franchise
    public function storeSubFranchise(Request $request) {
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
        $subFranchise->state_id = $request->state_id;
        $subFranchise->city_id = $request->city_id;
        $subFranchise->area_id = $request->area_id;
        $subFranchise->referral_code = Str::random(10);
        $subFranchise->business_experience = $request->business_experience;
        $subFranchise->id_proof = $id_proof_path;
        $subFranchise->franchise_package_id = $request->franchise_package_id;
        $subFranchise->status = 'approved';

        // Link to Parent Franchise if exists for the city
        $parentFranchise = Franchise::where('city_id', $request->city_id)->first();
        if ($parentFranchise) {
            $subFranchise->franchise_id = $parentFranchise->id;
            $subFranchise->state_franchise_id = $parentFranchise->state_franchise_id;
        } else {
            // Link to State Franchise directly if no city franchise
            $stateFranchise = StateFranchise::where('state_id', $request->state_id)->where('status', 'approved')->first();
            if ($stateFranchise) {
                $subFranchise->state_franchise_id = $stateFranchise->id;
            }
        }

        $subFranchise->save();

        flash(translate('Sub-Franchise created successfully'))->success();
        return redirect()->route('admin.sub_franchises.index');
    }

    public function approve($id, $type) {
         if($type == 'franchise') {
             $franchise = Franchise::with('franchise_package')->findOrFail($id);
             $franchise->status = 'approved';

             // ── Resolve State Franchise: FK first, then fallback via state_id ──
             $stateFranchise = null;
             if ($franchise->state_franchise_id) {
                 $stateFranchise = StateFranchise::find($franchise->state_franchise_id);
             }
             if (!$stateFranchise && $franchise->state_id) {
                 $stateFranchise = StateFranchise::where('state_id', $franchise->state_id)
                     ->where('status', 'approved')->first();
             }
             if ($stateFranchise && !$franchise->state_franchise_id) {
                 $franchise->state_franchise_id = $stateFranchise->id;
             }
             $franchise->save();

             // City Franchise package → State Franchise gets commission
             if ($stateFranchise && $franchise->franchise_package) {
                 $commission_percentage = (float) get_setting('state_franchise_commission_on_package');
                 if ($commission_percentage > 0) {
                     $package_price    = $franchise->franchise_package->price;
                     $commission_amount = ($package_price * $commission_percentage) / 100;

                     $stateFranchise->balance = ($stateFranchise->balance ?? 0) + $commission_amount;
                     $stateFranchise->save();

                     \App\Models\PackageCommissionHistory::create([
                         'state_franchise_id'   => $stateFranchise->id,
                         'franchise_id'         => $franchise->id,
                         'franchise_package_id' => $franchise->franchise_package_id,
                         'amount'               => $commission_amount,
                         'percentage'           => $commission_percentage,
                         'type'                 => 'city_to_state',
                         'beneficiary_type'     => 'state_franchise',
                     ]);
                 }
             }

             if ($franchise->user) {
                 $franchise->user->verification_status = 1;
                 $franchise->user->save();
             }
         } elseif ($type == 'state_franchise') {
             $state_franchise = StateFranchise::findOrFail($id);
             $state_franchise->status = 'approved';
             $state_franchise->save();

             // Auto-link any orphaned city/sub franchises in this state
             Franchise::where('state_id', $state_franchise->state_id)
                 ->whereNull('state_franchise_id')
                 ->update(['state_franchise_id' => $state_franchise->id]);
             SubFranchise::where('state_id', $state_franchise->state_id)
                 ->whereNull('state_franchise_id')
                 ->update(['state_franchise_id' => $state_franchise->id]);

             if ($state_franchise->user) {
                 $state_franchise->user->verification_status = 1;
                 $state_franchise->user->save();
             }
         } else {
             // ── Sub-Franchise ─────────────────────────────────────────────────────
             $sub = SubFranchise::with('franchise_package')->findOrFail($id);

             if ($sub->status != 'approved') {

                 // ── Resolve City Franchise: FK first, fallback via city_id ────────
                 $cityFranchise = null;
                 if ($sub->franchise_id) {
                     $cityFranchise = Franchise::find($sub->franchise_id);
                 }
                 if (!$cityFranchise && $sub->city_id) {
                     $cityFranchise = Franchise::where('city_id', $sub->city_id)
                         ->where('status', 'approved')->first();
                 }
                 if ($cityFranchise && !$sub->franchise_id) {
                     $sub->franchise_id = $cityFranchise->id;
                 }

                 // ── Resolve State Franchise: FK → state_id → via city franchise ──
                 $stateFranchise = null;
                 if ($sub->state_franchise_id) {
                     $stateFranchise = StateFranchise::find($sub->state_franchise_id);
                 }
                 if (!$stateFranchise && $sub->state_id) {
                     $stateFranchise = StateFranchise::where('state_id', $sub->state_id)
                         ->where('status', 'approved')->first();
                 }
                 if (!$stateFranchise && $cityFranchise && $cityFranchise->state_franchise_id) {
                     $stateFranchise = StateFranchise::find($cityFranchise->state_franchise_id);
                 }
                 if ($stateFranchise && !$sub->state_franchise_id) {
                     $sub->state_franchise_id = $stateFranchise->id;
                 }

                 // Sub-Franchise package → City Franchise earns commission
                 if ($cityFranchise && $sub->franchise_package) {
                     $cf_pct = (float) get_setting('franchise_commission_on_package');
                     if ($cf_pct > 0) {
                         $package_price     = $sub->franchise_package->price;
                         $commission_amount = ($package_price * $cf_pct) / 100;

                         $cityFranchise->balance = ($cityFranchise->balance ?? 0) + $commission_amount;
                         $cityFranchise->save();

                         \App\Models\PackageCommissionHistory::create([
                             'franchise_id'         => $cityFranchise->id,
                             'sub_franchise_id'     => $sub->id,
                             'state_franchise_id'   => $stateFranchise ? $stateFranchise->id : null,
                             'franchise_package_id' => $sub->franchise_package_id,
                             'amount'               => $commission_amount,
                             'percentage'           => $cf_pct,
                             'type'                 => 'sub_to_city',
                             'beneficiary_type'     => 'franchise',
                         ]);
                     }
                 }

                 // Sub-Franchise package → State Franchise earns commission
                 if ($stateFranchise && $sub->franchise_package) {
                     $stf_pct = (float) get_setting('state_franchise_commission_on_package');
                     if ($stf_pct > 0) {
                         $package_price     = $sub->franchise_package->price;
                         $commission_amount = ($package_price * $stf_pct) / 100;

                         $stateFranchise->balance = ($stateFranchise->balance ?? 0) + $commission_amount;
                         $stateFranchise->save();

                         \App\Models\PackageCommissionHistory::create([
                             'state_franchise_id'   => $stateFranchise->id,
                             'franchise_id'         => $cityFranchise ? $cityFranchise->id : null,
                             'sub_franchise_id'     => $sub->id,
                             'franchise_package_id' => $sub->franchise_package_id,
                             'amount'               => $commission_amount,
                             'percentage'           => $stf_pct,
                             'type'                 => 'sub_to_state',
                             'beneficiary_type'     => 'state_franchise',
                         ]);
                     }
                 }
             }

             $sub->status = 'approved';
             $sub->save();

             if ($sub->user) {
                 $sub->user->verification_status = 1;
                 $sub->user->save();
             }
         }
         flash(translate('Approved Successfully'))->success();
         return back();
    }
    
    public function reject($id, $type) {
         if($type == 'franchise') {
             $franchise = Franchise::findOrFail($id);
             $franchise->status = 'rejected';
             $franchise->save();
         } elseif($type == 'state_franchise') {
             $state_franchise = StateFranchise::findOrFail($id);
             $state_franchise->status = 'rejected';
             $state_franchise->save();
         } else {
             $sub = SubFranchise::findOrFail($id);
             $sub->status = 'rejected';
             $sub->save();
         }
         flash(translate('Rejected Successfully'))->success();
         return back();
    }

    public function editStateFranchise($id)
    {
        $stateFranchise = StateFranchise::findOrFail($id);
        $states = State::where('country_id', 101)->where('status', 1)->get();
        $packages = FranchisePackage::where('package_type', 'state_franchise')->get();
        return view('backend.franchise.state_edit', compact('stateFranchise', 'states', 'packages'));
    }

    public function updateStateFranchise(Request $request, $id)
    {
        $stateFranchise = StateFranchise::findOrFail($id);

        if ($stateFranchise->state_id != $request->state_id) {
            $existingStateFranchise = StateFranchise::where('state_id', $request->state_id)->where('status', '!=', 'rejected')->first();
            if ($existingStateFranchise) {
                flash(translate('A state franchise already exists for the selected state.'))->error();
                return back()->withInput();
            }
        }

        $stateFranchise->franchise_name = $request->name;
        $stateFranchise->business_experience = $request->business_experience;
        $stateFranchise->state_id = $request->state_id;
        $stateFranchise->franchise_package_id = $request->franchise_package_id;
        $stateFranchise->invalid_at = $request->invalid_at;
        $stateFranchise->commission_percentage = $request->commission_percentage ?? 0;
        
        if ($request->hasFile('id_proof')) {
            $stateFranchise->id_proof = $request->file('id_proof')->store('uploads/franchise/id_proofs', 'public');
        }

        $stateFranchise->bank_name = $request->bank_name;
        $stateFranchise->bank_acc_name = $request->bank_acc_name;
        $stateFranchise->bank_acc_no = $request->bank_acc_no;
        $stateFranchise->bank_routing_no = $request->bank_routing_no;
        
        $stateFranchise->save();

        $user = $stateFranchise->user;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        flash(translate('State Franchise updated successfully'))->success();
        return redirect()->route('admin.state_franchises.index');
    }

    public function destroyStateFranchise($id)
    {
        $stateFranchise = StateFranchise::findOrFail($id);
        $user = $stateFranchise->user;
        $stateFranchise->delete();
        if($user){
            $user->delete();
        }
        flash(translate('State Franchise deleted successfully'))->success();
        return back();
    }

    public function editFranchise($id)
    {
        $franchise = Franchise::findOrFail($id);
        $states = State::where('country_id', 101)->where('status', 1)->get();
        $packages = FranchisePackage::where('package_type', 'franchise')->get();
        return view('backend.franchise.edit', compact('franchise', 'states', 'packages'));
    }

    public function updateFranchise(Request $request, $id)
    {
        $franchise = Franchise::findOrFail($id);

        if ($franchise->city_id != $request->city_id) {
            $existingFranchise = Franchise::where('city_id', $request->city_id)->where('status', '!=', 'rejected')->first();
            if ($existingFranchise) {
                flash(translate('A franchise already exists for the selected city.'))->error();
                return back()->withInput();
            }
        }

        $franchise->franchise_name = $request->name . ' Franchise';
        $franchise->business_experience = $request->business_experience;
        $franchise->state_id = $request->state_id;
        $franchise->city_id = $request->city_id;
        $franchise->franchise_package_id = $request->franchise_package_id;
        $franchise->invalid_at = $request->invalid_at;
        $franchise->commission_percentage = $request->commission_percentage ?? 0;
        
        if ($request->hasFile('id_proof')) {
            $franchise->id_proof = $request->file('id_proof')->store('uploads/franchise/id_proofs', 'public');
        }

        $franchise->bank_name = $request->bank_name;
        $franchise->bank_acc_name = $request->bank_acc_name;
        $franchise->bank_acc_no = $request->bank_acc_no;
        $franchise->bank_routing_no = $request->bank_routing_no;
        
        $franchise->save();

        $user = $franchise->user;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        flash(translate('Franchise updated successfully'))->success();
        return redirect()->route('admin.franchises.index');
    }

    public function destroyFranchise($id)
    {
        $franchise = Franchise::findOrFail($id);
        $user = $franchise->user;
        $franchise->delete();
        if($user){
            $user->delete();
        }
        flash(translate('Franchise deleted successfully'))->success();
        return back();
    }

    public function editSubFranchise($id)
    {
        $subFranchise = SubFranchise::findOrFail($id);
        $states = State::where('country_id', 101)->where('status', 1)->get();
        $packages = FranchisePackage::where('package_type', 'sub_franchise')->get();
        $franchises = Franchise::all();
        return view('backend.franchise.edit_sub', compact('subFranchise', 'states', 'packages', 'franchises'));
    }

    public function updateSubFranchise(Request $request, $id)
    {
        $subFranchise = SubFranchise::findOrFail($id);

        if ($subFranchise->area_id != $request->area_id) {
            $existingSubFranchise = SubFranchise::where('area_id', $request->area_id)->where('status', '!=', 'rejected')->first();
            if ($existingSubFranchise) {
                flash(translate('A sub-franchise already exists for the selected area.'))->error();
                return back()->withInput();
            }
        }

        $subFranchise->business_experience = $request->business_experience;
        $subFranchise->state_id = $request->state_id;
        $subFranchise->city_id = $request->city_id;
        $subFranchise->area_id = $request->area_id;
        $subFranchise->franchise_package_id = $request->franchise_package_id;
        $subFranchise->franchise_id = $request->franchise_id;
        $subFranchise->invalid_at = $request->invalid_at;
        $subFranchise->commission_percentage = $request->commission_percentage ?? 0;

        if ($request->hasFile('id_proof')) {
            $subFranchise->id_proof = $request->file('id_proof')->store('uploads/franchise/id_proofs', 'public');
        }

        $subFranchise->bank_name = $request->bank_name;
        $subFranchise->bank_acc_name = $request->bank_acc_name;
        $subFranchise->bank_acc_no = $request->bank_acc_no;
        $subFranchise->bank_routing_no = $request->bank_routing_no;

        $subFranchise->save();

        $user = $subFranchise->user;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        flash(translate('Sub-Franchise updated successfully'))->success();
        return redirect()->route('admin.sub_franchises.index');
    }

    public function destroySubFranchise($id)
    {
        $subFranchise = SubFranchise::findOrFail($id);
        $user = $subFranchise->user;
        $subFranchise->delete();
        if($user){
            $user->delete();
        }
        flash(translate('Sub-Franchise deleted successfully'))->success();
        return back();
    }

    public function login($id)
    {
        try {
            $user_id = decrypt($id);
        } catch (\Exception $e) {
            $user_id = $id;
        }
        $user = User::findOrFail($user_id);
        Auth::login($user, true);
        return redirect()->route('franchise.dashboard');
    }

    public function ban($id)
    {
        $user = User::findOrFail($id);
        if($user->banned == 1) {
            $user->banned = 0;
            flash(translate('User Unbanned Successfully'))->success();
        } else {
            $user->banned = 1;
            flash(translate('User Banned Successfully'))->success();
        }
        $user->save();
        return back();
    }

    public function suspicious($id)
    {
        $user = User::findOrFail(decrypt($id));
        if($user->is_suspicious == 1) {
            $user->is_suspicious = 0;
            flash(translate('User marked as unsuspicious'))->success();
        } else {
            $user->is_suspicious = 1;
            flash(translate('User marked as suspicious'))->success();
        }
        $user->save();
        return back();
    }

    public function profile($id)
    {
        $user_id = decrypt($id);
        $user = User::findOrFail($user_id);
        
        $subFranchises = collect();
        $franchise_vendors = collect();
        $sub_franchise_vendors = collect();
        $franchise_employees = collect();
        $sub_franchise_employees = collect();
        $delivery_boys = collect();

        if($user->user_type == 'franchise') {
            $franchise = $user->franchise;
            if($franchise) {
                $subFranchises = SubFranchise::where('franchise_id', $franchise->id)->get();
                $franchise_vendors = Vendor::where('franchise_id', $franchise->id)->whereNull('sub_franchise_id')->get();
                $sub_franchise_vendors = Vendor::where('franchise_id', $franchise->id)->whereNotNull('sub_franchise_id')->get();
                $franchise_employees = FranchiseEmployee::where('franchise_id', $franchise->id)->whereNull('sub_franchise_id')->get();
                $sub_franchise_employees = FranchiseEmployee::where('franchise_id', $franchise->id)->whereNotNull('sub_franchise_id')->get();
                $delivery_boys = \App\Models\DeliveryBoy::where('franchise_id', $user->id)->get();
            }
        } elseif ($user->user_type == 'sub_franchise') {
             $subFranchise = $user->sub_franchise;
             if($subFranchise) {
                 $sub_franchise_vendors = Vendor::where('sub_franchise_id', $subFranchise->id)->get();
                 $sub_franchise_employees = FranchiseEmployee::where('sub_franchise_id', $subFranchise->id)->get();
                 $delivery_boys = \App\Models\DeliveryBoy::where('sub_franchise_id', $user->id)->get();
             }
        }

        return view('backend.franchise.profile', compact(
            'user', 
            'subFranchises', 
            'franchise_vendors', 
            'sub_franchise_vendors', 
            'franchise_employees', 
            'sub_franchise_employees', 
            'delivery_boys'
        ));
    }

    public function payment_modal(Request $request)
    {
        $user = User::findOrFail($request->id);
        return view('backend.franchise.payment_modal', compact('user'));
    }

    public function payment_history($id)
    {
        $user_id = decrypt($id);
        // Assuming there's a Payment model or similar to track payments to franchises
        // If not, this might need adjustment. Using 'Payment' model from SellerController context
        $payments = \App\Models\Payment::where('seller_id', $user_id)->paginate(15); 
        return view('backend.franchise.payment_history', compact('payments', 'user_id'));
    }

    public function payment_store(Request $request)
    {
         $user = User::findOrFail($request->user_id);
         $amount = $request->amount;
         
         if($user->franchise) {
             $franchise = $user->franchise;
             $franchise->balance -= $amount;
             $franchise->save();
         } elseif($user->sub_franchise) {
             $sub = $user->sub_franchise;
             $sub->balance -= $amount;
             $sub->save();
         }

         $payment = new \App\Models\Payment;
         $payment->seller_id = $user->id; // Using seller_id column as user_id for now
         $payment->amount = $amount;
         $payment->payment_method = $request->payment_option;
         if($request->has('txn_code')) {
            $payment->txn_code = $request->txn_code;
         }
         $payment->payment_details = null;
         $payment->save();

         flash(translate('Payment completed'))->success();
         return back();
    }

    public function updateVerificationInfo(Request $request)
    {
        $request->validate([
            'id_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'pan_number' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $id_proof_path = null;
        if ($request->hasFile('id_proof')) {
            $id_proof_path = $request->file('id_proof')->store('uploads/franchise/id_proofs', 'public');
        }

        $saved = false;
        if ($user->user_type == 'franchise') {
            $franchise = Franchise::where('user_id', $user->id)->first();
            if ($franchise) {
                if ($id_proof_path) {
                    $franchise->id_proof = $id_proof_path;
                }
                $franchise->pan_number = $request->pan_number;
                $franchise->save();
                $saved = true;
            }
        } elseif ($user->user_type == 'sub_franchise') {
            $sub = SubFranchise::where('user_id', $user->id)->first();
            if ($sub) {
                if ($id_proof_path) {
                    $sub->id_proof = $id_proof_path;
                }
                $sub->pan_number = $request->pan_number;
                $sub->save();
                $saved = true;
            }
        }

        if ($saved) {
            flash(translate('Verification details uploaded successfully. Please wait for admin approval.'))->success();
        } else {
            flash(translate('User record not found.'))->error();
        }
        
        return back();
    }

    public function setSubFranchiseCommission(Request $request)
    {
        $sub = SubFranchise::findOrFail($request->id);
        $sub->commission_percentage = $request->commission_percentage;
        $sub->save();

        flash(translate('Commission updated successfully'))->success();
        return back();
    }

    public function setFranchiseCommission(Request $request)
    {
        $franchise = Franchise::findOrFail($request->id);
        $franchise->commission_percentage = $request->commission_percentage;
        $franchise->save();

        flash(translate('Commission updated successfully'))->success();
        return back();
    }
}
