<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Area;
use App\Models\Vendor;
use Session;

class LocationController extends Controller
{
    public function setLocation(Request $request)
    {
        if ($request->filled('area_id')) {
            $area = Area::find($request->area_id);
            if ($area) {
                Session::put('selected_area_id', $area->id);
                Session::put('selected_area_name', $area->getTranslation('name'));
                if ($area->city_id) {
                    $city = City::find($area->city_id);
                    if ($city) {
                        Session::put('selected_city_id', $city->id);
                        Session::put('selected_city_name', $city->getTranslation('name'));
                        if ($city->state_id) {
                            $state = State::find($city->state_id);
                            if ($state) {
                                Session::put('selected_state_id', $state->id);
                                Session::put('selected_state_name', $state->name);
                            }
                        }
                        if ($city->country_id) {
                            $country = Country::find($city->country_id);
                            if ($country) {
                                Session::put('selected_country_id', $country->id);
                                Session::put('selected_country_name', $country->name);
                            }
                        }
                    }
                }
            }
        } elseif ($request->filled('city_id')) {
            Session::forget('selected_area_id');
            Session::forget('selected_area_name');
            $city = City::find($request->city_id);
            if ($city) {
                Session::put('selected_city_id', $city->id);
                Session::put('selected_city_name', $city->getTranslation('name'));
                if ($city->state_id) {
                    $state = State::find($city->state_id);
                    if ($state) {
                        Session::put('selected_state_id', $state->id);
                        Session::put('selected_state_name', $state->name);
                    }
                }
                if ($city->country_id) {
                    $country = Country::find($city->country_id);
                    if ($country) {
                        Session::put('selected_country_id', $country->id);
                        Session::put('selected_country_name', $country->name);
                    }
                }
            }
        } elseif ($request->filled('state_id')) {
            Session::forget('selected_area_id');
            Session::forget('selected_area_name');
            Session::forget('selected_city_id');
            Session::forget('selected_city_name');
            $state = State::find($request->state_id);
            if ($state) {
                Session::put('selected_state_id', $state->id);
                Session::put('selected_state_name', $state->name);
                $country = Country::find($state->country_id ?? null);
                if ($country) {
                    Session::put('selected_country_id', $country->id);
                    Session::put('selected_country_name', $country->name);
                }
            }
        } elseif ($request->filled('country_id')) {
            Session::forget('selected_area_id');
            Session::forget('selected_area_name');
            Session::forget('selected_city_id');
            Session::forget('selected_city_name');
            Session::forget('selected_state_id');
            Session::forget('selected_state_name');
            $country = Country::find($request->country_id);
            if ($country) {
                Session::put('selected_country_id', $country->id);
                Session::put('selected_country_name', $country->name);
            }
        } else {
            // Reset all location filters
            Session::forget('selected_area_id');
            Session::forget('selected_area_name');
            Session::forget('selected_city_id');
            Session::forget('selected_city_name');
            Session::forget('selected_state_id');
            Session::forget('selected_state_name');
            Session::forget('selected_country_id');
            Session::forget('selected_country_name');
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get countries that have at least one vendor registered in them (via vendor → city → country).
     */
    public function getAvailableCountries(Request $request)
    {
        $city_ids    = Vendor::whereNotNull('city_id')->pluck('city_id')->unique();
        $country_ids = City::whereIn('id', $city_ids)->whereNotNull('country_id')->pluck('country_id')->unique();
        $countries   = Country::whereIn('id', $country_ids)->orderBy('name')->get(['id', 'name']);
        return response()->json($countries);
    }

    /**
     * Get states that have vendors, optionally filtered by country.
     */
    public function getStatesByCountry(Request $request)
    {
        $vendor_query = Vendor::whereNotNull('state_id');
        if ($request->filled('country_id')) {
            // Filter vendors whose city belongs to the given country
            $city_ids = City::where('country_id', $request->country_id)->pluck('id');
            $vendor_query->whereIn('city_id', $city_ids);
        }
        $state_ids = $vendor_query->pluck('state_id')->unique();
        $states    = State::whereIn('id', $state_ids)->orderBy('name')->get(['id', 'name']);
        return response()->json($states);
    }

    /**
     * Get cities that have vendors, filtered by state.
     */
    public function getCitiesByState(Request $request)
    {
        $vendor_query = Vendor::whereNotNull('city_id');
        if ($request->filled('state_id')) {
            $vendor_query->where('state_id', $request->state_id);
        }
        $city_ids = $vendor_query->pluck('city_id')->unique();
        $cities   = City::whereIn('id', $city_ids)->get()->map(function ($city) {
            return ['id' => $city->id, 'name' => $city->getTranslation('name')];
        });
        return response()->json($cities);
    }

    /**
     * Get areas that have vendors, filtered by city.
     */
    public function getAreasByCity(Request $request)
    {
        $vendor_query = Vendor::whereNotNull('area_id');
        if ($request->filled('city_id')) {
            $vendor_query->where('city_id', $request->city_id);
        }
        $area_ids = $vendor_query->pluck('area_id')->unique();
        $areas    = Area::whereIn('id', $area_ids)->get()->map(function ($area) {
            return ['id' => $area->id, 'name' => $area->getTranslation('name')];
        });
        return response()->json($areas);
    }
}
