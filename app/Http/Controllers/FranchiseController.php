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
use Auth;

class FranchiseController extends Controller
{
    // Show Franchise Landing Page
    public function showLandingPage()
    {
        $packages = FranchisePackage::where('status', 1)->get();
        return view('frontend.franchise.landing', compact('packages'));
    }

    // Show Sub-Franchise Landing Page
    public function showSubFranchiseLandingPage()
    {
        $packages = FranchisePackage::where('status', 1)->get();
        return view('frontend.franchise.sub_landing', compact('packages'));
    }

    // Show Registration Form
    public function showRegistrationForm()
    {
        $states = State::where('country_id', 101)->where('status', 1)->get();
        $packages = FranchisePackage::all();
        return view('frontend.franchise.registration', compact('states', 'packages'));
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
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'area_id' => 'required_if:franchise_type,sub_franchise',
            'franchise_package_id' => 'required|exists:franchise_packages,id',
            'id_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->franchise_type == 'city_franchise') {
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
            $user->user_type = $request->franchise_type == 'city_franchise' ? 'franchise' : 'sub_franchise';
            $user->verification_status = 0; // Default to unverified
            $user->save();
            
            // Handle ID Proof Upload
            $id_proof_path = null;
            if ($request->hasFile('id_proof')) {
                $id_proof_path = $request->file('id_proof')->store('uploads/franchise/id_proofs', 'public');
            }

            if ($request->franchise_type == 'city_franchise') {
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

    // Admin: List Franchises
    public function index()
    {
        $franchises = Franchise::with('user', 'city', 'franchise_package')->paginate(15);
        return view('backend.franchise.index', compact('franchises'));
    }
    
    // Admin: List Sub Franchises
    public function indexSub()
    {
        $subFranchises = SubFranchise::with('user', 'city', 'area', 'franchise', 'franchise_package')->paginate(15);
        return view('backend.franchise.sub_index', compact('subFranchises'));
    }
    
    // Admin: Create Franchise Form
    public function createFranchise()
    {
        $states = State::where('country_id', 101)->where('status', 1)->get();
        $packages = FranchisePackage::all();
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
        $packages = FranchisePackage::all();
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

        // Link to Parent Franchise if exists for the city (any status)
        $parentFranchise = Franchise::where('city_id', $request->city_id)->first();
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
             
             if($franchise->user){
                 $franchise->user->verification_status = 1;
                 $franchise->user->save();
             }
         } else {

             $sub = SubFranchise::findOrFail($id);

             if ($sub->status != 'approved') {
                 if ($sub->franchise_id) {
                     $commission_percentage = get_setting('franchise_commission_on_package');
                     if ($commission_percentage > 0 && $sub->franchise_package) {
                         $package_price = $sub->franchise_package->price;
                         $commission_amount = ($package_price * $commission_percentage) / 100;

                         $franchise = Franchise::find($sub->franchise_id);
                         if ($franchise) {
                             $franchise->balance += $commission_amount;
                             $franchise->save();

                             $history = new \App\Models\PackageCommissionHistory();
                             $history->franchise_id = $franchise->id;
                             $history->sub_franchise_id = $sub->id;
                             $history->franchise_package_id = $sub->franchise_package_id;
                             $history->amount = $commission_amount;
                             $history->percentage = $commission_percentage;
                             $history->save();
                         }
                     }
                 }
             }

             $sub->status = 'approved';
             $sub->save();

             if($sub->user){
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
         } else {
             $sub = SubFranchise::findOrFail($id);
             $sub->status = 'rejected';
             $sub->save();
         }
         flash(translate('Rejected Successfully'))->success();
         return back();
    }

    public function editFranchise($id)
    {
        $franchise = Franchise::findOrFail($id);
        $states = State::where('country_id', 101)->where('status', 1)->get();
        $packages = FranchisePackage::all();
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
        $packages = FranchisePackage::all();
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
