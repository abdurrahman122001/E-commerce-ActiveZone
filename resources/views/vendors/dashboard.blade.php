@extends('vendors.layouts.app')

@section('panel_content')
    <div class="row gutters-10">
        <div class="col-md-3">
            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50">
                        <span class="fs-12 d-block">{{ translate('Total Orders') }}</span>
                    </div>
                    <div class="h3 fw-700 mb-3">{{ $total_orders ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50">
                        <span class="fs-12 d-block">{{ translate('Total Sales') }}</span>
                    </div>
                    <div class="h3 fw-700 mb-3">{{ single_price($total_sales ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
