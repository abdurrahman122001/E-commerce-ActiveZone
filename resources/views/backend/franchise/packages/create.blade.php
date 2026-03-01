@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="h3">
        @if($type == 'state_franchise')
            {{translate('Add State Franchise Package')}}
        @elseif($type == 'sub_franchise')
            {{translate('Add Sub Franchise Package')}}
        @elseif($type == 'vendor')
            {{translate('Add Vendor Package')}}
        @else
            {{translate('Add Franchise Package')}}
        @endif
    </h1>
</div>

<div class="col-lg-10 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">
                @if($type == 'state_franchise')
                    {{translate('State Franchise Package Information')}}
                @elseif($type == 'sub_franchise')
                    {{translate('Sub Franchise Package Information')}}
                @elseif($type == 'vendor')
                    {{translate('Vendor Package Information')}}
                @else
                    {{translate('Franchise Package Information')}}
                @endif
            </h5>
        </div>

        <form class="form-horizontal" action="{{ route('franchise_packages.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <input type="hidden" name="package_type" value="{{ $type }}">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{translate('Package Name')}}</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                @if($type == 'vendor')
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="product_limit">{{translate('Product Limit')}}</label>
                    <div class="col-sm-10">
                        <input type="number" min="0" step="1" placeholder="{{translate('Product Limit')}}" id="product_limit" name="product_limit" class="form-control" required>
                    </div>
                </div>
                @endif
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="price">{{translate('Price')}}</label>
                    <div class="col-sm-10">
                        <input type="number" step="0.01" min="0" placeholder="{{translate('Price')}}" id="price" name="price" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="signinSrEmail">{{translate('Package Logo')}}</label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="logo" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="duration">{{translate('Duration (Days)')}}</label>
                    <div class="col-sm-10">
                        <input type="number" min="0" step="1" placeholder="{{translate('Duration')}}" id="duration" name="duration" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="features">{{translate('Package Features')}}</label>
                    <div class="col-sm-10">
                        <textarea name="features" rows="5" class="form-control"></textarea>
                        <small>{{ translate('Separate features with new line') }}</small>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <a href="{{ route('franchise_packages.index', ['type' => $type]) }}" class="btn btn-secondary mr-2">{{translate('Cancel')}}</a>
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection
