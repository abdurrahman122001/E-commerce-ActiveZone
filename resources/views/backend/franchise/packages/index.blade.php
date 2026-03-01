@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
            @if($type == 'state_franchise')
                <h1 class="h3">{{translate('State Franchise Packages')}}</h1>
            @elseif($type == 'sub_franchise')
                <h1 class="h3">{{translate('Sub Franchise Packages')}}</h1>
            @elseif($type == 'vendor')
                <h1 class="h3">{{translate('Vendor Packages')}}</h1>
            @else
                <h1 class="h3">{{translate('Franchise Packages')}}</h1>
            @endif
		</div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('franchise_packages.create', ['type' => $type]) }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New Package')}}</span>
            </a>
        </div>
	</div>
</div>

{{-- Type Tabs --}}
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $type == 'state_franchise' ? 'active' : '' }}"
           href="{{ route('franchise_packages.index', ['type' => 'state_franchise']) }}">
            {{ translate('State Franchise') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $type == 'franchise' ? 'active' : '' }}"
           href="{{ route('franchise_packages.index', ['type' => 'franchise']) }}">
            {{ translate('City Franchise') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $type == 'sub_franchise' ? 'active' : '' }}"
           href="{{ route('franchise_packages.index', ['type' => 'sub_franchise']) }}">
            {{ translate('Sub Franchise') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $type == 'vendor' ? 'active' : '' }}"
           href="{{ route('franchise_packages.index', ['type' => 'vendor']) }}">
            {{ translate('Vendor') }}
        </a>
    </li>
</ul>

<div class="row">
    @forelse ($franchise_packages as $key => $package)
        <div class="col-lg-3 col-md-4 col-sm-12">
            <div class="card">
                <div class="card-body text-center">
                    <img alt="{{ translate('Package Logo')}}" src="{{ uploaded_asset($package->logo) }}" class="mw-100 mx-auto mb-4" height="150px">
                    <p class="mb-3 h6 fw-600">{{$package->getTranslation('name')}}</p>
                    <p class="h4">{{single_price($package->price)}}</p>
                    @if($type == 'vendor')
                        <p class="mb-2">{{translate('Product Limit')}}: {{$package->product_limit}}</p>
                    @endif

                    <div class="mar-top">
                        <a href="{{route('franchise_packages.edit', ['id'=>$package->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="btn btn-sm btn-info">{{translate('Edit')}}</a>
                        <a href="#" data-href="{{route('franchise_packages.destroy', $package->id)}}" class="btn btn-sm btn-danger confirm-delete" >{{translate('Delete')}}</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="las la-box fs-50 text-muted mb-3"></i>
                    <p class="text-muted">{{ translate('No packages found for this type.') }}</p>
                    <a href="{{ route('franchise_packages.create', ['type' => $type]) }}" class="btn btn-info">
                        {{ translate('Add First Package') }}
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

{{ $franchise_packages->appends(['type' => $type])->links() }}

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
