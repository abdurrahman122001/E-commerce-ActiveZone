@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="h3">
        @if($type == 'state_franchise')
            {{translate('Edit State Franchise Package')}}
        @elseif($type == 'sub_franchise')
            {{translate('Edit Sub Franchise Package')}}
        @elseif($type == 'vendor')
            {{translate('Edit Vendor Package')}}
        @else
            {{translate('Edit Franchise Package')}}
        @endif
    </h1>
</div>

<div class="col-lg-10 mx-auto">
    <div class="card">
        <ul class="nav nav-tabs nav-fill border-light">
            @foreach (\App\Models\Language::all() as $language)
                <li class="nav-item">
                    <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3" href="{{ route('franchise_packages.edit', ['id'=>$franchise_package->id, 'lang'=> $language->code] ) }}">
                        <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                        <span>{{$language->name}}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <form class="form-horizontal" action="{{ route('franchise_packages.update', $franchise_package->id) }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="lang" value="{{ $lang }}">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{translate('Package Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" value="{{ $franchise_package->getTranslation('name', $lang) }}" class="form-control" required>
                    </div>
                </div>
                @if($lang == 'en' && $type != 'vendor')
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="package_type">{{translate('Package Type')}}</label>
                    <div class="col-sm-10">
                        <select name="package_type" id="package_type" class="form-control aiz-selectpicker" required>
                            <option value="state_franchise" @if($franchise_package->package_type == 'state_franchise') selected @endif>{{ translate('State Franchise') }}</option>
                            <option value="franchise" @if($franchise_package->package_type == 'franchise') selected @endif>{{ translate('City Franchise') }}</option>
                            <option value="sub_franchise" @if($franchise_package->package_type == 'sub_franchise') selected @endif>{{ translate('Sub Franchise') }}</option>
                        </select>
                    </div>
                </div>
                @endif
                @if($lang == 'en' && $type == 'vendor')
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="product_limit">{{translate('Product Limit')}}</label>
                    <div class="col-sm-10">
                        <input type="number" min="0" step="1" placeholder="{{translate('Product Limit')}}" id="product_limit" name="product_limit" value="{{ $franchise_package->product_limit }}" class="form-control" required>
                    </div>
                </div>
                @endif
                @if($lang == 'en')
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="price">{{translate('Price')}}</label>
                    <div class="col-sm-10">
                        <input type="number" step="0.01" min="0" placeholder="{{translate('Price')}}" id="price" name="price" value="{{ $franchise_package->price }}" class="form-control" required>
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
                            <input type="hidden" name="logo" value="{{ $franchise_package->logo }}" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="duration">{{translate('Duration (Days)')}}</label>
                    <div class="col-sm-10">
                        <input type="number" min="0" step="1" placeholder="{{translate('Duration')}}" id="duration" name="duration" value="{{ $franchise_package->duration }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="features">{{translate('Package Features')}}</label>
                    <div class="col-sm-10">
                        <textarea name="features" rows="5" class="form-control">{{ $franchise_package->features }}</textarea>
                        <small>{{ translate('Separate features with new line') }}</small>
                    </div>
                </div>
                @endif
                <div class="form-group mb-0 text-right">
                    <a href="{{ route('franchise_packages.index', ['type' => $type]) }}" class="btn btn-secondary mr-2">{{translate('Cancel')}}</a>
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection
