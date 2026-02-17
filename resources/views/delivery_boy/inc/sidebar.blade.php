<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('delivery_boy.dashboard') }}" class="d-block text-center my-3">
                @if(get_setting('system_logo_white') != null)
                    <img class="mw-100" src="{{ uploaded_asset(get_setting('system_logo_white')) }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
                @else
                    <img class="mw-100" src="{{ asset('assets/img/logo.png') }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="{{ route('delivery_boy.dashboard') }}" class="aiz-side-nav-link">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('assigned-deliveries') }}" class="aiz-side-nav-link">
                        <i class="las la-truck-loading aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Assigned Delivery') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('pickup-deliveries') }}" class="aiz-side-nav-link">
                        <i class="las la-box aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Pickup Delivery') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('on-the-way-deliveries') }}" class="aiz-side-nav-link">
                        <i class="las la-shipping-fast aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('On The Way Delivery') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('completed-deliveries') }}" class="aiz-side-nav-link">
                        <i class="las la-check-circle aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Completed Delivery') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('pending-deliveries') }}" class="aiz-side-nav-link">
                        <i class="las la-clock aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Pending Delivery') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('cancelled-deliveries') }}" class="aiz-side-nav-link">
                        <i class="las la-times-circle aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Cancelled Delivery') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('total-collection') }}" class="aiz-side-nav-link">
                        <i class="las la-wallet aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Total Collections') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('total-earnings') }}" class="aiz-side-nav-link">
                        <i class="las la-money-bill aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Total Earnings') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('cancel-request-list') }}" class="aiz-side-nav-link">
                        <i class="las la-history aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Cancel Requests') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('delivery-boy.wallet') }}" class="aiz-side-nav-link">
                        <i class="las la-wallet aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('My Wallet') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('delivery-boy.profile') }}" class="aiz-side-nav-link">
                        <i class="las la-user aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Profile') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="aiz-sidebar-overlay"></div>
</div>
