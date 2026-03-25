@php
    // Get countries that actually have vendors
    $vendor_city_ids    = \App\Models\Vendor::whereNotNull('city_id')->pluck('city_id')->unique();
    $available_country_ids = \App\Models\City::whereIn('id', $vendor_city_ids)->whereNotNull('country_id')->pluck('country_id')->unique();
    $available_countries = \App\Models\Country::whereIn('id', $available_country_ids)->orderBy('name')->get();

    // Current session values
    $sel_country_id = Session::get('selected_country_id');
    $sel_state_id   = Session::get('selected_state_id');
    $sel_city_id    = Session::get('selected_city_id');
    $sel_area_id    = Session::get('selected_area_id');

    // Pre-load states for selected country
    $available_states = collect();
    if ($sel_country_id) {
        $country_city_ids = \App\Models\City::where('country_id', $sel_country_id)->pluck('id');
        $state_ids = \App\Models\Vendor::whereNotNull('state_id')->whereIn('city_id', $country_city_ids)->pluck('state_id')->unique();
        $available_states = \App\Models\State::whereIn('id', $state_ids)->orderBy('name')->get();
    }

    // Pre-load cities for selected state
    $available_cities = collect();
    if ($sel_state_id) {
        $city_ids = \App\Models\Vendor::whereNotNull('city_id')->where('state_id', $sel_state_id)->pluck('city_id')->unique();
        $available_cities = \App\Models\City::whereIn('id', $city_ids)->get();
    }

    // Pre-load areas for selected city
    $available_areas = collect();
    if ($sel_city_id) {
        $area_ids = \App\Models\Vendor::whereNotNull('area_id')->where('city_id', $sel_city_id)->pluck('area_id')->unique();
        $available_areas = \App\Models\Area::whereIn('id', $area_ids)->get();
    }

    // Label for button
    $location_label = Session::get('selected_area_name')
        ?? Session::get('selected_city_name')
        ?? Session::get('selected_state_name')
        ?? Session::get('selected_country_name')
        ?? translate('Select Location');
@endphp

<div class="d-flex align-items-center location-picker">
    <div class="dropdown" id="location-choice">
        <a href="javascript:void(0)"
           class="dropdown-toggle text-reset fs-13 py-2 d-flex align-items-center border px-2 rounded bg-light shadow-sm"
           data-toggle="dropdown" data-display="static">
            <i class="las la-map-marker la-lg mr-1 text-primary"></i>
            <span class="text-truncate" style="max-width: 150px;">{{ $location_label }}</span>
        </a>

        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left p-3 shadow-lg border-0 stop-propagation"
             style="min-width: 300px;">

            {{-- Country --}}
            <div class="form-group mb-2">
                <label class="small font-weight-bold mb-1">{{ translate('Country') }}</label>
                <select class="form-control" id="loc_country">
                    <option value="">{{ translate('All Countries') }}</option>
                    @foreach ($available_countries as $c)
                        <option value="{{ $c->id }}" @selected($sel_country_id == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- State --}}
            <div class="form-group mb-2 loc-state-wrap" @if(!$sel_country_id) style="display:none;" @endif>
                <label class="small font-weight-bold mb-1">{{ translate('State') }}</label>
                <select class="form-control" id="loc_state">
                    <option value="">{{ translate('All States') }}</option>
                    @foreach ($available_states as $s)
                        <option value="{{ $s->id }}" @selected($sel_state_id == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- City --}}
            <div class="form-group mb-2 loc-city-wrap" @if(!$sel_state_id) style="display:none;" @endif>
                <label class="small font-weight-bold mb-1">{{ translate('City') }}</label>
                <select class="form-control" id="loc_city">
                    <option value="">{{ translate('All Cities') }}</option>
                    @foreach ($available_cities as $ct)
                        <option value="{{ $ct->id }}" @selected($sel_city_id == $ct->id)>{{ $ct->getTranslation('name') }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Area --}}
            <div class="form-group mb-3 loc-area-wrap" @if(!$sel_city_id) style="display:none;" @endif>
                <label class="small font-weight-bold mb-1">{{ translate('Area') }}</label>
                <select class="form-control" id="loc_area">
                    <option value="">{{ translate('All Areas') }}</option>
                    @foreach ($available_areas as $ar)
                        <option value="{{ $ar->id }}" @selected($sel_area_id == $ar->id)>{{ $ar->getTranslation('name') }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-soft-secondary btn-sm" onclick="clearLocation()">
                    <i class="las la-times mr-1"></i>{{ translate('Reset') }}
                </button>
                <button type="button" class="btn btn-primary btn-sm px-4" onclick="applyLocation()">
                    <i class="las la-check mr-1"></i>{{ translate('Apply') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

    // Country → load states
    $('#loc_country').on('change', function () {
        var country_id = $(this).val();
        resetSelect('#loc_state', '{{ translate('All States') }}');
        resetSelect('#loc_city',  '{{ translate('All Cities') }}');
        resetSelect('#loc_area',  '{{ translate('All Areas') }}');
        $('.loc-state-wrap, .loc-city-wrap, .loc-area-wrap').hide();

        if (country_id) {
            $.post('{{ route('location.get-states-by-country') }}', {
                _token: '{{ csrf_token() }}',
                country_id: country_id
            }, function (data) {
                fillSelect('#loc_state', data);
                if (data.length) $('.loc-state-wrap').show();
            });
        }
    });

    // State → load cities
    $('#loc_state').on('change', function () {
        var state_id = $(this).val();
        resetSelect('#loc_city', '{{ translate('All Cities') }}');
        resetSelect('#loc_area', '{{ translate('All Areas') }}');
        $('.loc-city-wrap, .loc-area-wrap').hide();

        if (state_id) {
            $.post('{{ route('location.get-cities-by-state') }}', {
                _token: '{{ csrf_token() }}',
                state_id: state_id
            }, function (data) {
                fillSelect('#loc_city', data);
                if (data.length) $('.loc-city-wrap').show();
            });
        }
    });

    // City → load areas
    $('#loc_city').on('change', function () {
        var city_id = $(this).val();
        resetSelect('#loc_area', '{{ translate('All Areas') }}');
        $('.loc-area-wrap').hide();

        if (city_id) {
            $.post('{{ route('location.get-areas-by-city') }}', {
                _token: '{{ csrf_token() }}',
                city_id: city_id
            }, function (data) {
                fillSelect('#loc_area', data);
                if (data.length) $('.loc-area-wrap').show();
            });
        }
    });

    // Keep dropdown open on click inside
    $(document).on('click', '.stop-propagation', function (e) {
        e.stopPropagation();
    });
});

function resetSelect(selector, placeholder) {
    $(selector).empty().append('<option value="">' + placeholder + '</option>');
}

function fillSelect(selector, data) {
    $.each(data, function (i, item) {
        $(selector).append('<option value="' + item.id + '">' + item.name + '</option>');
    });
}

function applyLocation() {
    $.post('{{ route('location.set') }}', {
        _token:     '{{ csrf_token() }}',
        country_id: $('#loc_country').val(),
        state_id:   $('#loc_state').val(),
        city_id:    $('#loc_city').val(),
        area_id:    $('#loc_area').val()
    }, function (data) {
        if (data.success) location.reload();
    });
}

function clearLocation() {
    $.post('{{ route('location.set') }}', { _token: '{{ csrf_token() }}' }, function (data) {
        if (data.success) location.reload();
    });
}
</script>
