@extends('frontend.layouts.app')

@section('meta_title'){{ translate('State Franchise Opportunity') }}@stop

@section('content')

{{-- Hero Section --}}
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #1a1c2e 0%, #16213e 50%, #0f3460 100%); min-height: 480px;">
    <div class="container py-5">
        <div class="row align-items-center py-4">
            <div class="col-lg-7 text-white">
                <span class="badge badge-pill px-3 py-2 mb-3" style="background: rgba(255,255,255,0.15); font-size: 13px;">
                    <i class="las la-globe-americas mr-1"></i> {{ translate('State Level Partnership') }}
                </span>
                <h1 class="fw-700 mb-3" style="font-size: 2.6rem; line-height: 1.2;">
                    {{ translate('Become a State Franchise Partner') }}
                </h1>
                <p class="opacity-80 fs-16 mb-4" style="max-width: 520px; line-height: 1.7;">
                    {{ translate('Take command of an entire state. As a State Franchise, you represent our brand at the highest regional level, managing city franchises and sub-franchises across your territory.') }}
                </p>
                <div class="d-flex align-items-center">
                    <a href="#franchise-packages" class="btn btn-primary btn-lg rounded-0 px-4 shadow">
                        {{ translate('View State Packages') }} <i class="las la-arrow-down ml-1"></i>
                    </a>
                    <a href="{{ route('franchise.state_registration') }}" class="btn btn-outline-light btn-lg rounded-0 px-4 ml-3" style="color: #fff; border-color: rgba(255,255,255,0.5);">
                        {{ translate('Join as State Head') }}
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <div style="font-size: 12rem; opacity: 0.15;">
                    <i class="las la-map-marked text-white animate-pulse"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Why Join Section --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-700 fs-28">{{ translate('Supreme Benefits for State Franchise') }}</h2>
            <p class="text-secondary fs-14 mt-2">{{ translate('Exclusive state-level advantages and revenue streams') }}</p>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3" style="width:60px;height:60px;margin:0 auto;background:rgba(var(--primary-rgb,55,125,255),0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="las la-crown fs-30 text-primary"></i>
                        </div>
                        <h5 class="fw-700">{{ translate('Regional Hegemony') }}</h5>
                        <p class="text-secondary fs-13 mb-0">{{ translate('Complete authority over city and sub-franchise networks within the selected state.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3" style="width:60px;height:60px;margin:0 auto;background:rgba(var(--primary-rgb,55,125,255),0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="las la-percentage fs-30 text-primary"></i>
                        </div>
                        <h5 class="fw-700">{{ translate('Override Commissions') }}</h5>
                        <p class="text-secondary fs-13 mb-0">{{ translate('Earn passive income from every package purchase and every sale made within your state.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3" style="width:60px;height:60px;margin:0 auto;background:rgba(var(--primary-rgb,55,125,255),0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="las la-users-cog fs-30 text-primary"></i>
                        </div>
                        <h5 class="fw-700">{{ translate('Network Management') }}</h5>
                        <p class="text-secondary fs-13 mb-0">{{ translate('Dedicated tools to oversee and optimize the performance of your entire state network.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Franchise Hierarchy --}}
<section class="py-5" style="background: #f7f8fa;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-700 fs-28">{{ translate('Franchise Ecosystem') }}</h2>
            <p class="text-secondary fs-14 mt-2">{{ translate('Understand where the State Franchise fits in our structure') }}</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0 border-top border-primary border-4" style="background: #fff;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3" style="width:50px;height:50px;background:linear-gradient(135deg,#1a1a2e,#0f3460);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                <i class="las la-globe text-white fs-24"></i>
                            </div>
                            <h5 class="fw-700 mb-0 opacity-80">{{ translate('State Franchise') }}</h5>
                        </div>
                        <p class="text-secondary fs-13 mb-3">{{ translate('The top-tier regional partner. Manages multiple cities and areas. Highest commission structure.') }}</p>
                        <ul class="list-unstyled fs-12 text-secondary mb-0">
                            <li class="mb-1"><i class="las la-check text-success mr-1"></i> {{ translate('Overrides all city/sub sales') }}</li>
                            <li class="mb-1"><i class="las la-check text-success mr-1"></i> {{ translate('Package commission referral') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0" style="opacity: 0.8; background: #fff;">
                    <div class="card-body p-4">
                         <div class="d-flex align-items-center mb-3">
                            <div class="mr-3" style="width:40px;height:40px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <i class="las la-city text-white fs-20"></i>
                            </div>
                            <h6 class="fw-700 mb-0">{{ translate('City Franchise') }}</h6>
                        </div>
                        <p class="text-secondary fs-12 mb-0">{{ translate('Tier-2 partner. Manages an entire city. Reports to State Franchise.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0" style="opacity: 0.8; background: #fff;">
                    <div class="card-body p-4">
                         <div class="d-flex align-items-center mb-3">
                            <div class="mr-3" style="width:40px;height:40px;background:linear-gradient(135deg,#f093fb,#f5576c);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <i class="las la-map-marker-alt text-white fs-20"></i>
                            </div>
                            <h6 class="fw-700 mb-0">{{ translate('Sub-Franchise') }}</h6>
                        </div>
                        <p class="text-secondary fs-12 mb-0">{{ translate('Tier-3 partner. Manages specific Area/Taluka. Reports to City & State.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- State Packages --}}
<section class="py-5 bg-white" id="franchise-packages">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-700 fs-28">{{ translate('State Franchise Plans') }}</h2>
            <p class="text-secondary fs-14 mt-2">{{ translate('Exclusive premium packages for our State level partners') }}</p>
        </div>
        <div class="row justify-content-center">
            @forelse($packages as $key => $package)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-lg h-100 rounded-0 position-relative overflow-hidden package-card" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        @if($key == 0)
                            <div class="position-absolute text-white px-3 py-1 fs-11 fw-700" style="background: linear-gradient(135deg, #1a1a2e, #ff6b6b); top:15px; right:-30px; transform: rotate(45deg); width:130px; text-align:center; z-index: 10;">
                                {{ translate('Premium') }}
                            </div>
                        @endif
                        <div class="card-body p-4 d-flex flex-column">
                            @if($package->logo)
                                <div class="text-center mb-4">
                                    <div class="p-2 d-inline-block bg-light rounded">
                                        <img src="{{ uploaded_asset($package->logo) }}" alt="{{ $package->getTranslation('name') }}" class="img-fluid" style="max-height: 80px;" onerror="this.style.display='none'">
                                    </div>
                                </div>
                            @endif

                            <h4 class="fw-700 text-center mb-2 text-primary">{{ $package->getTranslation('name') }}</h4>

                            <div class="text-center mb-4">
                                <span class="fw-700" style="font-size: 2.2rem; color: #1a1a2e;">{{ single_price($package->price) }}</span>
                                <div class="text-muted fs-12 text-uppercase ls-1 fw-600">{{ $package->duration > 0 ? $package->duration . ' ' . translate('Days Validity') : translate('Lifetime Validity') }}</div>
                            </div>

                            <ul class="list-unstyled mb-4 flex-grow-1">
                                @if($package->features)
                                    @foreach(array_filter(array_map('trim', preg_split('/[\n,]+/', $package->features))) as $feature)
                                        <li class="d-flex align-items-center py-2 border-bottom">
                                            <i class="las la-check-circle text-success mr-2 fs-18"></i>
                                            <span class="fs-13">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="d-flex align-items-center py-2 border-bottom">
                                        <i class="las la-check-circle text-success mr-2 fs-18"></i>
                                        <span class="fs-13">{{ translate('Full State Access') }}</span>
                                    </li>
                                    <li class="d-flex align-items-center py-2 border-bottom">
                                        <i class="las la-check-circle text-success mr-2 fs-18"></i>
                                        <span class="fs-13">{{ translate('Referral Commissions') }}</span>
                                    </li>
                                @endif
                            </ul>

                            <a href="{{ route('franchise.state_registration', ['package' => $package->id]) }}" class="btn btn-primary btn-block rounded-0 py-2 fw-700 shadow-sm">
                                {{ translate('Apply for State Franchise') }} <i class="las la-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">{{ translate('No state franchise packages currently available.') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Final CTA --}}
<section class="py-5" style="background: #1a1a2e;">
    <div class="container text-center text-white">
        <h2 class="fw-700 mb-3">{{ translate('Take Ownership of Your State') }}</h2>
        <p class="opacity-70 mb-4">{{ translate('Limited state franchises available. Start your premium partnership today.') }}</p>
        <a href="{{ route('franchise.state_registration') }}" class="btn btn-primary btn-lg rounded-0 px-5">
            {{ translate('Get Started Now') }}
        </a>
    </div>
</section>

@endsection

@section('style')
<style>
    .animate-pulse {
        animation: pulse 3s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.15; }
        50% { transform: scale(1.05); opacity: 0.25; }
        100% { transform: scale(1); opacity: 0.15; }
    }
</style>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('.package-card').hover(
            function(){ $(this).css({'transform':'translateY(-10px)','box-shadow':'0 15px 45px rgba(0,0,0,0.15)'}); },
            function(){ $(this).css({'transform':'translateY(0)','box-shadow':'0 0.5rem 1rem rgba(0,0,0,0.15)'}); }
        );
    });
</script>
@endsection
