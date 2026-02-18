@extends('delivery_boy.layouts.app')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Total Collections') }}</h1>
        </div>
    </div>
</div>

@php
    $delivery_boy = Auth::user()->delivery_boy;
@endphp

<div class="card">
    <div class="card-body text-center">
        <h4>{{ translate('Total Cash Collected') }}</h4>
        <h1 class="display-4 text-success">{{ single_price($delivery_boy->cash_collected ?? 0) }}</h1>
    </div>
</div>
@endsection
