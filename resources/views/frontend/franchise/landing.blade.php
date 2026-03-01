@extends('frontend.layouts.app')

@section('meta_title'){{ translate('Franchise Opportunity') }}@stop

@section('content')

{{-- Hero Section --}}
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 480px;">
    <div class="container py-5">
        <div class="row align-items-center py-4">
            <div class="col-lg-7 text-white">
                <span class="badge badge-pill px-3 py-2 mb-3" style="background: rgba(255,255,255,0.15); font-size: 13px;">
                    <i class="las la-handshake mr-1"></i> {{ translate('Join Our Network') }}
                </span>
                <h1 class="fw-700 mb-3" style="font-size: 2.6rem; line-height: 1.2;">
                    {{ translate('Become a Franchise Partner') }}
                </h1>
                <p class="opacity-80 fs-16 mb-4" style="max-width: 520px; line-height: 1.7;">
                    {{ translate('Start your own business with our proven franchise model. We provide complete support, training, and a ready-to-go e-commerce platform. Choose from our flexible packages and start your journey today.') }}
                </p>
                <a href="#franchise-packages" class="btn btn-primary btn-lg rounded-0 px-4 shadow">
                    {{ translate('View Packages') }} <i class="las la-arrow-down ml-1"></i>
                </a>
                <a href="{{ route('franchise.registration') }}" class="btn btn-outline-light btn-lg rounded-0 px-4 ml-2" style="color: #fff;">
                    {{ translate('Register Now') }}
                </a>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <div style="font-size: 10rem; opacity: 0.12;">
                    <i class="las la-store-alt text-white"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Why Join Section --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-700 fs-28">{{ translate('Why Join Our Franchise?') }}</h2>
            <p class="text-secondary fs-14 mt-2">{{ translate('Discover the benefits of partnering with us') }}</p>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3" style="width:60px;height:60px;margin:0 auto;background:rgba(var(--primary-rgb,55,125,255),0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="las la-rocket fs-30 text-primary"></i>
                        </div>
                        <h5 class="fw-700">{{ translate('Quick Setup') }}</h5>
                        <p class="text-secondary fs-13 mb-0">{{ translate('Get started quickly with our turnkey solution. We handle the technology so you can focus on growing your business.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3" style="width:60px;height:60px;margin:0 auto;background:rgba(var(--primary-rgb,55,125,255),0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="las la-chalkboard-teacher fs-30 text-primary"></i>
                        </div>
                        <h5 class="fw-700">{{ translate('Full Training & Support') }}</h5>
                        <p class="text-secondary fs-13 mb-0">{{ translate('Comprehensive training program and ongoing support to ensure your franchise runs smoothly and profitably.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3" style="width:60px;height:60px;margin:0 auto;background:rgba(var(--primary-rgb,55,125,255),0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="las la-chart-line fs-30 text-primary"></i>
                        </div>
                        <h5 class="fw-700">{{ translate('Proven Business Model') }}</h5>
                        <p class="text-secondary fs-13 mb-0">{{ translate('Leverage our established brand and proven business strategies to maximize your revenue and grow your customer base.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3" style="width:60px;height:60px;margin:0 auto;background:rgba(var(--primary-rgb,55,125,255),0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="las la-map-marked-alt fs-30 text-primary"></i>
                        </div>
                        <h5 class="fw-700">{{ translate('Exclusive Territory') }}</h5>
                        <p class="text-secondary fs-13 mb-0">{{ translate('Get exclusive rights to operate in your city or area, ensuring you have a dedicated market with no internal competition.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3" style="width:60px;height:60px;margin:0 auto;background:rgba(var(--primary-rgb,55,125,255),0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="las la-boxes fs-30 text-primary"></i>
                        </div>
                        <h5 class="fw-700">{{ translate('Product Catalog Access') }}</h5>
                        <p class="text-secondary fs-13 mb-0">{{ translate('Access our extensive product catalog with thousands of products ready to sell. No need to source products yourself.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3" style="width:60px;height:60px;margin:0 auto;background:rgba(var(--primary-rgb,55,125,255),0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="las la-headset fs-30 text-primary"></i>
                        </div>
                        <h5 class="fw-700">{{ translate('Dedicated Support') }}</h5>
                        <p class="text-secondary fs-13 mb-0">{{ translate('Our dedicated team is always available to help you with any technical, operational, or business-related queries.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Franchise Types --}}
<section class="py-5" style="background: #f7f8fa;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-700 fs-28">{{ translate('Franchise Types') }}</h2>
            <p class="text-secondary fs-14 mt-2">{{ translate('Choose the franchise model that fits you best') }}</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3" style="width:50px;height:50px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                <i class="las la-city text-white fs-24"></i>
                            </div>
                            <h5 class="fw-700 mb-0">{{ translate('City Franchise') }}</h5>
                        </div>
                        <p class="text-secondary fs-13 mb-3">{{ translate('Become the master franchise partner for an entire city. Manage operations, build a team, and serve the complete city market under our brand.') }}</p>
                        <ul class="list-unstyled fs-13 text-secondary">
                            <li class="mb-2"><i class="las la-check-circle text-success mr-1"></i> {{ translate('Full city-level rights') }}</li>
                            <li class="mb-2"><i class="las la-check-circle text-success mr-1"></i> {{ translate('Manage sub-franchises') }}</li>
                            <li class="mb-2"><i class="las la-check-circle text-success mr-1"></i> {{ translate('Higher earning potential') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3" style="width:50px;height:50px;background:linear-gradient(135deg,#f093fb,#f5576c);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                <i class="las la-map-marker-alt text-white fs-24"></i>
                            </div>
                            <h5 class="fw-700 mb-0">{{ translate('Sub-Franchise') }}</h5>
                        </div>
                        <p class="text-secondary fs-13 mb-3">{{ translate('Operate at the area, zone, or tehsil level. Perfect for entrepreneurs who want to start small and grow within a defined territory.') }}</p>
                        <ul class="list-unstyled fs-13 text-secondary">
                            <li class="mb-2"><i class="las la-check-circle text-success mr-1"></i> {{ translate('Area/Zone level rights') }}</li>
                            <li class="mb-2"><i class="las la-check-circle text-success mr-1"></i> {{ translate('Lower investment required') }}</li>
                            <li class="mb-2"><i class="las la-check-circle text-success mr-1"></i> {{ translate('Backed by city franchise') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Franchise Packages --}}
<section class="py-5 bg-white" id="franchise-packages">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-700 fs-28">{{ translate('Our Franchise Packages') }}</h2>
            <p class="text-secondary fs-14 mt-2">{{ translate('Select the package that suits your business goals') }}</p>
        </div>
        <div class="row justify-content-center">
            @forelse($packages as $key => $package)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow h-100 rounded-0 position-relative overflow-hidden package-card" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        @if($key == 1)
                            <div class="position-absolute text-white px-3 py-1 fs-11 fw-700" style="background: linear-gradient(135deg, #f5576c, #ff6b6b); top:15px; right:-30px; transform: rotate(45deg); width:130px; text-align:center;">
                                {{ translate('Popular') }}
                            </div>
                        @endif
                        <div class="card-body p-4 d-flex flex-column">
                            {{-- Package Logo --}}
                            @if($package->logo)
                                <div class="text-center mb-3">
                                    <img src="{{ uploaded_asset($package->logo) }}" alt="{{ $package->getTranslation('name') }}" class="img-fluid" style="max-height: 60px;" onerror="this.style.display='none'">
                                </div>
                            @endif

                            {{-- Package Name --}}
                            <h4 class="fw-700 text-center mb-2">{{ $package->getTranslation('name') }}</h4>

                            {{-- Price --}}
                            <div class="text-center mb-4">
                                <span class="fw-700" style="font-size: 2.2rem; color: #1a1a2e;">{{ single_price($package->price) }}</span>
                                @if($package->duration > 0)
                                    <span class="text-secondary fs-13">/ {{ $package->duration }} {{ translate('Days') }}</span>
                                @endif
                            </div>

                            {{-- Details --}}
                            <ul class="list-unstyled mb-4 flex-grow-1">
                                <li class="d-flex align-items-center py-2 border-bottom">
                                    <i class="las la-calendar text-primary mr-2 fs-18"></i>
                                    <span class="fs-13">{{ translate('Duration') }}: <strong>{{ $package->duration > 0 ? $package->duration . ' ' . translate('Days') : translate('Lifetime') }}</strong></span>
                                </li>
                                @if($package->features)
                                    @foreach(array_filter(array_map('trim', preg_split('/[\n,]+/', $package->features))) as $feature)
                                        <li class="d-flex align-items-center py-2 border-bottom">
                                            <i class="las la-check-circle text-success mr-2 fs-18"></i>
                                            <span class="fs-13">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>

                            {{-- CTA Button --}}
                            <a href="{{ route('franchise.registration', ['package' => $package->id]) }}" class="btn {{ $key == 1 ? 'btn-primary' : 'btn-outline-primary' }} btn-block rounded-0 py-2 fw-700">
                                {{ translate('Choose This Package') }} <i class="las la-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="las la-box-open fs-60 text-secondary opacity-50"></i>
                        <p class="text-secondary fs-16 mt-3">{{ translate('No packages available at the moment. Please check back later.') }}</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-5" style="background: linear-gradient(135deg, #0f3460 0%, #1a1a2e 100%);">
    <div class="container text-center text-white py-3">
        <h2 class="fw-700 mb-3">{{ translate('Ready to Start Your Franchise Journey?') }}</h2>
        <p class="opacity-80 fs-16 mb-4 mx-auto" style="max-width: 500px;">{{ translate('Apply now and take the first step towards building your own successful business with our franchise program.') }}</p>
        <a href="{{ route('franchise.registration') }}" class="btn btn-primary btn-lg rounded-0 px-5 shadow">
            {{ translate('Apply Now') }} <i class="las la-arrow-right ml-2"></i>
        </a>
    </div>
</section>

@endsection

@section('script')
<script>
    // Package card hover effect
    $(document).ready(function(){
        $('.package-card').hover(
            function(){ $(this).css({'transform':'translateY(-5px)','box-shadow':'0 10px 40px rgba(0,0,0,0.12)'}); },
            function(){ $(this).css({'transform':'translateY(0)','box-shadow':'0 0.5rem 1rem rgba(0,0,0,0.15)'}); }
        );
    });
</script>
@endsection
