@extends('franchise.layouts.app')

@section('panel_content')
    @php 
        $authUser = auth()->user(); 
        $status = 'pending';
        if ($authUser->user_type == 'franchise') {
            $status = $authUser->franchise ? $authUser->franchise->status : 'pending';
        } elseif ($authUser->user_type == 'sub_franchise') {
            $status = $authUser->sub_franchise ? $authUser->sub_franchise->status : 'pending';
        }
    @endphp

    {{-- Pending Approval Alert --}}
    @if($status != 'approved')
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="las la-exclamation-triangle la-2x mr-3"></i>
                <div>
                    <strong>{{ translate('Account Pending Approval') }}</strong>
                    <p class="mb-0">{{ translate('Your account is currently unverified. Please wait for admin approval to access all features. Some functionality may be limited until your account is approved.') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 text-primary">{{ translate('Dashboard') }}</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4 bg-primary ">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14 text-light">{{ translate('Products') }}</span>
                            </p>
                            <h3 class="mb-0 text-white fs-30">
                                {{ $total_products }}
                            </h3>

                        </div>
                        <div class="col-auto text-right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64.001" height="64" viewBox="0 0 64.001 64">
                                <path id="Path_66" data-name="Path 66"
                                    d="M146.431,117.56l-26.514-10.606a8.014,8.014,0,0,0-5.944,0L87.458,117.56a4,4,0,0,0-2.514,3.714v34.217a4,4,0,0,0,2.514,3.714l26.514,10.606a8.013,8.013,0,0,0,5.944,0L146.431,159.2a4,4,0,0,0,2.514-3.714V121.274a4,4,0,0,0-2.514-3.714m-31.714-8.748a5.981,5.981,0,0,1,4.456,0l26.1,10.44a1,1,0,0,1,0,1.858l-12.332,4.932-30.654-12.26Zm1.228,59.633L88.2,157.347a2,2,0,0,1-1.258-1.856V122.6l29,11.6Zm1-36L88.612,121.11a1,1,0,0,1,0-1.858L99.6,114.858l30.654,12.262Zm30,23.048a2,2,0,0,1-1.258,1.856l-27.742,11.1V134.2l13-5.2V146.61a1.035,1.035,0,0,0,2-.466V128.2l14-5.6Z"
                                    transform="translate(-84.944 -106.382)" fill="#FFFFFF" />
                            </svg>
                        </div>
                    </div>

                    @if($status == 'approved')
                     <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('franchise.products.create') }}">
                        <div class="d-flex align-items-center">
                            <i class="las la-plus la-1x text-white"></i>
                            <p class="fs-12 text-light my-2 ml-1">{{ translate('Add New Product') }}</p>
                        </div>
                        </a>
                     </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4 bg-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14 text-light">{{ translate('Rating') }}</span>
                            </p>
                            <h3 class="mb-0 text-white fs-30">
                                {{ $authUser->shop?->rating ?? 0 }}
                            </h3>
                        </div>
                        <div class="col-auto text-right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="61.143" viewBox="0 0 64 61.143">
                                <path id="Path_57" data-name="Path 57"
                                    d="M63.286,22.145a2.821,2.821,0,0,0-1.816-.926L43.958,19.455a2.816,2.816,0,0,1-2.294-1.666L34.574,1.68a2.813,2.813,0,0,0-5.148,0l-7.09,16.11a2.813,2.813,0,0,1-2.292,1.666L2.53,21.219a2.813,2.813,0,0,0-1.59,4.9l13.13,11.72a2.818,2.818,0,0,1,.876,2.7l-3.734,17.2a2.812,2.812,0,0,0,4.166,3.026L30.584,51.9a2.8,2.8,0,0,1,2.832,0l15.206,8.864a2.813,2.813,0,0,0,4.166-3.026l-3.734-17.2a2.818,2.818,0,0,1,.876-2.7l13.13-11.72a2.813,2.813,0,0,0,.226-3.972m-1.5,2.546L48.658,36.413a4.717,4.717,0,0,0-1.47,4.524l3.732,17.2a.9.9,0,0,1-1.336.97l-15.2-8.866a4.729,4.729,0,0,0-4.758,0L14.416,59.109a.9.9,0,0,1-1.336-.97l3.732-17.2a4.717,4.717,0,0,0-1.47-4.524L2.212,24.691a.9.9,0,0,1,.51-1.57l17.512-1.766a4.721,4.721,0,0,0,3.85-2.8l7.09-16.11a.9.9,0,0,1,1.652,0l7.09,16.11a4.721,4.721,0,0,0,3.85,2.8l17.512,1.766a.9.9,0,0,1,.51,1.57"
                                    transform="translate(0 0)" fill="#FFFFFF" />
                            </svg>
                        </div> 
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <div class="d-flex align-items-center pt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="Group_38928" data-name="Group 38928" transform="translate(-9435 -1195)">
                                    <g id="Layer_2" data-name="Layer 2" transform="translate(9435 1195)">
                                        <g id="people">
                                            <rect id="Rectangle_23088" data-name="Rectangle 23088" width="16" height="16" fill="#fff" opacity="0"/>
                                            <path id="Path_45108" data-name="Path 45108" d="M6.667,8.333A2.667,2.667,0,1,0,4,5.667,2.667,2.667,0,0,0,6.667,8.333Zm0-4A1.333,1.333,0,1,1,5.333,5.667,1.333,1.333,0,0,1,6.667,4.333ZM12,9.667a2,2,0,1,0-2-2A2,2,0,0,0,12,9.667ZM12,7a.667.667,0,1,1-.667.667A.667.667,0,0,1,12,7Zm0,3.333a3.333,3.333,0,0,0-2.04.7A4.667,4.667,0,0,0,2,14.333a.667.667,0,1,0,1.333,0,3.333,3.333,0,0,1,6.667,0,.667.667,0,0,0,1.333,0A4.6,4.6,0,0,0,10.76,12.1,2,2,0,0,1,14,13.667a.667.667,0,1,0,1.333,0A3.333,3.333,0,0,0,12,10.333Z" transform="translate(-0.667 -1)" fill="#fff"/>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                            <p class="fs-12 text-light my-2 ml-1">{{ translate('Followers').' '.($authUser->shop?->followers()->count() ?? 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4 bg-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14 text-light">{{ translate('Total Order') }}</span>
                            </p>
                            <h3 class="mb-0 text-white fs-30">
                                {{ $delivered_orders }}
                            </h3>
                        </div>
                        <div class="col-auto text-right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">
                                <g id="Group_25" data-name="Group 25" transform="translate(-1561.844 1020.618)">
                                    <path id="Path_58" data-name="Path 58"
                                        d="M229.23,106.382h-12a6,6,0,0,0,0,12h12a6,6,0,0,0,0-12m0,10h-12a4,4,0,0,1,0-8h12a4,4,0,0,1,0,8"
                                        transform="translate(1370.615 -1127)" fill="#FFFFFF" />
                                    <path id="Path_59" data-name="Path 59"
                                        d="M213.73,117.882h24a1,1,0,0,1,0,2h-24a1,1,0,0,1,0-2"
                                        transform="translate(1372.115 -1115.5)" fill="#FFFFFF" />
                                    <path id="Path_60" data-name="Path 60" d="M210.23,117.382a2,2,0,1,0,2,2,2,2,0,0,0-2-2"
                                        transform="translate(1367.615 -1116)" fill="#FFFFFF" />
                                    <line id="Line_1" data-name="Line 1" transform="translate(1578.047 -1014.618)"
                                        fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="0.142" />
                                    <line id="Line_2" data-name="Line 2" transform="translate(1609.643 -1014.618)"
                                        fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="0.142" />
                                    <path id="Path_61" data-name="Path 61"
                                        d="M213.73,123.882h24a1,1,0,0,1,0,2h-24a1,1,0,0,1,0-2"
                                        transform="translate(1372.115 -1109.5)" fill="#FFFFFF" />
                                    <path id="Path_62" data-name="Path 62" d="M210.23,123.382a2,2,0,1,0,2,2,2,2,0,0,0-2-2"
                                        transform="translate(1367.615 -1110)" fill="#FFFFFF" />
                                    <path id="Path_63" data-name="Path 63"
                                        d="M213.73,129.882h24a1,1,0,0,1,0,2h-24a1,1,0,1,1,0-2"
                                        transform="translate(1372.115 -1103.5)" fill="#FFFFFF" />
                                    <path id="Path_64" data-name="Path 64" d="M210.23,129.382a2,2,0,1,0,2,2,2,2,0,0,0-2-2"
                                        transform="translate(1367.615 -1104)" fill="#FFFFFF" />
                                    <line id="Line_3" data-name="Line 3" transform="translate(1609.643 -1015.618)"
                                        fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="0.142" />
                                    <line id="Line_4" data-name="Line 4" transform="translate(1578.047 -1015.618)"
                                        fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="0.142" />
                                    <path id="Path_65" data-name="Path 65"
                                        d="M265.23,116.382a8,8,0,0,0-8-8h-7.2a1,1,0,0,0,0,2h7.2a6,6,0,0,1,6,6v44a6,6,0,0,1-6,6h-48a6,6,0,0,1-6-6v-44a6,6,0,0,1,6-6h7.2a1,1,0,0,0,0-2h-7.2a8,8,0,0,0-8,8v44a8,8,0,0,0,8,8h48a8,8,0,0,0,8-8Z"
                                        transform="translate(1360.615 -1125)" fill="#FFFFFF" />
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('franchise.dashboard') }}">
                        <div class="d-flex align-items-center">
                            <i class="las la-eye la-1x text-white"></i>
                            <p class="fs-12 text-light my-2 ml-1">{{ translate('View Dashboard') }}</p>
                        </div>
                        </a>
                     </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4 bg-primary ">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14 text-light">{{ translate('Total Sales') }}</span>
                            </p>
                            <h3 class="mb-0 text-white fs-30">
                                {{ single_price($total_sales) }}
                            </h3>

                        </div>
                        <div class="col-auto text-right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64.001" viewBox="0 0 64 64.001">
                                <g id="Group_26" data-name="Group 26" transform="translate(-1571.385 1123.29)">
                                    <line id="Line_5" data-name="Line 5" transform="translate(1572.385 -1123.29)"
                                        fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="0.142" />
                                    <path id="Path_67" data-name="Path 67"
                                        d="M214.771,65.71a2,2,0,0,1-2-2v-59a1,1,0,0,0-2,0v59a4,4,0,0,0,4,4h59a1,1,0,0,0,0-2Z"
                                        transform="translate(1360.615 -1127)" fill="#FFFFFF" />
                                    <line id="Line_6" data-name="Line 6" y1="0.136" x2="0.136"
                                        transform="translate(1586.533 -1087.117)" fill="none" stroke="red"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="0.142" />
                                    <path id="Path_68" data-name="Path 68"
                                        d="M264.6,10.027a3,3,0,0,0-4,4L247.536,27.1a2.994,2.994,0,0,0-2.594,0l-6.584-6.584a3,3,0,1,0-5.414,0L221.528,31.927a3,3,0,1,0,1.412,1.418l11.418-11.418a3,3,0,0,0,2.586,0l6.586,6.586a3,3,0,1,0,5.418,0l13.072-13.07a3,3,0,0,0,2.584-5.416M220.23,35.633a1,1,0,1,1,1-1,1,1,0,0,1-1,1m15.42-15.414a1,1,0,1,1,1-1,1,1,0,0,1-1,1M246.238,30.8a1,1,0,1,1,1-1,1,1,0,0,1-1,1m17.074-17.066a1,1,0,1,1,1-1,1,1,0,0,1-1,1"
                                        transform="translate(1367.074 -1120.976)" fill="#FFFFFF" />
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <div class="d-flex align-items-center">
                            <p class="fs-12 text-light my-2 ml-1"> {{ translate('Last Month') }}: {{ single_price($previous_month_sold_amount) }}</p>
                        </div>
                     </div>
                </div>
            </div>
        </div>

        <!-- Employees Card -->
        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4 bg-success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14 text-light">{{ translate('Total Employees') }}</span>
                            </p>
                            <h3 class="mb-0 text-white fs-30">
                                {{ $total_employees }}
                            </h3>
                        </div>
                        <div class="col-auto text-right">
                            <i class="las la-user-friends la-3x text-white"></i>
                        </div>
                    </div>
                    @if($status == 'approved')
                     <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('franchise.employees.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="las la-eye la-1x text-white"></i>
                            <p class="fs-12 text-light my-2 ml-1">{{ translate('Manage Employees') }}</p>
                        </div>
                        </a>
                     </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-3 mb-4">
            <div class="card shadow-none bg-soft-primary">
                <div class="card-body">
                    <div class="card-title text-primary fs-16 fw-600">
                        {{ translate('Sales Stat') }}
                    </div>
                    <canvas id="graph-1" class="w-100" height="150"></canvas>
                </div>
            </div>
            <div class="card shadow-none bg-soft-primary mb-0 border-sm-top">
                <div class="card-body">
                    <div class="card-title text-primary fs-16 fw-600">
                        {{ translate('Sold Amount') }}
                    </div>
                    <p>{{ translate('Your Sold Amount (Current month)') }}</p>
                    <h3 class="text-primary fw-600 fs-30">
                        {{ single_price($this_month_sold_amount) }}
                    </h3>
                    <p class="mt-4">
                        {{ translate('Last Month') }}: {{ single_price($previous_month_sold_amount) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-3 mb-4">
            <div class="card shadow-none h-450px mb-0 h-100">
                <div class="card-body">
                    <div class="card-title text-primary fs-16 fw-600">
                        {{ translate('Category wise product count') }}
                    </div>
                    <hr>
                    <div class="h-300px overflow-auto c-scrollbar-light">
                        <ul class="list-group list-group-flush">
                            @foreach (\App\Models\Category::all() as $key => $category)
                                @php
                                    $cat_products = \App\Models\Product::where('user_id', $authUser->id)->where('category_id',$category->id)->count();
                                @endphp
                                @if ($cat_products > 0)
                                    <li class="d-flex justify-content-between align-items-center my-2 text-primary fs-13">
                                        {{ $category->getTranslation('name') }}
                                        <span class="">
                                            {{ $cat_products }}
                                        </span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-3 mb-4">
            <div class="card h-450px mb-0 h-100">
                <div class="card-body">
                    <div class="card-title text-primary fs-16 fw-600">
                        {{ translate('Orders') }}
                        <p class="small text-muted mb-0">
                            <span class="fs-12 fw-600">{{ translate('This Month') }}</span>
                        </p>
                    </div>
                    <div class="row align-items-center mb-4 mt-3">
                        <div class="col-auto text-left">
                            <i class="las la-shopping-cart la-2x text-primary"></i>
                        </div>
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fs-13 text-primary fw-600">{{ translate('New Order') }}</span>
                            </p>
                            <h3 class="mb-0" style="color: #A9A3CC">
                                {{ $this_month_pending_orders }}
                            </h3>
                        </div>
                    </div>
                    <div class="row align-items-center mb-4">
                        <div class="col-auto text-left">
                            <i class="las la-times-circle la-2x text-danger"></i>
                        </div>
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fs-13 text-primary fw-600">{{ translate('Cancelled') }}</span>
                            </p>
                            <h3 class="mb-0" style="color: #A9A3CC">
                                {{ $this_month_cancelled_orders }}
                            </h3>
                        </div>
                    </div>
                    <div class="row align-items-center mb-4">
                        <div class="col-auto text-left">
                            <i class="las la-truck la-2x text-warning"></i>
                        </div>
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fs-13 text-primary fw-600">{{ translate('On Delivery') }}</span>
                            </p>
                            <h3 class="mb-0" style="color: #A9A3CC">
                                {{ $this_month_on_the_way_orders }}
                            </h3>
                        </div>
                    </div>
                    <div class="row align-items-center mb-4">
                        <div class="col-auto text-left">
                            <i class="las la-check-circle la-2x text-success"></i>
                        </div>
                        <div class="col">
                            <p class="small text-muted mb-0">
                                <span class="fs-13 text-primary fw-600">{{ translate('Delivered') }}</span>
                            </p>
                            <h3 class="mb-0" style="color: #A9A3CC">
                                {{ $this_month_delivered_orders }}
                            </h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-3 mb-4">
            <div class="card h-100 mb-0 d-flex align-items-center justify-content-center p-4">
                <div class="text-center">
                    @if($status == 'approved')
                        <img src="{{ static_asset('assets/img/verified.png') }}" alt="" class="img-fluid mb-3" style="max-width: 100px;">
                        <h5 class="fw-600">{{ translate('Verified Account') }}</h5>
                    @else
                        <img src="{{ static_asset('assets/img/non_verified.png') }}" alt="" class="img-fluid mb-3" style="max-width: 100px;">
                        <h5 class="fw-600 text-danger">{{ translate('Unverified Account') }}</h5>
                        <p class="text-muted mb-0">{{ translate('Wait for Admin Approval') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">{{ translate('Top Selling Products') }}</h6>
        </div>
        <div class="card-body">
            <div class="aiz-carousel gutters-10" data-items="6" data-xl-items="5" data-lg-items="4" data-md-items="3" data-sm-items="2" data-arrows='true'>
                @foreach ($products as $key => $product)
                    <div class="carousel-box">
                        <div class="aiz-card-box border border-light rounded shadow-sm hov-shadow-md mb-2 has-transition bg-white">
                            <div class="position-relative">
                                <a href="{{ route('product', $product->slug) }}" class="d-block">
                                    <img class="img-fit lazyload mx-auto h-140px"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </a>
                            </div>
                            <div class="p-2 text-left">
                                <div class="fs-13">
                                    <span class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
                                </div>
                                <h3 class="fw-600 fs-12 text-truncate-2 lh-1-4 mb-0">
                                    <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">{{ $product->getTranslation('name') }}</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        AIZ.plugins.chart('#graph-1', {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($last_7_days_sales as $key => $last_7_days_sale)
                        '{{ $key }}',
                    @endforeach
                ],
                datasets: [{
                    label: '{{ translate('Sales') }}',
                    data: [
                        @foreach ($last_7_days_sales as $key => $last_7_days_sale)
                            '{{ $last_7_days_sale }}',
                        @endforeach
                    ],
                    backgroundColor: '#2E294E',
                    borderColor: '#2E294E',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#E0E0E0',
                            zeroLineColor: '#E0E0E0'
                        },
                        ticks: {
                            fontColor: "#AFAFAF",
                            fontFamily: 'Roboto',
                            fontSize: 10,
                            beginAtZero: true
                        },
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            fontColor: "#AFAFAF",
                            fontFamily: 'Roboto',
                            fontSize: 10
                        },
                        barThickness: 7
                    }],
                },
                legend: {
                    display: false
                }
            }
        });
    </script>
@endsection
