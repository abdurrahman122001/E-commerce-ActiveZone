@extends('auth.layouts.authentication')

@section('content')
    <!-- aiz-main-wrapper -->
    <div class="aiz-main-wrapper d-flex flex-column justify-content-md-center bg-white">
        <section class="bg-white overflow-hidden">
            <div class="row">
                <div class="col-xxl-6 col-xl-9 col-lg-10 col-md-7 mx-auto py-lg-4">
                    <div class="card shadow-none rounded-0 border-0">
                        <div class="row no-gutters">
                            <!-- Left Side Image-->
                            <div class="col-lg-6">
                                    <img src="{{ uploaded_asset(get_setting('seller_register_page_image')) }}" alt="" class="img-fit h-100">
                                </div>
                                    
                                <!-- Right Side -->
                                <div class="col-lg-6 p-4 p-lg-5 d-flex flex-column justify-content-center border right-content" style="height: auto;">
                                    <!-- Site Icon -->
                                    <div class="size-48px mb-3 mx-auto mx-lg-0">
                                        <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon')}}" class="img-fit h-100">
                                    </div>

                                    <!-- Titles -->
                                    <div class="text-center text-lg-left">
                                        <h1 class="fs-20 fs-md-24 fw-700 text-primary" style="text-transform: uppercase;">{{ translate('Register your shop')}}</h1>
                                    </div>
                                    <!-- Register form -->
                                    <div class="pt-3 pt-lg-4">
                                        <div class="">
                                            <form id="reg-form" class="form-default" role="form" action="{{ route('shops.store') }}" method="POST">
                                                @csrf

                                                @if($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul class="mb-0">
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                <div class="fs-15 fw-600 pb-2">{{ translate('Personal Info')}}</div>
                                                <!-- Name -->
                                                <div class="form-group">
                                                    <label for="name" class="fs-12 fw-700 text-soft-dark">{{  translate('Your Name') }}</label>
                                                    <input type="text" class="form-control rounded-0{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="{{  translate('Full Name') }}" name="name" required>
                                                    @if ($errors->has('name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="form-group">
                                                    <label>{{ translate('Your Email')}}</label>
                                                    <input type="email" class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ $email ?? old('email') }}" placeholder="{{  translate('Email') }}" name="email" required  {{$email  ? 'readonly' : ''}}>
                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="form-group">
                                                    <label>{{ translate('Your Phone')}}</label>
                                                    <input type="tel" class="form-control rounded-0{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ $phone ?? old('phone') }}" placeholder="{{  translate('Phone') }}" name="phone" required  {{$phone  ? 'readonly' : ''}}>
                                                    @if ($errors->has('phone'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('phone') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- password -->
                                                <div class="form-group mb-0">
                                                    <label for="password" class="fs-12 fw-700 text-soft-dark">{{  translate('Password') }}</label>
                                                    <div class="position-relative">
                                                        <input type="password" class="form-control rounded-0{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{  translate('Password') }}" name="password" required>
                                                        <i class="password-toggle las la-2x la-eye"></i>
                                                    </div>

                                                    @if ($errors->has('password'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- password Confirm -->
                                                <div class="form-group">
                                                    <label for="password_confirmation" class="fs-12 fw-700 text-soft-dark">{{  translate('Confirm Password') }}</label>
                                                    <div class="position-relative">
                                                        <input type="password" class="form-control rounded-0" placeholder="{{  translate('Confirm Password') }}" name="password_confirmation" required>
                                                        <i class="password-toggle las la-2x la-eye"></i>
                                                    </div>
                                                </div>


                                                <div class="fs-15 fw-600 py-2">{{ translate('Basic Info')}}</div>
                                                
                                                <div class="form-group">
                                                    <label for="shop_name" class="fs-12 fw-700 text-soft-dark">{{  translate('Shop Name') }}</label>
                                                    <input type="text" class="form-control rounded-0{{ $errors->has('shop_name') ? ' is-invalid' : '' }}" value="{{ old('shop_name') }}" placeholder="{{  translate('Shop Name') }}" name="shop_name" required>
                                                    @if ($errors->has('shop_name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('shop_name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="form-group">
                                                    <label for="state_id" class="fs-12 fw-700 text-soft-dark">{{  translate('State') }}</label>
                                                    <select class="form-control aiz-selectpicker rounded-0" name="state_id" id="state_id" data-live-search="true" required>
                                                        <option value="">{{ translate('Select State') }}</option>
                                                        @foreach ($states as $state)
                                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="city_id" class="fs-12 fw-700 text-soft-dark">{{  translate('City') }}</label>
                                                    <select class="form-control aiz-selectpicker rounded-0" name="city_id" id="city_id" data-live-search="true" required>
                                                        <option value="">{{ translate('Select City') }}</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="area_id" class="fs-12 fw-700 text-soft-dark">{{  translate('Area') }}</label>
                                                    <select class="form-control aiz-selectpicker rounded-0" name="area_id" id="area_id" data-live-search="true" required>
                                                        <option value="">{{ translate('Select Area') }}</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="address" class="fs-12 fw-700 text-soft-dark">{{  translate('Address') }}</label>
                                                    <input type="text" class="form-control rounded-0{{ $errors->has('address') ? ' is-invalid' : '' }}" value="{{ old('address') }}" placeholder="{{  translate('Address') }}" name="address" required>
                                                    @if ($errors->has('address'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('address') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="form-group">
                                                    <label for="referral_code" class="fs-12 fw-700 text-soft-dark">{{  translate('Referral Code') }}</label>
                                                    <input type="text" class="form-control rounded-0" value="{{ old('referral_code', Cookie::get('vendor_referral_code')) }}" placeholder="{{  translate('Referral Code') }}" name="referral_code">
                                                    <small class="text-muted">{{ translate('Optional: Enter the referral code if you were referred by another vendor.') }}</small>
                                                </div>

                                                <!-- Recaptcha -->
                                                @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_seller_register') == 1)
                                                    
                                                    @if ($errors->has('g-recaptcha-response'))
                                                        <span class="border invalid-feedback rounded p-2 mb-3 bg-danger text-white" role="alert" style="display: block;">
                                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                        </span>
                                                    @endif
                                                @endif
                                            
                                                <!-- Submit Button -->
                                                <div class="mb-4 mt-4">
                                                    <button type="submit" class="btn btn-primary btn-block fw-600 rounded-0">{{  translate('Register Your Shop') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- Log In -->
                                        <p class="fs-12 text-gray mb-0">
                                            {{ translate('Already have an account?')}}
                                            <a href="{{ route('seller.login') }}" class="ml-2 fs-14 fw-700 animate-underline-primary">{{ translate('Log In')}}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Go Back -->
                        <div class="mt-3 mr-4 mr-md-0">
                            <a href="{{ url()->previous() }}" class="ml-auto fs-14 fw-700 d-flex align-items-center text-primary" style="max-width: fit-content;">
                                <i class="las la-arrow-left fs-20 mr-1"></i>
                                {{ translate('Back to Previous Page')}}
                            </a>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection

@section('script')
    @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_seller_register') == 1)
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}"></script>
        
        <script type="text/javascript">
                document.getElementById('reg-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    grecaptcha.ready(function() {
                        grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {action: 'selller_registration'}).then(function(token) {
                            var input = document.createElement('input');
                            input.setAttribute('type', 'hidden');
                            input.setAttribute('name', 'g-recaptcha-response');
                            input.setAttribute('value', token);
                            e.target.appendChild(input);

                            e.target.submit();
                        });
                    });
                });
        </script>
    @endif

    <script type="text/javascript">
        $(document).on('change', '#state_id', function() {
            var state_id = $(this).val();
            $.post('{{ route('get-city') }}', {_token:'{{ csrf_token() }}', state_id:state_id}, function(data){
                $('#city_id').html(data);
                AIZ.plugins.bootstrapSelect('refresh');
            });
        });

        $(document).on('change', '#city_id', function() {
            var city_id = $(this).val();
            $.post('{{ route('get-area') }}', {_token:'{{ csrf_token() }}', city_id:city_id}, function(data){
                $('#area_id').html(data);
                AIZ.plugins.bootstrapSelect('refresh');
            });
        });
    </script>
@endsection