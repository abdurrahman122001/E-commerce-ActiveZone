<div class="aiz-topbar px-15px px-lg-25px d-flex align-items-stretch justify-content-between">
    <div class="d-flex h-100 align-items-center">
        <div class="aiz-topbar-nav-toggler d-xxl-none mr-2" data-toggle="aiz-mobile-nav">
            <button class="aiz-mobile-toggler">
                <span></span>
            </button>
        </div>
        @if(Auth::user()->user_type == 'delivery_boy')
            <div class="aiz-topbar-item">
                <div class="d-flex align-items-center">
                    <span class="mr-2">{{translate('Online Status')}}</span>
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="update_online_status(this)" @if(Auth::user()->delivery_boy->online_status == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        @endif
    </div>
    <div class="d-flex h-100 align-items-center">
        <div class="aiz-topbar-item ml-2">
            <div class="align-items-center d-flex dropdown">
                <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                    <span class="d-flex align-items-center">
                        <span class="avatar avatar-sm mr-md-2">
                            <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}" onerror="this.src='{{ asset('assets/img/avatar-placeholder.png') }}'">
                        </span>
                        <span class="d-none d-md-block">
                            <span class="d-block fw-500">{{ Auth::user()->name }}</span>
                            <span class="d-block small opacity-60">{{ translate('Delivery Boy') }}</span>
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-md">
                    <a href="{{ route('delivery-boy.profile') }}" class="dropdown-item">
                        <i class="las la-user-circle"></i>
                        <span>{{translate('Profile')}}</span>
                    </a>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="las la-sign-out-alt"></i>
                        <span>{{translate('Logout')}}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
