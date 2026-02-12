<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <div class="d-block text-center my-3">
                <img class="mw-100 mb-3" src="{{ uploaded_asset(get_setting('header_logo')) }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
                <h3 class="fs-16 m-0 text-primary">{{ Auth::user()->name }}</h3>
                <p class="text-primary">({{ translate(ucfirst(str_replace('_', ' ', Auth::user()->user_type))) }})</p>
            </div>
        </div>
        <div class="aiz-side-nav-wrap">
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="{{ route('franchise.dashboard') }}" class="aiz-side-nav-link">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>

                @php
                    $status = 'pending';
                    if (Auth::user()->user_type == 'franchise') {
                        $status = Auth::user()->franchise ? Auth::user()->franchise->status : 'pending';
                    } elseif (Auth::user()->user_type == 'sub_franchise') {
                        $status = Auth::user()->sub_franchise ? Auth::user()->sub_franchise->status : 'pending';
                    }
                @endphp

                @if($status == 'approved')
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Products') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.products') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Products') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.products.create') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Add New Product') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-list aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Categories') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.categories.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('All Categories') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.categories.create') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Add New Category') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    @if(Auth::user()->user_type == 'franchise')
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-users aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Sub-Franchises') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.sub_franchises.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('All Sub-Franchises') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.sub_franchises.create') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Add New Sub-Franchise') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-user-friends aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Vendors') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.vendors.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('All Vendors') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.vendors.create') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Add New Vendor') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.vendors.commission_history') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Commission History') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-user-friends aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Employees') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.employees.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('All Employees') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('franchise.employees.create') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Add New Employee') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="aiz-side-nav-item">
                        <a href="{{ route('franchise.orders.index') }}" class="aiz-side-nav-link">
                            <i class="las la-money-bill aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Orders') }}</span>
                        </a>
                    </li>
                    
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('franchise.profile.index') }}" class="aiz-side-nav-link">
                            <i class="las la-user aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Manage Profile') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="aiz-sidebar-overlay"></div>
</div>
