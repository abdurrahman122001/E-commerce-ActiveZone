@extends('franchise.layouts.app')

@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 text-primary">{{ translate('Product Commission Earnings') }}</h1>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row gutters-10 mb-4">
        <div class="col-md-3 col-6">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body py-3">
                    <div class="fs-11 fw-600 text-uppercase opacity-60">{{ translate('Total Records') }}</div>
                    <div class="fs-24 fw-700">{{ $histories->total() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body py-3">
                    <div class="fs-11 fw-600 text-uppercase opacity-60">{{ translate('Total Earned') }}</div>
                    <div class="fs-24 fw-700">
                        @if(auth()->user()->user_type == 'state_franchise')
                            {{ single_price($histories->sum('state_franchise_commission_amount')) }}
                        @elseif(auth()->user()->user_type == 'franchise')
                            {{ single_price($histories->sum('franchise_commission_amount')) }}
                        @else
                            {{ single_price($histories->sum('sub_franchise_commission_amount')) }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body py-3">
                    <div class="fs-11 fw-600 text-uppercase opacity-60">{{ translate('Total Product Sales') }}</div>
                    <div class="fs-24 fw-700">
                        {{ single_price($histories->sum(fn($h) => $h->order_detail ? $h->order_detail->price : 0)) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body py-3">
                    <div class="fs-11 fw-600 text-uppercase opacity-60">{{ translate('Vendors') }}</div>
                    <div class="fs-24 fw-700">{{ $histories->pluck('vendor_id')->unique()->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-none mb-4">
        <div class="card-header border-bottom-0">
            <h5 class="mb-0 h6">{{translate('Filters')}}</h5>
        </div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="row gutters-5">
                    <div class="col-md-8">
                        <input type="text" class="form-control aiz-date-range" name="date_range"
                            @isset($date_range) value="{{ $date_range }}" @endisset
                            placeholder="{{ translate('Select Date Range') }}" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-block">{{ translate('Filter') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Commission History Table --}}
    <div class="card shadow-none">
        <div class="card-header border-bottom-0">
            <h5 class="mb-0 h6">{{translate('Product Commission History')}}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th>{{translate('Order Code')}}</th>
                        <th>{{translate('Product')}}</th>
                        <th data-breakpoints="lg">{{translate('Vendor')}}</th>
                        <th>{{translate('Product Price')}}</th>
                        <th data-breakpoints="lg">{{translate('Commission %')}}</th>
                        <th>{{translate('Your Earning')}}</th>
                        @if(auth()->user()->user_type == 'state_franchise')
                            <th data-breakpoints="lg">{{translate('City Commission')}}</th>
                            <th data-breakpoints="lg">{{translate('Sub-City Commission')}}</th>
                        @elseif(auth()->user()->user_type == 'franchise')
                            <th data-breakpoints="lg">{{translate('Sub-Franchise Commission')}}</th>
                            <th data-breakpoints="lg">{{translate('State Commission')}}</th>
                        @endif
                        <th>{{translate('Vendor Net')}}</th>
                        <th data-breakpoints="lg">{{translate('Date')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $key => $history)
                        @php
                            $product_price = $history->order_detail ? $history->order_detail->price : 0;
                            $user_type = auth()->user()->user_type;
                            if ($user_type == 'state_franchise') {
                                $my_earning = $history->state_franchise_commission_amount;
                            } elseif ($user_type == 'franchise') {
                                $my_earning = $history->franchise_commission_amount;
                            } else {
                                $my_earning = $history->sub_franchise_commission_amount;
                            }
                            $comm_pct = $product_price > 0 ? round(($my_earning / $product_price) * 100, 2) : 0;
                            $vendor_net = $history->commission_amount;
                        @endphp
                        <tr>
                            <td>{{ ($key+1) + ($histories->currentPage() - 1)*$histories->perPage() }}</td>
                            <td>
                                <span class="badge badge-inline badge-soft-primary">
                                    {{ $history->order->code ?? translate('N/A') }}
                                </span>
                            </td>
                            <td>
                                @if($history->order_detail && $history->order_detail->product)
                                    <span class="fw-600">{{ Str::limit($history->order_detail->product->name, 35) }}</span>
                                @else
                                    <span class="text-muted">{{ translate('N/A') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-inline badge-soft-info">
                                    {{ $history->vendor->user->name ?? translate('N/A') }}
                                </span>
                            </td>
                            <td>{{ single_price($product_price) }}</td>
                            <td>
                                <span class="badge badge-inline badge-soft-warning">
                                    {{ $comm_pct }}%
                                </span>
                            </td>
                            <td class="text-success fw-700">
                                {{ single_price($my_earning) }}
                            </td>
                            @if($user_type == 'state_franchise')
                                <td class="text-primary">{{ single_price($history->franchise_commission_amount) }}</td>
                                <td class="text-info">{{ single_price($history->sub_franchise_commission_amount) }}</td>
                            @elseif($user_type == 'franchise')
                                <td class="text-info">{{ single_price($history->sub_franchise_commission_amount) }}</td>
                                <td class="text-warning">{{ single_price($history->state_franchise_commission_amount) }}</td>
                            @endif
                            <td class="text-muted">{{ single_price($vendor_net) }}</td>
                            <td>{{ $history->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4">
                                <i class="las la-box fs-48 text-muted d-block"></i>
                                <span class="text-muted">{{ translate('No commission history found.') }}</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="aiz-pagination mt-3">
                {{ $histories->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection
