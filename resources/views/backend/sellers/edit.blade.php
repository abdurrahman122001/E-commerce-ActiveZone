@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Edit Seller Information')}}</h5>
</div>

<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Seller Information')}}</h5>
        </div>

        <div class="card-body">
          <form action="{{ route('sellers.update', $shop->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control" value="{{$shop->user->name}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Email Address')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Email Address')}}" id="email" name="email" class="form-control" value="{{$shop->user->email}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="commission_percentage">{{translate('Commission')}}</label>
                    <div class="col-sm-5">
                        <input type="number" step="0.01" min="0" placeholder="{{translate('Commission Value')}}" id="commission_percentage" name="commission_percentage" class="form-control" value="{{ $shop->commission_percentage }}">
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control aiz-selectpicker" name="commission_type" id="commission_type">
                            <option value="percentage" @if($shop->commission_type == 'percentage') selected @endif>{{translate('Percentage (%)')}}</option>
                            <option value="flat" @if($shop->commission_type == 'flat') selected @endif>{{translate('Flat Amount')}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="referral_commission_value">{{translate('Referral Commission Override')}}</label>
                    <div class="col-sm-5">
                        <input type="number" step="0.01" min="0" placeholder="{{translate('Value')}}" id="referral_commission_value" name="referral_commission_value" class="form-control" value="{{ $shop->user->vendor->referral_commission_value }}">
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control aiz-selectpicker" name="referral_commission_type">
                            <option value="percentage" @if($shop->user->vendor->referral_commission_type == 'percentage') selected @endif>{{translate('Percentage (%)')}}</option>
                            <option value="flat" @if($shop->user->vendor->referral_commission_type == 'flat') selected @endif>{{translate('Flat Amount')}}</option>
                        </select>
                    </div>
                    <div class="col-sm-9 offset-sm-3">
                        <small class="text-muted">{{translate('If set, this will override global and package-specific referral commissions for this vendor.')}}</small>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
