@extends('frontend.layouts.app')

@section('content')
<section class="gry-bg py-5">
    <div class="profile">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="card rounded-0 border-0 shadow-sm">
                        <div class="card-header pt-4 border-bottom-0">
                            <h1 class="mb-0 fs-20 fw-700 text-center w-100">{{ translate('Franchise Partner Registration') }}</h1>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('franchise.register') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('Full Name') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control rounded-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="{{ translate('Full Name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('Email') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control rounded-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ translate('Email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('Mobile Number') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control rounded-0 @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="{{ translate('Mobile Number') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('Password') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control rounded-0 @error('password') is-invalid @enderror" name="password" placeholder="{{ translate('Password') }}" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('Confirm Password') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control rounded-0" name="password_confirmation" placeholder="{{ translate('Confirm Password') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('Apply For') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <select class="form-control aiz-selectpicker @error('franchise_type') is-invalid @enderror" name="franchise_type" id="franchise_type" required>
                                            <option value="">{{ translate('Select Franchise Type') }}</option>
                                            <option value="state_franchise" {{ (old('franchise_type') == 'state_franchise' || (isset($type) && $type == 'state_franchise')) ? 'selected' : '' }}>{{ translate('State Franchise') }}</option>
                                            <option value="city_franchise" {{ (old('franchise_type') == 'city_franchise' || (isset($type) && $type == 'city_franchise')) ? 'selected' : '' }}>{{ translate('City Franchise (Master Franchise)') }}</option>
                                            <option value="sub_franchise" {{ (old('franchise_type') == 'sub_franchise' || (isset($type) && $type == 'sub_franchise')) ? 'selected' : '' }}>{{ translate('Sub-Franchise (Area/Zone/Tehsil)') }}</option>
                                        </select>
                                        @error('franchise_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('State') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <select class="form-control aiz-selectpicker @error('state_id') is-invalid @enderror" name="state_id" id="state_id" data-live-search="true" required>
                                            <option value="">{{ translate('Select State') }}</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('state_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row" id="city_section">
                                    <label class="col-md-3 col-form-label">{{ translate('City') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <select class="form-control aiz-selectpicker @error('city_id') is-invalid @enderror" name="city_id" id="city_id" data-live-search="true">
                                            <option value="">{{ translate('Select City') }}</option>
                                        </select>
                                        @error('city_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row d-none" id="area_section">
                                    <label class="col-md-3 col-form-label">{{ translate('Area / Zone') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <select class="form-control aiz-selectpicker @error('area_id') is-invalid @enderror" name="area_id" id="area_id" data-live-search="true">
                                            <option value="">{{ translate('Select Area') }}</option>
                                        </select>
                                        @error('area_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('Package') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <select class="form-control aiz-selectpicker @error('franchise_package_id') is-invalid @enderror" name="franchise_package_id" id="franchise_package_id" data-live-search="true" required>
                                            <option value="">{{ translate('Select Package') }}</option>
                                            @foreach($packages as $package)
                                                <option value="{{ $package->id }}" {{ old('franchise_package_id') == $package->id ? 'selected' : '' }}>{{ $package->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                        @error('franchise_package_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('Business Experience') }}</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control rounded-0" name="business_experience" rows="3" placeholder="{{ translate('Briefly describe your business experience') }}">{{ old('business_experience') }}</textarea>
                                    </div>
                                </div>



                                <div class="form-group mb-0 text-right">
                                    <button type="submit" class="btn btn-primary rounded-0 w-100">{{ translate('Submit Application') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        $('#franchise_type').change(function(){
            var type = $(this).val();
            if(type == 'sub_franchise'){
                $('#city_section').removeClass('d-none');
                $('#city_id').prop('required', true);
                $('#area_section').removeClass('d-none');
                $('#area_id').prop('required', true);
            } else if(type == 'city_franchise'){
                $('#city_section').removeClass('d-none');
                $('#city_id').prop('required', true);
                $('#area_section').addClass('d-none');
                $('#area_id').prop('required', false);
            } else if(type == 'state_franchise'){
                $('#city_section').addClass('d-none');
                $('#city_id').prop('required', false);
                $('#area_section').addClass('d-none');
                $('#area_id').prop('required', false);
            }
            
            var state_id = $('#state_id').val();
            if(state_id && type != 'state_franchise'){
                get_cities(state_id);
            }
        });

        $('#state_id').change(function(){
            var state_id = $(this).val();
            get_cities(state_id);
        });

        $('#city_id').change(function(){
            var city_id = $(this).val();
            get_areas(city_id);
        });

        function get_cities(state_id){
            var franchise_type = $('#franchise_type').val();
            $.post('{{ route('get-city') }}', { _token: '{{ csrf_token() }}', state_id: state_id, franchise_type: franchise_type }, function(data){
                var obj = JSON.parse(data);
                var html = '<option value="">{{ translate("Select City") }}</option>';
                $('#city_id').html(html + obj);
                $('.aiz-selectpicker').selectpicker('refresh');
                $('#area_id').html('<option value="">{{ translate("Select Area") }}</option>').selectpicker('refresh');
            });
        }

        function get_areas(city_id) {
            var franchise_type = $('#franchise_type').val();
            $.post('{{ route('get-area') }}', { _token: '{{ csrf_token() }}', city_id: city_id, franchise_type: franchise_type }, function(data){
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

        // Auto-select package from query parameter (coming from landing page)
        var urlParams = new URLSearchParams(window.location.search);
        var selectedPackage = urlParams.get('package');
        if (selectedPackage) {
            $('#franchise_package_id').val(selectedPackage);
            $('.aiz-selectpicker').selectpicker('refresh');
        }

        // Auto-select franchise type from query parameter (coming from sub-franchise page)
        var selectedType = urlParams.get('type');
        if (selectedType) {
            $('#franchise_type').val(selectedType);
            $('.aiz-selectpicker').selectpicker('refresh');
            if (selectedType == 'sub_franchise') {
                $('#area_section').removeClass('d-none');
                $('#area_id').prop('required', true);
            }
        }

        // Trigger change to handle pre-selected type
        $('#franchise_type').trigger('change');
    });

    // Custom file input
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@endsection
