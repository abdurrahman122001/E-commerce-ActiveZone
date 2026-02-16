@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Sub-Franchise')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <form class="p-4" action="{{ route('admin.sub_franchises.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="email" placeholder="{{translate('Email')}}" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="phone">{{translate('Phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Phone')}}" id="phone" name="phone" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="state_id">{{translate('State')}}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" name="state_id" id="state_id" data-live-search="true" required>
                            <option value="">{{ translate('Select State') }}</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="city_id">{{translate('City')}}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" name="city_id" id="city_id" data-live-search="true" required>
                            <option value="">{{ translate('Select City') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="area_id">{{translate('Area')}}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" name="area_id" id="area_id" data-live-search="true" required>
                            <option value="">{{ translate('Select Area') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="franchise_package_id">{{translate('Package')}}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" name="franchise_package_id" id="franchise_package_id" data-live-search="true" required>
                            <option value="">{{ translate('Select Package') }}</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->getTranslation('name') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                 <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="business_experience">{{translate('Business Experience')}}</label>
                    <div class="col-sm-9">
                         <textarea class="form-control" name="business_experience" rows="3" placeholder="{{ translate('Briefly describe business experience') }}"></textarea>
                    </div>
                </div>

                
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        $('#state_id').change(function(){
            var state_id = $(this).val();
            get_cities(state_id);
        });

        $('#city_id').change(function(){
            var city_id = $(this).val();
            get_areas(city_id);
        });

        function get_cities(state_id){
            $.post('{{ route('get-city') }}', { _token: '{{ csrf_token() }}', state_id: state_id }, function(data){
                $('#city_id').html(null);
                $('#city_id').append($('<option>', {
                    value: '',
                    text: '{{ translate("Select City") }}'
                }));
                var obj = JSON.parse(data);
                $('#city_id').append(obj);
                $('.aiz-selectpicker').selectpicker('refresh');
            });
        }

        function get_areas(city_id) {
            $.post('{{ route('get-area') }}', { _token: '{{ csrf_token() }}', city_id: city_id, franchise_type: 'sub_franchise' }, function(data){
                var obj = JSON.parse(data);
                if(obj.indexOf('disabled') == -1){
                    var html = '<option value="">{{ translate("Select Area") }}</option>';
                    $('#area_id').html(html + obj);
                } else {
                    $('#area_id').html(obj);
                }
                $('.aiz-selectpicker').selectpicker('refresh');
            });
        }
    });
</script>
@endsection
