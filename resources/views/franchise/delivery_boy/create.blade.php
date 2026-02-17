@extends('franchise.layouts.app')

@section('panel_content')

@php
    $route_prefix = Auth::guard('franchise_employee')->check() ? 'franchise.employee.' : 'franchise.';
@endphp

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Delivery Boy Information')}}</h5>
        </div>
        <form action="{{ route($route_prefix.'delivery_boys.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" placeholder="{{translate('Name')}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="email" name="email" placeholder="{{translate('Email')}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="phone" placeholder="{{translate('Phone')}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" name="password" placeholder="{{translate('Password')}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
