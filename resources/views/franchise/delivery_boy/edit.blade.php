@extends('franchise.layouts.app')

@section('panel_content')

@php
    $route_prefix = Auth::guard('franchise_employee')->check() ? 'franchise.employee.' : 'franchise.';
@endphp

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Edit Delivery Boy Information')}}</h5>
        </div>
        <form action="{{ route($route_prefix.'delivery_boys.update', $delivery_boy->id) }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" value="{{ $delivery_boy->user->name }}" placeholder="{{translate('Name')}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="email" name="email" value="{{ $delivery_boy->user->email }}" placeholder="{{translate('Email')}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="phone" value="{{ $delivery_boy->user->phone }}" placeholder="{{translate('Phone')}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" name="password" placeholder="{{translate('Password')}}" class="form-control">
                        <small>{{ translate('Leave blank to keep current password') }}</small>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
