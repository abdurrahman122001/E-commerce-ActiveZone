<div class="d-flex align-items-center ml-3 mr-0 location-picker">
    <div class="dropdown" id="location-choice">
        <a href="javascript:void(0)" class="dropdown-toggle text-reset fs-13 py-2 d-flex align-items-center" data-toggle="dropdown" data-display="static">
            <i class="las la-map-marker la-lg mr-1 text-primary"></i>
            <span class="text-truncate" style="max-width: 100px;">
                {{ Session::get('selected_city_name') ?? (Session::get('selected_country_name') ?? translate('Select Location')) }}
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left p-3" style="min-width: 300px;">
            <div class="form-group">
                <label>{{ translate('Country') }}</label>
                <select class="form-control aiz-selectpicker" id="location_country" data-live-search="true">
                    <option value="">{{ translate('All Countries') }}</option>
                    @foreach (get_active_countries() as $country)
                        <option value="{{ $country->id }}" @if(Session::get('selected_country_id') == $country->id) selected @endif >
                            {{ $country->getTranslation('name') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group city-select-group" @if(!Session::has('selected_country_id')) style="display:none;" @endif>
                <label>{{ translate('City') }}</label>
                <select class="form-control aiz-selectpicker" id="location_city" data-live-search="true">
                    <option value="">{{ translate('All Cities') }}</option>
                    @if(Session::has('selected_country_id'))
                        @foreach (\App\Models\City::where('country_id', Session::get('selected_country_id'))->get() as $city)
                            <option value="{{ $city->id }}" @if(Session::get('selected_city_id') == $city->id) selected @endif >
                                {{ $city->getTranslation('name') }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="text-right">
                <button type="button" class="btn btn-primary btn-sm" onclick="applyLocation()">{{ translate('Apply') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#location_country').on('change', function() {
        var country_id = $(this).val();
        if (country_id) {
            $('.city-select-group').show();
            $.post('{{ route('location.get-city-by-country') }}', {
                _token: '{{ csrf_token() }}',
                country_id: country_id
            }, function(data) {
                var city_select = $('#location_city');
                city_select.empty();
                city_select.append('<option value="">{{ translate('All Cities') }}</option>');
                $.each(data, function(index, city) {
                    city_select.append('<option value="' + city.id + '">' + city.name + '</option>');
                });
                city_select.selectpicker('refresh');
            });
        } else {
            $('.city-select-group').hide();
        }
    });

    function applyLocation() {
        var country_id = $('#location_country').val();
        var city_id = $('#location_city').val();
        
        $.post('{{ route('location.set') }}', {
            _token: '{{ csrf_token() }}',
            country_id: country_id,
            city_id: city_id
        }, function(data) {
            location.reload();
        });
    }
</script>
