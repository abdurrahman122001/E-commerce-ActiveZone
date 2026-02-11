<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\Country;

class IndiaStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $india = Country::where('name', 'India')->first();
        if (!$india) {
            return;
        }

        $states = [
            'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 
            'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 
            'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 
            'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 
            'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 
            'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
            'Andaman and Nicobar Islands', 'Chandigarh', 
            'Dadra and Nagar Haveli and Daman and Diu', 'Delhi', 
            'Jammu and Kashmir', 'Ladakh', 'Lakshadweep', 'Puducherry'
        ];

        foreach ($states as $stateName) {
            State::updateOrCreate(
                ['name' => $stateName, 'country_id' => $india->id],
                ['status' => 1]
            );
        }
    }
}
