@extends('delivery_boy.layouts.app')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('My Wallet') }}</h1>
        </div>
    </div>
</div>

@php
    $delivery_boy = Auth::user()->delivery_boy;
@endphp

<div class="row gutters-10">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>{{ translate('Total Earnings') }}</h5>
                <h2>{{ single_price($delivery_boy->total_earning ?? 0) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h5>{{ translate('Cash Collected') }}</h5>
                <h2>{{ single_price($delivery_boy->cash_collected ?? 0) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5>{{ translate('Balance') }}</h5>
                <h2>{{ single_price(($delivery_boy->total_earning ?? 0) - ($delivery_boy->cash_collected ?? 0)) }}</h2>
            </div>
        </div>
    </div>
</div>

@endsection
