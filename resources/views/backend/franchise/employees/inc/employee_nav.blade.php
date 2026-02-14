<div class="aiz-topbar px-15px px-lg-25px d-flex align-items-stretch justify-content-between">
    <div class="d-flex">
        <div class="aiz-topbar-nav-toggler d-flex align-items-center justify-content-start ml-0 mr-2" data-toggle="aiz-mobile-nav">
            <a class="btn btn-topbar has-transition btn-icon p-0 d-flex align-items-center" href="javascript:void(0)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                    <g id="Group_28009" data-name="Group 28009" transform="translate(0 16) rotate(-90)">
                      <rect id="Rectangle_18283" data-name="Rectangle 18283" width="2" height="7" rx="1" fill="#9da3ae"/>
                      <rect id="Rectangle_16236" data-name="Rectangle 16236" width="2" height="11" rx="1" transform="translate(14)" fill="#9da3ae"/>
                      <rect id="Rectangle_18284" data-name="Rectangle 18284" width="2" height="16" rx="1" transform="translate(7)" fill="#9da3ae"/>
                    </g>
                </svg>
            </a>
        </div>
    </div>
    <div class="d-flex justify-content-end align-items-stretch flex-grow-1">
        <div class="d-flex align-items-center">
            <div class="aiz-topbar-item mr-3">
                <a class="btn btn-topbar has-transition btn-icon btn-circle btn-light p-0 hov-bg-primary hov-svg-white d-flex align-items-center justify-content-center"
                    href="{{ route('home') }}" target="_blank" data-toggle="tooltip" data-title="{{ translate('Browse Website') }}">
                    <i class="las la-globe"></i>
                </a>
            </div>

            <div class="aiz-topbar-item">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow text-dark" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <span class="d-none d-md-block mr-2">
                                <span class="d-block fw-500">{{ Auth::guard('franchise_employee')->user()->name }}</span>
                                <span class="d-block small opacity-60 text-right">{{ translate('Employee') }}</span>
                            </span>
                            <span class="size-40px rounded-circle overflow-hidden">
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="img-fit">
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-md">
                        <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
                            <i class="las la-sign-out-alt"></i>
                            <span>{{ translate('Logout') }}</span>
                        </a>
                        <form id="logout-form" action="{{ route('franchise.employee.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
