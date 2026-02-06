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
                                            <option value="city_franchise" {{ old('franchise_type') == 'city_franchise' ? 'selected' : '' }}>{{ translate('City Franchise (Master Franchise)') }}</option>
                                            <option value="sub_franchise" {{ old('franchise_type') == 'sub_franchise' ? 'selected' : '' }}>{{ translate('Sub-Franchise (Area/Zone/Tehsil)') }}</option>
                                        </select>
                                        @error('franchise_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('City') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <select class="form-control aiz-selectpicker @error('city_id') is-invalid @enderror" name="city_id" id="city_id" data-live-search="true" required>
                                            <option value="">{{ translate('Select City') }}</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->getTranslation('name') }}</option>
                                            @endforeach
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
                                    <label class="col-md-3 col-form-label">{{ translate('Investment Capacity (₹)') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control rounded-0 @error('investment_capacity') is-invalid @enderror" name="investment_capacity" value="{{ old('investment_capacity') }}" step="0.01" placeholder="{{ translate('Investment Capacity') }}" required>
                                        @error('investment_capacity')
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

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('ID Proof (Aadhar/PAN)') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('id_proof') is-invalid @enderror" name="id_proof" id="id_proof" required>
                                            <label class="custom-file-label" for="id_proof">{{ translate('Choose file') }}</label>
                                        </div>
                                        @error('id_proof')
                                            <div class="text-danger mt-1 fs-12">{{ $message }}</div>
                                        @enderror
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
                $('#area_section').removeClass('d-none');
                $('#area_id').prop('required', true);
            } else {
                $('#area_section').addClass('d-none');
                $('#area_id').prop('required', false);
            }
        });

        $('#city_id').change(function(){
            var city_id = $(this).val();
            get_areas(city_id);
        });

        function get_areas(city_id) {
            $.post('{{ route('get-area') }}', { _token: '{{ csrf_token() }}', city_id: city_id }, function(data){
                var obj = JSON.parse(data);
                $('#area_id').html(obj);
                $('.aiz-selectpicker').selectpicker('refresh');
            });
        }
    });

    // Custom file input
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@endsection
