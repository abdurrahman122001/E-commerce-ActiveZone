@extends('franchise.layouts.app')

@section('panel_content')
    <div class="row gutters-10">
        <div class="col-md-3">
            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50">
                        <span class="fs-12 d-block">{{ translate('Total Products') }}</span>
                    </div>
                    <div class="h3 fw-700 mb-3">{{ $total_products }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50">
                        <span class="fs-12 d-block">{{ translate('Total Sales') }}</span>
                    </div>
                    <div class="h3 fw-700 mb-3">{{ single_price($total_sales) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50">
                        <span class="fs-12 d-block">{{ translate('Pending Orders') }}</span>
                    </div>
                    <div class="h3 fw-700 mb-3">{{ $pending_orders }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50">
                        <span class="fs-12 d-block">{{ translate('Delivered Orders') }}</span>
                    </div>
                    <div class="h3 fw-700 mb-3">{{ $delivered_orders }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gutters-10">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Sale This Month') }}</h6>
                </div>
                <div class="card-body">
                    <div class="h3 fw-700 mb-3 text-primary">{{ single_price($sale_this_month) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Total Categories') }}</h6>
                </div>
                <div class="card-body">
                    <div class="h3 fw-700 mb-3 text-info">{{ $total_categories }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Total Brands') }}</h6>
                </div>
                <div class="card-body">
                    <div class="h3 fw-700 mb-3 text-warning">{{ $total_brands }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gutters-10">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Top Selling Products') }}</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach ($products as $key => $product)
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img src="{{ uploaded_asset($product->thumbnail_img) }}" class="size-40px">
                                    </div>
                                    <div class="col">
                                        <span class="text-muted text-truncate-2">{{ $product->getTranslation('name') }}</span>
                                    </div>
                                    <div class="col-auto text-right">
                                        <span class="badge badge-inline badge-soft-primary">{{ $product->num_of_sale }} {{ translate('Sales') }}</span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Top Categories') }}</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach ($top_categories as $key => $category)
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <span class="text-muted text-truncate-2">{{ $category->name }}</span>
                                    </div>
                                    <div class="col-auto text-right">
                                        <span class="badge badge-inline badge-soft-info">{{ single_price($category->total) }}</span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
