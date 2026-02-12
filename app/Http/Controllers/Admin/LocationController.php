<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;
use App\Models\CityTranslation;
use App\Models\Area;
use App\Models\AreaTranslation;
use App\Models\Country;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display unified location management interface
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'states');
        $countryId = $request->get('country_id');
        $stateId = $request->get('state_id');
        $cityId = $request->get('city_id');

        // Get countries for dropdowns
        $countries = Country::where('status', 1)->get();

        // Get states with filters
        $statesQuery = State::with('country');
        if ($countryId) {
            $statesQuery->where('country_id', $countryId);
        }
        if ($request->has('search_state')) {
            $statesQuery->where('name', 'like', '%' . $request->search_state . '%');
        }
        $states = $statesQuery->orderBy('name')->paginate(20, ['*'], 'states_page');

        // Get cities with filters
        $citiesQuery = City::with(['state', 'country']);
        if ($countryId) {
            $citiesQuery->where('country_id', $countryId);
        }
        if ($stateId) {
            $citiesQuery->where('state_id', $stateId);
        }
        if ($request->has('search_city')) {
            $citiesQuery->where('name', 'like', '%' . $request->search_city . '%');
        }
        $cities = $citiesQuery->orderBy('name')->paginate(20, ['*'], 'cities_page');

        // Get areas with filters
        $areasQuery = Area::with(['city', 'city.state']);
        if ($cityId) {
            $areasQuery->where('city_id', $cityId);
        }
        if ($stateId) {
            $areasQuery->whereHas('city', function($q) use ($stateId) {
                $q->where('state_id', $stateId);
            });
        }
        if ($request->has('search_area')) {
            $areasQuery->where('name', 'like', '%' . $request->search_area . '%');
        }
        $areas = $areasQuery->orderBy('name')->paginate(20, ['*'], 'areas_page');

        // Get dropdown data
        $statesList = State::where('status', 1)->orderBy('name')->get();
        $citiesList = City::where('status', 1)->orderBy('name')->get();

        return view('backend.locations.index', compact(
            'activeTab', 'countries', 'states', 'cities', 'areas',
            'statesList', 'citiesList', 'countryId', 'stateId', 'cityId'
        ));
    }

    // ========== STATE MANAGEMENT ==========

    public function storeState(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ]);

        $state = new State();
        $state->name = $request->name;
        $state->country_id = $request->country_id;
        $state->status = 1;
        $state->save();

        flash(translate('State has been added successfully'))->success();
        return redirect()->route('admin.locations.index', ['tab' => 'states']);
    }

    public function updateState(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ]);

        $state = State::findOrFail($id);
        $state->name = $request->name;
        $state->country_id = $request->country_id;
        $state->save();

        flash(translate('State has been updated successfully'))->success();
        return redirect()->route('admin.locations.index', ['tab' => 'states']);
    }

    public function destroyState($id)
    {
        $state = State::findOrFail($id);
        
        // Check if state has cities
        if ($state->cities()->count() > 0) {
            flash(translate('Cannot delete state with associated cities'))->error();
            return back();
        }

        $state->delete();
        flash(translate('State has been deleted successfully'))->success();
        return redirect()->route('admin.locations.index', ['tab' => 'states']);
    }

    // ========== CITY MANAGEMENT ==========

    public function storeCity(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $state = State::findOrFail($request->state_id);

        $city = new City();
        $city->name = $request->name;
        $city->state_id = $request->state_id;
        $city->country_id = $state->country_id;
        $city->cost = $request->cost ?? 0;
        $city->status = 1;
        $city->save();

        // Create translation
        $translation = new CityTranslation();
        $translation->city_id = $city->id;
        $translation->name = $request->name;
        $translation->lang = env('DEFAULT_LANGUAGE', 'en');
        $translation->save();

        flash(translate('City has been added successfully'))->success();
        return redirect()->route('admin.locations.index', ['tab' => 'cities']);
    }

    public function updateCity(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $city = City::findOrFail($id);
        $state = State::findOrFail($request->state_id);

        $city->name = $request->name;
        $city->state_id = $request->state_id;
        $city->country_id = $state->country_id;
        $city->cost = $request->cost ?? 0;
        $city->save();

        // Update translation
        $translation = CityTranslation::firstOrNew([
            'city_id' => $city->id,
            'lang' => env('DEFAULT_LANGUAGE', 'en')
        ]);
        $translation->name = $request->name;
        $translation->save();

        flash(translate('City has been updated successfully'))->success();
        return redirect()->route('admin.locations.index', ['tab' => 'cities']);
    }

    public function destroyCity($id)
    {
        $city = City::findOrFail($id);
        
        // Check if city has areas
        if ($city->areas()->count() > 0) {
            flash(translate('Cannot delete city with associated areas'))->error();
            return back();
        }

        $city->city_translations()->delete();
        $city->delete();

        flash(translate('City has been deleted successfully'))->success();
        return redirect()->route('admin.locations.index', ['tab' => 'cities']);
    }

    // ========== AREA MANAGEMENT ==========

    public function storeArea(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $city = City::findOrFail($request->city_id);

        $area = new Area();
        $area->name = $request->name;
        $area->city_id = $request->city_id;
        $area->state_id = $city->state_id;
        $area->country_id = $city->country_id;
        $area->cost = $request->cost ?? 0;
        $area->status = 1;
        $area->save();

        // Create translation
        $translation = new AreaTranslation();
        $translation->area_id = $area->id;
        $translation->name = $request->name;
        $translation->lang = env('DEFAULT_LANGUAGE', 'en');
        $translation->save();

        flash(translate('Area has been added successfully'))->success();
        return redirect()->route('admin.locations.index', ['tab' => 'areas']);
    }

    public function updateArea(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $area = Area::findOrFail($id);
        $city = City::findOrFail($request->city_id);

        $area->name = $request->name;
        $area->city_id = $request->city_id;
        $area->state_id = $city->state_id;
        $area->country_id = $city->country_id;
        $area->cost = $request->cost ?? 0;
        $area->save();

        // Update translation
        $translation = AreaTranslation::firstOrNew([
            'area_id' => $area->id,
            'lang' => env('DEFAULT_LANGUAGE', 'en')
        ]);
        $translation->name = $request->name;
        $translation->save();

        flash(translate('Area has been updated successfully'))->success();
        return redirect()->route('admin.locations.index', ['tab' => 'areas']);
    }

    public function destroyArea($id)
    {
        $area = Area::findOrFail($id);
        $area->area_translations()->delete();
        $area->delete();

        flash(translate('Area has been deleted successfully'))->success();
        return redirect()->route('admin.locations.index', ['tab' => 'areas']);
    }

    // ========== AJAX HELPERS ==========

    public function getStatesByCountry(Request $request)
    {
        $states = State::where('country_id', $request->country_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);
        return response()->json($states);
    }

    public function getCitiesByState(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);
        return response()->json($cities);
    }
}
