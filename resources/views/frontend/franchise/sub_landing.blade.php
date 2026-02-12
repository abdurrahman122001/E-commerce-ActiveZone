@extends('frontend.layouts.app')

@section('meta_title'){{ translate('Sub-Franchise Opportunity') }}@stop

@section('content')

{{-- Hero Section --}}
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #2d1b69 0%, #5b2c8e 50%, #8e44ad 100%); min-height: 480px;">
    <div class="container py-5">
        <div class="row align-items-center py-4">
            <div class="col-lg-7 text-white">
                <span class="badge badge-pill px-3 py-2 mb-3" style="background: rgba(255,255,255,0.15); font-size: 13px;">
                    <i class="las la-map-marker-alt mr-1"></i> {{ translate('Area / Zone / Tehsil Level') }}
                </span>
                <h1 class="fw-700 mb-3" style="font-size: 2.6rem; line-height: 1.2;">
                    {{ translate('Become a Sub-Franchise Partner') }}
                </h1>
                <p class="opacity-80 fs-16 mb-4" style="max-width: 520px; line-height: 1.7;">
                    {{ translate('Start at the zone, area, or tehsil level of a city. Lower investment, dedicated territory, and full support from the city franchise. Perfect for entrepreneurs who want to start small and grow.') }}
                </p>
                <a href="#franchise-packages" class="btn btn-light btn-lg rounded-0 px-4 shadow" style="color: #5b2c8e;">
                    {{ translate('View Packages') }} <i class="las la-arrow-down ml-1"></i>
                </a>
                <a href="{{ route('franchise.registration') }}" class="btn btn-outline-light btn-lg rounded-0 px-4 ml-2" style="color: #fff;">
                    {{ translate('Register Now') }}
                </a>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <div style="font-size: 10rem; opacity: 0.12;">
                    <i class="las la-map-marked-alt text-white"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- What is Sub-Franchise --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-700 fs-28">{{ translate('What is a Sub-Franchise?') }}</h2>
            <p class="text-secondary fs-14 mt-2 mx-auto" style="max-width: 600px;">{{ translate('A Sub-Franchise operates at the area, zone, or tehsil level within a city. You get exclusive rights to your designated zone and operate under the guidance of the city franchise partner.') }}</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3" style="width:60px;height:60px;margin:0 auto;background:rgba(139,92,246,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="las la-map-marked-alt fs-30" style="color:#8b5cf6;"></i>
                        </div>
                        <h5 class="fw-700">{{ translate('Zone / Area Rights') }}</h5>
                        <p class="text-secondary fs-13 mb-0">{{ translate('Get exclusive rights to operate in a specific zone, area, or tehsil of a city. No competition within your territory.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3" style="width:60px;height:60px;margin:0 auto;background:rgba(139,92,246,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="las la-coins fs-30" style="color:#8b5cf6;"></i>
                        </div>
                        <h5 class="fw-700">{{ translate('Lower Investment') }}</h5>
                        <p class="text-secondary fs-13 mb-0">{{ translate('Start with a smaller investment compared to a city franchise. Ideal for first-time entrepreneurs looking for a manageable start.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3" style="width:60px;height:60px;margin:0 auto;background:rgba(139,92,246,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="las la-hands-helping fs-30" style="color:#8b5cf6;"></i>
                        </div>
                        <h5 class="fw-700">{{ translate('City Franchise Support') }}</h5>
                        <p class="text-secondary fs-13 mb-0">{{ translate('Get mentoring and operational support from the city franchise partner. You are never alone in your journey.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- How It Works --}}
<section class="py-5" style="background: #f7f8fa;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-700 fs-28">{{ translate('How Sub-Franchise Works') }}</h2>
            <p class="text-secondary fs-14 mt-2">{{ translate('Your zone, your business, our support') }}</p>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:70px;height:70px;background:linear-gradient(135deg,#8b5cf6,#a78bfa);color:#fff;font-size:1.5rem;font-weight:700;">1</div>
                <h6 class="fw-700">{{ translate('Choose Your Zone') }}</h6>
                <p class="text-secondary fs-13">{{ translate('Select the area, zone, or tehsil of a city where you want to operate your sub-franchise.') }}</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:70px;height:70px;background:linear-gradient(135deg,#8b5cf6,#a78bfa);color:#fff;font-size:1.5rem;font-weight:700;">2</div>
                <h6 class="fw-700">{{ translate('Select a Package') }}</h6>
                <p class="text-secondary fs-13">{{ translate('Pick a franchise package that fits your budget and goals from the options below.') }}</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:70px;height:70px;background:linear-gradient(135deg,#8b5cf6,#a78bfa);color:#fff;font-size:1.5rem;font-weight:700;">3</div>
                <h6 class="fw-700">{{ translate('Register & Apply') }}</h6>
                <p class="text-secondary fs-13">{{ translate('Fill out the registration form with your zone/area details and submit your application.') }}</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:70px;height:70px;background:linear-gradient(135deg,#8b5cf6,#a78bfa);color:#fff;font-size:1.5rem;font-weight:700;">4</div>
                <h6 class="fw-700">{{ translate('Start Selling') }}</h6>
                <p class="text-secondary fs-13">{{ translate('Once approved, start your business in your zone with full platform access and support.') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- Franchise Packages --}}
<section class="py-5 bg-white" id="franchise-packages">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-700 fs-28">{{ translate('Sub-Franchise Packages') }}</h2>
            <p class="text-secondary fs-14 mt-2">{{ translate('Choose a package and register with your zone/area details') }}</p>
        </div>
        <div class="row justify-content-center">
            @forelse($packages as $key => $package)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow h-100 rounded-0 position-relative overflow-hidden package-card" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        @if($key == 1)
                            <div class="position-absolute text-white px-3 py-1 fs-11 fw-700" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa); top:15px; right:-30px; transform: rotate(45deg); width:130px; text-align:center;">
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

                            {{-- Category --}}
                            @if($package->category)
                                <p class="text-center text-secondary fs-12 mb-3">
                                    <i class="las la-tag mr-1"></i>{{ $package->category->getTranslation('name') }}
                                </p>
                            @endif

                            {{-- Price --}}
                            <div class="text-center mb-4">
                                <span class="fw-700" style="font-size: 2.2rem; color: #2d1b69;">{{ single_price($package->price) }}</span>
                                @if($package->duration > 0)
                                    <span class="text-secondary fs-13">/ {{ $package->duration }} {{ translate('Days') }}</span>
                                @endif
                            </div>

                            {{-- Details --}}
                            <ul class="list-unstyled mb-4 flex-grow-1">
                                <li class="d-flex align-items-center py-2 border-bottom">
                                    <i class="las la-box mr-2 fs-18" style="color:#8b5cf6;"></i>
                                    <span class="fs-13">{{ translate('Product Limit') }}: <strong>{{ $package->product_limit > 0 ? $package->product_limit : translate('Unlimited') }}</strong></span>
                                </li>
                                <li class="d-flex align-items-center py-2 border-bottom">
                                    <i class="las la-calendar mr-2 fs-18" style="color:#8b5cf6;"></i>
                                    <span class="fs-13">{{ translate('Duration') }}: <strong>{{ $package->duration > 0 ? $package->duration . ' ' . translate('Days') : translate('Lifetime') }}</strong></span>
                                </li>
                                <li class="d-flex align-items-center py-2 border-bottom">
                                    <i class="las la-map-marker-alt mr-2 fs-18" style="color:#8b5cf6;"></i>
                                    <span class="fs-13"><strong>{{ translate('Zone / Area / Tehsil Level') }}</strong></span>
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
                            <a href="{{ route('franchise.registration', ['package' => $package->id, 'type' => 'sub_franchise']) }}" class="btn {{ $key == 1 ? 'btn-primary' : 'btn-outline-primary' }} btn-block rounded-0 py-2 fw-700" style="{{ $key == 1 ? 'background:#8b5cf6;border-color:#8b5cf6;' : 'color:#8b5cf6;border-color:#8b5cf6;' }}">
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
<section class="py-5" style="background: linear-gradient(135deg, #5b2c8e 0%, #2d1b69 100%);">
    <div class="container text-center text-white py-3">
        <h2 class="fw-700 mb-3">{{ translate('Ready to Own Your Zone?') }}</h2>
        <p class="opacity-80 fs-16 mb-4 mx-auto" style="max-width: 500px;">{{ translate('Apply now for a sub-franchise in your area. Select your zone, pick a package, and start your business today.') }}</p>
        <a href="{{ route('franchise.registration') }}" class="btn btn-light btn-lg rounded-0 px-5 shadow" style="color: #5b2c8e;">
            {{ translate('Apply Now') }} <i class="las la-arrow-right ml-2"></i>
        </a>
    </div>
</section>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('.package-card').hover(
            function(){ $(this).css({'transform':'translateY(-5px)','box-shadow':'0 10px 40px rgba(0,0,0,0.12)'}); },
            function(){ $(this).css({'transform':'translateY(0)','box-shadow':'0 0.5rem 1rem rgba(0,0,0,0.15)'}); }
        );
    });
</script>
@endsection
