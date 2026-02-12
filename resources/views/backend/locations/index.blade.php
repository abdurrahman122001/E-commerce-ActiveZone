@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Location Management') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <span class="text-muted">{{ translate('Manage States, Cities & Area Zones') }}</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'states' ? 'active' : '' }}" href="?tab=states" role="tab">
                            <i class="las la-map-marked-alt mr-1"></i>{{ translate('States') }}
                            <span class="badge badge-soft-primary ml-1">{{ $states->total() ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'cities' ? 'active' : '' }}" href="?tab=cities" role="tab">
                            <i class="las la-city mr-1"></i>{{ translate('Cities') }}
                            <span class="badge badge-soft-info ml-1">{{ $cities->total() ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'areas' ? 'active' : '' }}" href="?tab=areas" role="tab">
                            <i class="las la-map-marker-alt mr-1"></i>{{ translate('Area Zones') }}
                            <span class="badge badge-soft-success ml-1">{{ $areas->total() ?? 0 }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <!-- States Tab -->
                @if($activeTab == 'states')
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <form action="" method="GET" class="form-inline">
                                <input type="hidden" name="tab" value="states">
                                <div class="input-group mr-2" style="width: 250px;">
                                    <input type="text" class="form-control" name="search_state" value="{{ request('search_state') }}" placeholder="{{ translate('Search states...') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="las la-search"></i></button>
                                    </div>
                                </div>
                                <select class="form-control aiz-selectpicker" name="country_id" data-live-search="true" onchange="this.form.submit()">
                                    <option value="">{{ translate('Filter by Country') }}</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="col-md-4 text-md-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addStateModal">
                                <i class="las la-plus mr-1"></i>{{ translate('Add State') }}
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="35%">{{ translate('State Name') }}</th>
                                    <th width="30%">{{ translate('Country') }}</th>
                                    <th width="15%">{{ translate('Cities Count') }}</th>
                                    <th width="15%" class="text-right">{{ translate('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($states as $key => $state)
                                    <tr>
                                        <td>{{ ($key+1) + ($states->currentPage() - 1) * $states->perPage() }}</td>
                                        <td>{{ $state->name }}</td>
                                        <td>{{ $state->country->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-soft-info">{{ $state->cities()->count() }}</span>
                                        </td>
                                        <td class="text-right">
                                            <button class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="editState({{ $state->id }}, '{{ $state->name }}', {{ $state->country_id }})" title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </button>
                                            <a href="{{ route('admin.locations.states.destroy', $state->id) }}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('admin.locations.states.destroy', $state->id) }}" title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ translate('No states found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="aiz-pagination">
                        {{ $states->appends(request()->input())->links() }}
                    </div>
                @endif

                <!-- Cities Tab -->
                @if($activeTab == 'cities')
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <form action="" method="GET" class="form-inline">
                                <input type="hidden" name="tab" value="cities">
                                <div class="input-group mr-2" style="width: 250px;">
                                    <input type="text" class="form-control" name="search_city" value="{{ request('search_city') }}" placeholder="{{ translate('Search cities...') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="las la-search"></i></button>
                                    </div>
                                </div>
                                <select class="form-control aiz-selectpicker mr-2" name="country_id" data-live-search="true" onchange="this.form.submit()">
                                    <option value="">{{ translate('All Countries') }}</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <select class="form-control aiz-selectpicker" name="state_id" data-live-search="true" onchange="this.form.submit()">
                                    <option value="">{{ translate('All States') }}</option>
                                    @foreach($statesList as $s)
                                        <option value="{{ $s->id }}" {{ request('state_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="col-md-4 text-md-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addCityModal">
                                <i class="las la-plus mr-1"></i>{{ translate('Add City') }}
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">{{ translate('City Name') }}</th>
                                    <th width="20%">{{ translate('State') }}</th>
                                    <th width="20%">{{ translate('Country') }}</th>
                                    <th width="10%">{{ translate('Areas') }}</th>
                                    <th width="10%">{{ translate('Cost') }}</th>
                                    <th width="10%" class="text-right">{{ translate('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cities as $key => $city)
                                    <tr>
                                        <td>{{ ($key+1) + ($cities->currentPage() - 1) * $cities->perPage() }}</td>
                                        <td>{{ $city->getTranslation('name') }}</td>
                                        <td>{{ $city->state->name ?? '-' }}</td>
                                        <td>{{ $city->country->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-soft-success">{{ $city->areas()->count() }}</span>
                                        </td>
                                        <td>{{ single_price($city->cost) }}</td>
                                        <td class="text-right">
                                            <button class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="editCity({{ $city->id }}, '{{ $city->getTranslation('name') }}', {{ $city->state_id ?? 'null' }}, {{ $city->cost }})" title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </button>
                                            <a href="{{ route('admin.locations.cities.destroy', $city->id) }}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('admin.locations.cities.destroy', $city->id) }}" title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ translate('No cities found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="aiz-pagination">
                        {{ $cities->appends(request()->input())->links() }}
                    </div>
                @endif

                <!-- Areas Tab -->
                @if($activeTab == 'areas')
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <form action="" method="GET" class="form-inline">
                                <input type="hidden" name="tab" value="areas">
                                <div class="input-group mr-2" style="width: 250px;">
                                    <input type="text" class="form-control" name="search_area" value="{{ request('search_area') }}" placeholder="{{ translate('Search areas...') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="las la-search"></i></button>
                                    </div>
                                </div>
                                <select class="form-control aiz-selectpicker mr-2" name="state_id" data-live-search="true" onchange="this.form.submit()">
                                    <option value="">{{ translate('All States') }}</option>
                                    @foreach($statesList as $s)
                                        <option value="{{ $s->id }}" {{ request('state_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                                <select class="form-control aiz-selectpicker" name="city_id" data-live-search="true" onchange="this.form.submit()">
                                    <option value="">{{ translate('All Cities') }}</option>
                                    @foreach($citiesList as $c)
                                        <option value="{{ $c->id }}" {{ request('city_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="col-md-4 text-md-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addAreaModal">
                                <i class="las la-plus mr-1"></i>{{ translate('Add Area Zone') }}
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">{{ translate('Area Name') }}</th>
                                    <th width="20%">{{ translate('City') }}</th>
                                    <th width="20%">{{ translate('State') }}</th>
                                    <th width="10%">{{ translate('Cost') }}</th>
                                    <th width="10%">{{ translate('Status') }}</th>
                                    <th width="10%" class="text-right">{{ translate('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($areas as $key => $area)
                                    <tr>
                                        <td>{{ ($key+1) + ($areas->currentPage() - 1) * $areas->perPage() }}</td>
                                        <td>{{ $area->getTranslation('name') }}</td>
                                        <td>{{ $area->city->name ?? '-' }}</td>
                                        <td>{{ $area->city->state->name ?? '-' }}</td>
                                        <td>{{ single_price($area->cost) }}</td>
                                        <td>
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input type="checkbox" disabled {{ $area->status ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td class="text-right">
                                            <button class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="editArea({{ $area->id }}, '{{ $area->getTranslation('name') }}', {{ $area->city_id }}, {{ $area->cost }})" title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </button>
                                            <a href="{{ route('admin.locations.areas.destroy', $area->id) }}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('admin.locations.areas.destroy', $area->id) }}" title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ translate('No areas found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="aiz-pagination">
                        {{ $areas->appends(request()->input())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add State Modal -->
<div class="modal fade" id="addStateModal" tabindex="-1" role="dialog" aria-labelledby="addStateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add New State') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.locations.states.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ translate('State Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required placeholder="{{ translate('Enter state name') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Country') }} <span class="text-danger">*</span></label>
                        <select class="form-control aiz-selectpicker" name="country_id" data-live-search="true" required>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Save State') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit State Modal -->
<div class="modal fade" id="editStateModal" tabindex="-1" role="dialog" aria-labelledby="editStateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Edit State') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editStateForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ translate('State Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="edit_state_name" required>
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Country') }} <span class="text-danger">*</span></label>
                        <select class="form-control aiz-selectpicker" name="country_id" id="edit_state_country" data-live-search="true" required>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Update State') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add City Modal -->
<div class="modal fade" id="addCityModal" tabindex="-1" role="dialog" aria-labelledby="addCityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add New City') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.locations.cities.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ translate('City Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required placeholder="{{ translate('Enter city name') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ translate('State') }} <span class="text-danger">*</span></label>
                        <select class="form-control aiz-selectpicker" name="state_id" id="city_state_id" data-live-search="true" required>
                            @foreach($statesList as $state)
                                <option value="{{ $state->id }}">{{ $state->name }} ({{ $state->country->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Shipping Cost') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="cost" value="0" placeholder="{{ translate('Enter shipping cost') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Save City') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit City Modal -->
<div class="modal fade" id="editCityModal" tabindex="-1" role="dialog" aria-labelledby="editCityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Edit City') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCityForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ translate('City Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="edit_city_name" required>
                    </div>
                    <div class="form-group">
                        <label>{{ translate('State') }} <span class="text-danger">*</span></label>
                        <select class="form-control aiz-selectpicker" name="state_id" id="edit_city_state" data-live-search="true" required>
                            @foreach($statesList as $state)
                                <option value="{{ $state->id }}">{{ $state->name }} ({{ $state->country->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Shipping Cost') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="cost" id="edit_city_cost" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Update City') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Area Modal -->
<div class="modal fade" id="addAreaModal" tabindex="-1" role="dialog" aria-labelledby="addAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add New Area Zone') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.locations.areas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ translate('Area Zone Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required placeholder="{{ translate('Enter area zone name (e.g., North Delhi, South Mumbai)') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ translate('State') }}</label>
                        <select class="form-control aiz-selectpicker" id="area_filter_state" data-live-search="true">
                            <option value="">{{ translate('Select State to filter cities') }}</option>
                            @foreach($statesList as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ translate('City') }} <span class="text-danger">*</span></label>
                        <select class="form-control aiz-selectpicker" name="city_id" id="area_city_id" data-live-search="true" required>
                            <option value="">{{ translate('Select City') }}</option>
                            @foreach($citiesList as $city)
                                <option value="{{ $city->id }}" data-state="{{ $city->state_id }}">{{ $city->name }} ({{ $city->state->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Shipping Cost') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="cost" value="0" placeholder="{{ translate('Enter shipping cost') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Save Area Zone') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Area Modal -->
<div class="modal fade" id="editAreaModal" tabindex="-1" role="dialog" aria-labelledby="editAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Edit Area Zone') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editAreaForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ translate('Area Zone Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="edit_area_name" required>
                    </div>
                    <div class="form-group">
                        <label>{{ translate('City') }} <span class="text-danger">*</span></label>
                        <select class="form-control aiz-selectpicker" name="city_id" id="edit_area_city" data-live-search="true" required>
                            @foreach($citiesList as $city)
                                <option value="{{ $city->id }}">{{ $city->name }} ({{ $city->state->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Shipping Cost') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="cost" id="edit_area_cost" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Update Area Zone') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('modals.delete_modal')

@endsection

@section('script')
<script type="text/javascript">
    // Edit State
    function editState(id, name, countryId) {
        $('#editStateForm').attr('action', '{{ url("admin/locations/states/update") }}/' + id);
        $('#edit_state_name').val(name);
        $('#edit_state_country').val(countryId).trigger('change');
        $('#editStateModal').modal('show');
    }

    // Edit City
    function editCity(id, name, stateId, cost) {
        $('#editCityForm').attr('action', '{{ url("admin/locations/cities/update") }}/' + id);
        $('#edit_city_name').val(name);
        if (stateId) {
            $('#edit_city_state').val(stateId).trigger('change');
        }
        $('#edit_city_cost').val(cost);
        $('#editCityModal').modal('show');
    }

    // Edit Area
    function editArea(id, name, cityId, cost) {
        $('#editAreaForm').attr('action', '{{ url("admin/locations/areas/update") }}/' + id);
        $('#edit_area_name').val(name);
        $('#edit_area_city').val(cityId).trigger('change');
        $('#edit_area_cost').val(cost);
        $('#editAreaModal').modal('show');
    }

    // Filter cities by state in Add Area modal
    $('#area_filter_state').on('change', function() {
        var stateId = $(this).val();
        if (stateId) {
            $('#area_city_id option').each(function() {
                if ($(this).data('state') == stateId || $(this).val() == '') {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        } else {
            $('#area_city_id option').show();
        }
        $('#area_city_id').selectpicker('refresh');
    });
</script>
@endsection
