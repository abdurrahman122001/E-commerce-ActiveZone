@extends('delivery_boy.layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ $page_title }}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>{{translate('Order Code')}}</th>
                    <th data-breakpoints="lg">{{translate('Franchise')}}</th>
                    <th data-breakpoints="lg">{{translate('Customer')}}</th>
                    <th data-breakpoints="lg">{{translate('Amount')}}</th>
                    <th data-breakpoints="lg">{{translate('Delivery Status')}}</th>
                    <th data-breakpoints="lg">{{translate('Payment Status')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $key => $order)
                    <tr>
                        <td>{{ $order->code }}</td>
                        <td>
                            @if($order->vendor && $order->vendor->franchise)
                                {{ $order->vendor->franchise->franchise_name }}
                            @elseif($order->vendor && $order->vendor->sub_franchise)
                                {{ $order->vendor->sub_franchise->sub_franchise_name }}
                            @else
                                {{ translate('Admin') }}
                            @endif
                        </td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>{{ single_price($order->grand_total) }}</td>
                        <td>
                            @php
                                $status = $order->delivery_status;
                            @endphp
                            @if($status == 'delivered')
                                <span class="badge badge-inline badge-success">{{translate(ucfirst($status))}}</span>
                            @elseif($status == 'pending')
                                <span class="badge badge-inline badge-danger">{{translate(ucfirst($status))}}</span>
                            @else
                                <span class="badge badge-inline badge-primary">{{translate(ucfirst($status))}}</span>
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
