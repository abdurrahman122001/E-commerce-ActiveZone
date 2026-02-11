<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\FranchiseEmployee;
use App\Models\SubFranchise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FranchiseEmployeeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = FranchiseEmployee::query();

        // Filter based on user type
        if ($user->user_type == 'franchise' && $user->franchise) {
            $franchiseId = $user->franchise->id;
            // Get all sub-franchise IDs under this franchise
            $subFranchiseIds = SubFranchise::where('franchise_id', $franchiseId)->pluck('id')->toArray();
            
            // Show employees created by this franchise or its sub-franchises
            $query->where(function ($q) use ($user, $subFranchiseIds) {
                $q->where('created_by', $user->id)
                  ->orWhereIn('sub_franchise_id', $subFranchiseIds);
            });
        } elseif ($user->user_type == 'sub_franchise' && $user->sub_franchise) {
            $subFranchiseId = $user->sub_franchise->id;
            // Show only employees created by this sub-franchise
            $query->where('sub_franchise_id', $subFranchiseId);
        } else {
            // Regular users see nothing
            $query->where('id', 0);
        }

        $employees = $query->with(['city', 'subFranchise'])->latest()->paginate(15);
        
        return view('backend.franchise.employees.index', compact('employees'));
    }

    public function create()
    {
        $user = auth()->user();
        $cities = City::where('status', 1)->get();
        $subFranchises = collect();

        if ($user->user_type == 'franchise' && $user->franchise) {
            $franchiseId = $user->franchise->id;
            $subFranchises = SubFranchise::where('franchise_id', $franchiseId)
                ->where('status', 'approved')
                ->with('user')
                ->get();
        }

        return view('backend.franchise.employees.create', compact('cities', 'subFranchises'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:franchise_employees,email',
            'mobile' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|max:50',
            'franchise_level' => 'required|in:CITY,SUB',
            'city_id' => 'required|exists:cities,id',
            'sub_franchise_id' => 'nullable|exists:sub_franchises,id',
            'is_active' => 'boolean'
        ]);

        // Set created_by
        $validated['created_by'] = $user->id;
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Validate permissions
        if ($user->user_type == 'sub_franchise') {
            $validated['franchise_level'] = 'SUB';
            if ($user->sub_franchise) {
                $validated['sub_franchise_id'] = $user->sub_franchise->id;
            }
        } elseif ($user->user_type == 'franchise') {
            // Franchise can assign to sub-franchise if provided
            if (!empty($validated['sub_franchise_id'])) {
                $validated['franchise_level'] = 'SUB';
                // Verify sub-franchise belongs to this franchise
                $subFranchise = SubFranchise::where('id', $validated['sub_franchise_id'])
                    ->where('franchise_id', $user->franchise->id)
                    ->first();
                if (!$subFranchise) {
                    return back()->with('error', 'Invalid sub-franchise selection');
                }
            } else {
                $validated['franchise_level'] = 'CITY';
            }
        }

        FranchiseEmployee::create($validated);

        return redirect()->route('franchise.employees.index')
            ->with('success', 'Employee created successfully');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $employee = $this->getAuthorizedEmployee($id, $user);

        if (!$employee) {
            return redirect()->route('franchise.employees.index')
                ->with('error', 'Unauthorized or employee not found');
        }

        $cities = City::where('status', 1)->get();
        $subFranchises = collect();

        if ($user->user_type == 'franchise' && $user->franchise) {
            $franchiseId = $user->franchise->id;
            $subFranchises = SubFranchise::where('franchise_id', $franchiseId)
                ->where('status', 'approved')
                ->with('user')
                ->get();
        }

        return view('backend.franchise.employees.edit', compact('employee', 'cities', 'subFranchises'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $employee = $this->getAuthorizedEmployee($id, $user);

        if (!$employee) {
            return redirect()->route('franchise.employees.index')
                ->with('error', 'Unauthorized or employee not found');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:franchise_employees,email,' . $id,
            'mobile' => 'required|string|max:20',
            'role' => 'required|string|max:50',
            'franchise_level' => 'required|in:CITY,SUB',
            'city_id' => 'required|exists:cities,id',
            'sub_franchise_id' => 'nullable|exists:sub_franchises,id',
            'is_active' => 'boolean',
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        // Update password only if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Validate permissions for level change
        if ($user->user_type == 'sub_franchise') {
            $validated['franchise_level'] = 'SUB';
            $validated['sub_franchise_id'] = $user->sub_franchise->id;
        } elseif ($user->user_type == 'franchise') {
            if (!empty($validated['sub_franchise_id'])) {
                $validated['franchise_level'] = 'SUB';
                $subFranchise = SubFranchise::where('id', $validated['sub_franchise_id'])
                    ->where('franchise_id', $user->franchise->id)
                    ->first();
                if (!$subFranchise) {
                    return back()->with('error', 'Invalid sub-franchise selection');
                }
            } else {
                $validated['franchise_level'] = 'CITY';
                $validated['sub_franchise_id'] = null;
            }
        }

        $employee->update($validated);

        return redirect()->route('franchise.employees.index')
            ->with('success', 'Employee updated successfully');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $employee = $this->getAuthorizedEmployee($id, $user);

        if (!$employee) {
            return redirect()->route('franchise.employees.index')
                ->with('error', 'Unauthorized or employee not found');
        }

        $employee->delete();

        return redirect()->route('franchise.employees.index')
            ->with('success', 'Employee deleted successfully');
    }

    private function getAuthorizedEmployee($id, $user)
    {
        $query = FranchiseEmployee::where('id', $id);

        if ($user->user_type == 'franchise' && $user->franchise) {
            $franchiseId = $user->franchise->id;
            $subFranchiseIds = SubFranchise::where('franchise_id', $franchiseId)->pluck('id')->toArray();
            
            $query->where(function ($q) use ($user, $subFranchiseIds) {
                $q->where('created_by', $user->id)
                  ->orWhereIn('sub_franchise_id', $subFranchiseIds);
            });
        } elseif ($user->user_type == 'sub_franchise' && $user->sub_franchise) {
            $subFranchiseId = $user->sub_franchise->id;
            $query->where('sub_franchise_id', $subFranchiseId);
        } else {
            return null;
        }

        return $query->first();
    }
}
