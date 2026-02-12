<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\AreaTranslation;
use App\Models\City;

class AreaZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            '706' => ['North Delhi', 'South Delhi', 'East Delhi', 'West Delhi', 'Central Delhi'],
            '2707' => ['Mumbai North', 'Mumbai South', 'Mumbai East', 'Mumbai West', 'Navi Mumbai'],
            '48363' => ['Electronic City', 'Whitefield', 'Indiranagar', 'Koramangala', 'Jayanagar'],
            '31580' => ['Banjara Hills', 'Jubilee Hills', 'Gachibowli', 'Hitech City', 'Secunderabad'],
            '783' => ['Satellite', 'Navrangpura', 'Vastrapur', 'Prahlad Nagar', 'Bodakdev'],
            '29019' => ['Kothrud', 'Baner', 'Hinjewadi', 'Viman Nagar', 'Hadapsar'],
            '50820' => ['Salt Lake', 'New Town', 'Ballygunge', 'Alipore', 'Park Street'],
            '31922' => ['Malviya Nagar', 'Vaishali Nagar', 'Mansarovar', 'C Scheme', 'Raja Park'],
            '50293' => ['Gomti Nagar', 'Hazratganj', 'Aliganj', 'Indira Nagar', 'Jankipuram'],
        ];

        foreach ($data as $cityId => $areas) {
            $city = City::find($cityId);
            if ($city) {
                foreach ($areas as $areaName) {
                    $area = Area::firstOrCreate([
                        'name' => $areaName,
                        'city_id' => $cityId,
                    ], [
                        'status' => 1,
                        'cost' => 0,
                    ]);

                    // Seed translation if needed
                    AreaTranslation::firstOrCreate([
                        'area_id' => $area->id,
                        'lang' => 'en',
                    ], [
                        'name' => $areaName,
                    ]);
                }
            }
        }
    }
}
