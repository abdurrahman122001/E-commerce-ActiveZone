<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Session;

class LocationController extends Controller
{
    public function setLocation(Request $request)
    {
        if ($request->has('city_id')) {
            Session::put('selected_city_id', $request->city_id);
            $city = City::find($request->city_id);
            if ($city) {
                Session::put('selected_city_name', $city->getTranslation('name'));
                Session::put('selected_state_id', $city->state_id);
                Session::put('selected_country_id', $city->country_id);
            }
        } elseif ($request->has('country_id')) {
            Session::put('selected_country_id', $request->country_id);
            Session::forget('selected_city_id');
            Session::forget('selected_city_name');
            Session::forget('selected_state_id');
            $country = Country::find($request->country_id);
            if ($country) {
                Session::put('selected_country_name', $country->getTranslation('name'));
            }
        }

        return response()->json(['success' => true]);
    }

    public function getCitiesByCountry(Request $request)
    {
        $cities = City::where('country_id', $request->country_id)->get();
        return response()->json($cities);
    }
}
