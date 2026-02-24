@extends('delivery_boy.layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Assigned Deliveries')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>{{translate('Order Code')}}</th>
                    <th data-breakpoints="lg">{{translate('Vendor / Shop')}}</th>
                    <th data-breakpoints="lg">{{translate('Customer')}}</th>
                    <th data-breakpoints="lg">{{translate('Customer Address')}}</th>
                    <th data-breakpoints="lg">{{translate('Amount')}}</th>
                    <th data-breakpoints="lg">{{translate('Delivery Status')}}</th>
                    <th data-breakpoints="lg">{{translate('Payment Status')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $key => $order)
                    @php
                        $shipping_address = json_decode($order->shipping_address);
                        $vendor = $order->vendor;
                        $shop   = $order->shop;
                        $status = $order->delivery_status;
                    @endphp
                    <tr class="{{ $status == 'ready_to_pick' ? 'table-warning' : '' }}">
                        <td>
                            {{ $order->code }}
                            @if($status == 'ready_to_pick')
                                <br><span class="badge badge-warning badge-sm">{{ translate('Action Required') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($vendor)
                                <strong>{{ $vendor->shop_name ?? ($shop->name ?? translate('N/A')) }}</strong><br>
                                <small class="text-muted">{{ translate('ID') }}: #{{ $vendor->id }}</small><br>
                                @if($vendor->address)
                                    <small>{{ $vendor->address }}</small>
                                @endif
                            @elseif($shop)
                                <strong>{{ $shop->name }}</strong><br>
                                @if($shop->address)
                                    <small>{{ $shop->address }}</small>
                                @endif
                            @else
                                <span class="text-muted">{{ translate('Admin') }}</span>
                            @endif
                        </td>
                        <td>
                            {{ $order->user->name ?? 'Guest' }}<br>
                            <small class="text-muted">{{ translate('ID') }}: #{{ $order->user_id }}</small>
                        </td>
                        <td>
                            @if($shipping_address)
                                {{ $shipping_address->address ?? '' }},
                                {{ $shipping_address->city ?? '' }}
                                <br>
                                <small>{{ $shipping_address->phone ?? '' }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ single_price($order->grand_total) }}</td>
                        <td>
                            @if($status == 'delivered')
                                <span class="badge badge-inline badge-success">{{translate(ucfirst(str_replace('_', ' ', $status)))}}</span>
                            @elseif($status == 'ready_to_pick')
                                <span class="badge badge-inline badge-warning">{{translate('Ready to Pick')}}</span>
                            @elseif($status == 'pending')
                                <span class="badge badge-inline badge-danger">{{translate(ucfirst($status))}}</span>
                            @else
                                <span class="badge badge-inline badge-primary">{{translate(ucfirst(str_replace('_', ' ', $status)))}}</span>
                            @endif
                        </td>
                        <td>
                            @if($order->payment_status == 'paid')
                                <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{translate('Unpaid')}}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('delivery-boy.order-detail', $order->id) }}" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{ translate('Detail') }}">
                                <i class="las la-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $orders->links() }}
        </div>
    </div>
</div>

@endsection
