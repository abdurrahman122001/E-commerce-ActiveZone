<?php

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\CityTranslation;

class IndianCitiesSeeder extends Seeder
{
    public function run()
    {
        // 1. Ensure Country "India" exists
        $country = Country::where('name', 'India')->first();
        if (!$country) {
            $country = new Country();
            $country->name = 'India';
            $country->code = 'IN';
            $country->status = 1;
            $country->save();
        } else {
            // Ensure status is 1
            $country->status = 1;
            $country->save();
        }

        $indiaId = $country->id;

        // 2. States and Cities Data
        $statesData = [
            'Andhra Pradesh' => [
                'Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Kurnool', 'Rajahmundry', 'Tirupati', 'Kakinada', 'Kadapa', 'Anantapur'
            ],
            'Arunachal Pradesh' => [
                'Itanagar', 'Tawang', 'Pasighat', 'Ziro', 'Bomdila'
            ],
            'Assam' => [
                'Guwahati', 'Silchar', 'Dibrugarh', 'Jorhat', 'Nagaon', 'Tinsukia', 'Tezpur'
            ],
            'Bihar' => [
                'Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur', 'Purnia', 'Darbhanga', 'Bihar Sharif', 'Arrah', 'Begusarai', 'Katihar'
            ],
            'Chhattisgarh' => [
                'Raipur', 'Bhilai', 'Bilaspur', 'Korba', 'Durg', 'Rajnandgaon', 'Raigarh', 'Jagdalpur'
            ],
            'Goa' => [
                'Panaji', 'Margao', 'Vasco da Gama', 'Mapusa', 'Ponda'
            ],
            'Gujarat' => [
                'Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar', 'Jamnagar', 'Junagadh', 'Gandhinagar', 'Gandhidham', 'Anand'
            ],
            'Haryana' => [
                'Faridabad', 'Gurgaon', 'Panipat', 'Ambala', 'Yamunanagar', 'Rohtak', 'Hisar', 'Karnal', 'Sonipat', 'Panchkula'
            ],
            'Himachal Pradesh' => [
                'Shimla', 'Dharamshala', 'Mandi', 'Solan', 'Una', 'Nahan', 'Bilaspur', 'Hamirpur'
            ],
            'Jharkhand' => [
                'Jamshedpur', 'Dhanbad', 'Ranchi', 'Bokaro Steel City', 'Deoghar', 'Phusro', 'Hazaribagh', 'Giridih'
            ],
            'Karnataka' => [
                'Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum', 'Gulbarga', 'Davangere', 'Bellary', 'Bijapur', 'Shimoga'
            ],
            'Kerala' => [
                'Thiruvananthapuram', 'Kochi', 'Kozhikode', 'Kollam', 'Thrissur', 'Palakkad', 'Alappuzha', 'Kannur', 'Kottayam'
            ],
            'Madhya Pradesh' => [
                'Indore', 'Bhopal', 'Jabalpur', 'Gwalior', 'Ujjain', 'Sagar', 'Dewas', 'Satna', 'Ratlam', 'Rewa'
            ],
            'Maharashtra' => [
                'Mumbai', 'Pune', 'Nagpur', 'Thane', 'Nashik', 'Kalyan-Dombivali', 'Vasai-Virar', 'Aurangabad', 'Navi Mumbai', 'Solapur', 'Mira-Bhayandar', 'Bhiwandi', 'Amravati', 'Nanded', 'Kolhapur'
            ],
            'Manipur' => [
                'Imphal', 'Thoubal', 'Kakching', 'Ukhrul'
            ],
            'Meghalaya' => [
                'Shillong', 'Tura', 'Jowai'
            ],
            'Mizoram' => [
                'Aizawl', 'Lunglei', 'Saiha'
            ],
            'Nagaland' => [
                'Dimapur', 'Kohima', 'Mokokchung'
            ],
            'Odisha' => [
                'Bhubaneswar', 'Cuttack', 'Rourkela', 'Berhampur', 'Sambalpur', 'Puri', 'Balasore', 'Bhadrak', 'Baripada'
            ],
            'Punjab' => [
                'Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala', 'Bathinda', 'Hoshiarpur', 'Mohali', 'Batala', 'Pathankot', 'Moga'
            ],
            'Rajasthan' => [
                'Jaipur', 'Jodhpur', 'Kota', 'Bikaner', 'Ajmer', 'Udaipur', 'Bhilwara', 'Alwar', 'Bharatpur', 'Sri Ganganagar'
            ],
            'Sikkim' => [
                'Gangtok', 'Namchi', 'Gyalshing'
            ],
            'Tamil Nadu' => [
                'Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem', 'Tirunelveli', 'Tiruppur', 'Vellore', 'Erode', 'Thoothukudi'
            ],
            'Telangana' => [
                'Hyderabad', 'Warangal', 'Nizamabad', 'Khammam', 'Karimnagar', 'Ramagundam', 'Mahbubnagar', 'Nalgonda', 'Adilabad'
            ],
            'Tripura' => [
                'Agartala', 'Udaipur', 'Dharmanagar'
            ],
            'Uttar Pradesh' => [
                'Lucknow', 'Kanpur', 'Ghaziabad', 'Agra', 'Meerut', 'Varanasi', 'Allahabad', 'Bareilly', 'Aligarh', 'Moradabad', 'Saharanpur', 'Gorakhpur', 'Noida', 'Firozabad', 'Jhansi'
            ],
            'Uttarakhand' => [
                'Dehradun', 'Haridwar', 'Roorkee', 'Haldwani', 'Rudrapur', 'Kashipur', 'Rishikesh'
            ],
            'West Bengal' => [
                'Kolkata', 'Asansol', 'Siliguri', 'Durgapur', 'Bardhaman', 'Malda', 'Baharampur', 'Habra', 'Kharagpur', 'Shantipur'
            ],
            'Chandigarh' => ['Chandigarh'],
            'Delhi' => ['New Delhi', 'Delhi'],
            'Puducherry' => ['Puducherry', 'Karaikal', 'Mahe', 'Yanam'],
            'Jammu and Kashmir' => ['Srinagar', 'Jammu', 'Anantnag'],
            'Ladakh' => ['Leh', 'Kargil']
        ];

        foreach ($statesData as $stateName => $citiesList) {
            // Find or Create State
            $state = State::where('name', $stateName)->where('country_id', $indiaId)->first();
            if (!$state) {
                $state = new State();
                $state->name = $stateName;
                $state->country_id = $indiaId;
                $state->status = 1;
                $state->save();
            } else {
                if ($state->status == 0) {
                     $state->status = 1;
                     $state->save();
                }
            }

            // Create Cities
            foreach ($citiesList as $cityName) {
                $city = City::where('name', $cityName)->where('state_id', $state->id)->first();
                if (!$city) {
                    $city = new City();
                    $city->name = $cityName;
                    $city->state_id = $state->id;
                    $city->country_id = $indiaId;
                    $city->cost = 0;
                    $city->status = 1;
                    $city->save();

                    // Create Translation
                    $translation = new CityTranslation();
                    $translation->city_id = $city->id;
                    $translation->lang = 'en'; // Default
                    $translation->name = $cityName;
                    $translation->save();
                } else {
                     if ($city->status == 0) {
                        $city->status = 1;
                        $city->save();
                    }
                }
            }
        }
    }
}
