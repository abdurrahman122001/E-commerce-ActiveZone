<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <div class="d-block text-center my-3">
                <img class="mw-100 mb-3" src="{{ uploaded_asset(get_setting('header_logo')) }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
                <h3 class="fs-16 m-0 text-primary">{{ Auth::guard('franchise_employee')->user()->name }}</h3>
                <p class="text-primary">({{ translate('Franchise Employee') }})</p>
            </div>
        </div>
        <div class="aiz-side-nav-wrap">
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="{{ route('franchise.employee.dashboard') }}" class="aiz-side-nav-link">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="{{ route('franchise.employee.profile.index') }}" class="aiz-side-nav-link">
                        <i class="las la-user aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Manage Profile') }}</span>
                    </a>
                </li>

                @if(Auth::guard('franchise_employee')->user()->status == 'approved')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('franchise.employee.products') }}" class="aiz-side-nav-link">
                            <i class="las la-shopping-basket aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Products') }}</span>
                        </a>
                    </li>

                    <li class="aiz-side-nav-item">
                        <a href="{{ route('franchise.employee.categories.index') }}" class="aiz-side-nav-link">
                            <i class="las la-list aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Categories') }}</span>
                        </a>
                    </li>

                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-user-friends aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Vendors') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.employee.vendors.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('All Vendors') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.employee.vendors.create') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Add New Vendor') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>



                    <li class="aiz-side-nav-item">
                        <a href="{{ route('franchise.employee.sales_report') }}" class="aiz-side-nav-link">
                            <i class="las la-file-alt aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Sales Report') }}</span>
                        </a>
                    </li>

                    <li class="aiz-side-nav-item">
                        <a href="{{ route('franchise.employee.payouts') }}" class="aiz-side-nav-link">
                            <i class="las la-money-bill-wave aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Earnings') }}</span>
                        </a>
                    </li>
                @else
                    <li class="aiz-side-nav-item mt-3 px-3">
                        <div class="alert alert-warning text-center">
                            <i class="las la-info-circle mb-2" style="font-size: 24px;"></i>
                            <br>
                            {{ translate('Your account is currently PENDING approval from admin. You will be able to access all features once approved.') }}
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="aiz-sidebar-overlay"></div>
</div>
