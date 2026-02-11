<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FranchiseEmployee;
use App\Models\Franchise;
use App\Models\SubFranchise;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FranchiseEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a franchise user
        $franchiseUser = User::where('user_type', 'franchise')->first();
        
        // Get a city
        $city = City::first();

        if (!$franchiseUser || !$city) {
            $this->command->info('No franchise user or city found. Skipping employee seeding.');
            return;
        }

        $franchise = Franchise::where('user_id', $franchiseUser->id)->first();
        
        if (!$franchise) {
            $this->command->info('No franchise record found. Skipping employee seeding.');
            return;
        }

        $subFranchise = SubFranchise::where('franchise_id', $franchise->id)->first();

        // Create CITY level employee
        FranchiseEmployee::create([
            'name' => 'John Smith',
            'email' => 'john.smith@franchise.com',
            'mobile' => '9876543210',
            'password' => Hash::make('password123'),
            'role' => 'Manager',
            'franchise_level' => 'CITY',
            'city_id' => $city->id,
            'sub_franchise_id' => null,
            'is_active' => true,
            'created_by' => $franchiseUser->id,
        ]);

        // Create SUB level employee (if sub-franchise exists)
        if ($subFranchise) {
            FranchiseEmployee::create([
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@franchise.com',
                'mobile' => '9876543211',
                'password' => Hash::make('password123'),
                'role' => 'Sales Executive',
                'franchise_level' => 'SUB',
                'city_id' => $city->id,
                'sub_franchise_id' => $subFranchise->id,
                'is_active' => true,
                'created_by' => $franchiseUser->id,
            ]);

            FranchiseEmployee::create([
                'name' => 'Michael Brown',
                'email' => 'michael.brown@franchise.com',
                'mobile' => '9876543212',
                'password' => Hash::make('password123'),
                'role' => 'Support Staff',
                'franchise_level' => 'SUB',
                'city_id' => $city->id,
                'sub_franchise_id' => $subFranchise->id,
                'is_active' => true,
                'created_by' => $subFranchise->user_id ?? $franchiseUser->id,
            ]);
        }

        // Create another CITY level employee
        FranchiseEmployee::create([
            'name' => 'Emily Davis',
            'email' => 'emily.davis@franchise.com',
            'mobile' => '9876543213',
            'password' => Hash::make('password123'),
            'role' => 'Inventory Manager',
            'franchise_level' => 'CITY',
            'city_id' => $city->id,
            'sub_franchise_id' => null,
            'is_active' => true,
            'created_by' => $franchiseUser->id,
        ]);

        // Create inactive employee
        FranchiseEmployee::create([
            'name' => 'David Wilson',
            'email' => 'david.wilson@franchise.com',
            'mobile' => '9876543214',
            'password' => Hash::make('password123'),
            'role' => 'Cashier',
            'franchise_level' => 'CITY',
            'city_id' => $city->id,
            'sub_franchise_id' => null,
            'is_active' => false,
            'created_by' => $franchiseUser->id,
        ]);

        $this->command->info('Franchise employees seeded successfully!');
    }
}
