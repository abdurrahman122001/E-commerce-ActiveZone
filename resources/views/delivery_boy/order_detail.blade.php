@extends('delivery_boy.layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Order Detail') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <td class="text-main text-bold">{{translate('Order Code')}}</td>
                        <td class="text-right">{{ $order->code }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Customer')}}</td>
                        <td class="text-right">{{ $order->user->name ?? 'Guest' }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Email')}}</td>
                        <td class="text-right">{{ $order->user->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Shipping Address')}}</td>
                        <td class="text-right">
                           @php $shipping_address = json_decode($order->shipping_address); @endphp
                           {{ $shipping_address->address }}, {{ $shipping_address->city }}, {{ $shipping_address->country }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <td class="text-main text-bold">{{translate('Order Date')}}</td>
                        <td class="text-right">{{ date('d-m-Y H:i A', $order->date) }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Order Status')}}</td>
                        <td class="text-right">{{ translate(ucfirst($order->delivery_status)) }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Total Amount')}}</td>
                        <td class="text-right">{{ single_price($order->grand_total) }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Payment Method')}}</td>
                        <td class="text-right">{{ translate(str_replace('_', ' ', $order->payment_type)) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="mt-4">
            <label>{{ translate('Update Delivery Status') }}</label>
            <form action="{{ route('delivery-boy.orders.update_delivery_status') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <div class="row">
                    <div class="col-md-9">
                        <select class="form-control aiz-selectpicker" name="status" data-minimum-results-for-search="Infinity">
                            <option value="pending" @if($order->delivery_status == 'pending') selected @endif>{{translate('Pending')}}</option>
                            <option value="confirmed" @if($order->delivery_status == 'confirmed') selected @endif>{{translate('Confirmed')}}</option>
                            <option value="picked_up" @if($order->delivery_status == 'picked_up') selected @endif>{{translate('Picked Up')}}</option>
                            <option value="on_the_way" @if($order->delivery_status == 'on_the_way') selected @endif>{{translate('On The Way')}}</option>
                            <option value="delivered" @if($order->delivery_status == 'delivered') selected @endif>{{translate('Delivered')}}</option>
                            <option value="cancelled" @if($order->delivery_status == 'cancelled') selected @endif>{{translate('Cancelled')}}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-block">{{ translate('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
