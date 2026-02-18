@extends('delivery_boy.layouts.app')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('My Profile') }}</h1>
        </div>
    </div>
</div>

@php
    $delivery_boy = Auth::user()->delivery_boy;
@endphp

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Personal Details') }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('delivery-boy.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ translate('Name') }}</label>
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ translate('Email') }}</label>
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ translate('Phone') }}</label>
                        <input type="text" class="form-control" name="phone" value="{{ $user->phone }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ translate('Address') }}</label>
                        <input type="text" class="form-control" name="address" value="{{ $user->address }}">
                    </div>
                </div>
            </div>

            <hr>
            <h5 class="mb-3">{{ translate('Vehicle Details') }}</h5>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>{{ translate('Vehicle Details') }}</label>
                        <textarea class="form-control" name="vehicle_details" rows="3" placeholder="{{ translate('e.g. Bike - Honda Shine, Reg No: XX-0000') }}">{{ $delivery_boy->vehicle_details ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <hr>
            <h5 class="mb-3">{{ translate('Documents') }}</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ translate('ID Proof') }}</label>
                        <input type="text" class="form-control" name="id_proof" value="{{ $delivery_boy->id_proof ?? '' }}" placeholder="{{ translate('Aadhaar / PAN number') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ translate('Driving License') }}</label>
                        <input type="text" class="form-control" name="driving_license" value="{{ $delivery_boy->driving_license ?? '' }}" placeholder="{{ translate('DL Number') }}">
                    </div>
                </div>
            </div>

            <hr>
            <h5 class="mb-3">{{ translate('Bank Details') }}</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ translate('Bank Name') }}</label>
                        <input type="text" class="form-control" name="bank_name" value="{{ $delivery_boy->bank_name ?? '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ translate('Account Holder Name') }}</label>
                        <input type="text" class="form-control" name="holder_name" value="{{ $delivery_boy->holder_name ?? '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ translate('Account Number') }}</label>
                        <input type="text" class="form-control" name="bank_account_no" value="{{ $delivery_boy->bank_account_no ?? '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ translate('Routing / IFSC Code') }}</label>
                        <input type="text" class="form-control" name="bank_routing_no" value="{{ $delivery_boy->bank_routing_no ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="text-right mt-3">
                <button type="submit" class="btn btn-primary">{{ translate('Update Profile') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
