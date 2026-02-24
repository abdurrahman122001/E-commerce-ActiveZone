@extends('delivery_boy.layouts.app')

@section('content')

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 h6">{{ translate('Order Detail') }}</h5>
        <span class="badge badge-inline 
            @if($order->delivery_status == 'delivered') badge-success
            @elseif($order->delivery_status == 'ready_to_pick') badge-warning
            @elseif($order->delivery_status == 'cancelled') badge-danger
            @else badge-primary @endif
        ">
            {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
        </span>
    </div>
    <div class="card-body">

        <div class="row">
            {{-- PICKUP INFO (Vendor/Shop) --}}
            <div class="col-md-6 mb-3">
                <div class="card border-warning h-100">
                    <div class="card-header bg-warning text-white py-2">
                        <h6 class="mb-0"><i class="las la-store mr-1"></i>{{ translate('Pickup From (Vendor Shop)') }}</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $vendor = $order->vendor;
                            $shop   = $order->shop;
                        @endphp

                        @if($vendor)
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td class="text-muted" width="40%"><strong>{{ translate('Vendor ID') }}</strong></td>
                                    <td><strong>#{{ $vendor->id }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><strong>{{ translate('Shop Name') }}</strong></td>
                                    <td>{{ $vendor->shop_name ?? ($shop->name ?? translate('N/A')) }}</td>
                                </tr>
                                @if($vendor->user)
                                <tr>
                                    <td class="text-muted"><strong>{{ translate('Contact') }}</strong></td>
                                    <td>{{ $vendor->user->phone ?? translate('N/A') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-muted"><strong>{{ translate('Address') }}</strong></td>
                                    <td>{{ $vendor->address ?? translate('N/A') }}</td>
                                </tr>
                                @if($vendor->franchise)
                                <tr>
                                    <td class="text-muted"><strong>{{ translate('Franchise') }}</strong></td>
                                    <td>{{ $vendor->franchise->franchise_name }}</td>
                                </tr>
                                @elseif($vendor->sub_franchise)
                                <tr>
                                    <td class="text-muted"><strong>{{ translate('Sub-Franchise') }}</strong></td>
                                    <td>{{ $vendor->sub_franchise->user->name ?? ('Sub-Franchise #' . $vendor->sub_franchise_id) }}</td>
                                </tr>
                                @endif
                            </table>
                        @elseif($shop)
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td class="text-muted" width="40%"><strong>{{ translate('Shop Name') }}</strong></td>
                                    <td>{{ $shop->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><strong>{{ translate('Address') }}</strong></td>
                                    <td>{{ $shop->address ?? translate('N/A') }}</td>
                                </tr>
                            </table>
                        @else
                            <p class="text-muted mb-0">{{ translate('Admin / In-house order') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- CUSTOMER DELIVERY INFO --}}
            <div class="col-md-6 mb-3">
                <div class="card border-info h-100">
                    <div class="card-header bg-info text-white py-2">
                        <h6 class="mb-0"><i class="las la-user mr-1"></i>{{ translate('Deliver To (Customer)') }}</h6>
                    </div>
                    <div class="card-body">
                        @php $shipping_address = json_decode($order->shipping_address); @endphp
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-muted" width="40%"><strong>{{ translate('Customer ID') }}</strong></td>
                                <td><strong>#{{ $order->user_id }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>{{ translate('Name') }}</strong></td>
                                <td>{{ $order->user->name ?? 'Guest' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>{{ translate('Phone') }}</strong></td>
                                <td>{{ $shipping_address->phone ?? ($order->user->phone ?? translate('N/A')) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>{{ translate('Email') }}</strong></td>
                                <td>{{ $order->user->email ?? translate('N/A') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>{{ translate('Address') }}</strong></td>
                                <td>
                                    @if($shipping_address)
                                        {{ $shipping_address->address ?? '' }},
                                        {{ $shipping_address->city ?? '' }}
                                        @if(isset($shipping_address->state)) , {{ $shipping_address->state }} @endif
                                        {{ $shipping_address->postal_code ?? '' }}<br>
                                        {{ $shipping_address->country ?? '' }}
                                    @else
                                        {{ translate('N/A') }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ORDER SUMMARY --}}
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <td class="text-main text-bold">{{translate('Order Code')}}</td>
                        <td class="text-right">{{ $order->code }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Order Date')}}</td>
                        <td class="text-right">{{ date('d-m-Y H:i A', $order->date) }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Total Amount')}}</td>
                        <td class="text-right">{{ single_price($order->grand_total) }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Payment Method')}}</td>
                        <td class="text-right">{{ translate(str_replace('_', ' ', $order->payment_type)) }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Payment Status')}}</td>
                        <td class="text-right">
                            @if($order->payment_status == 'paid')
                                <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- UPDATE STATUS FORM (only if not delivered/cancelled) --}}
        @if(!in_array($order->delivery_status, ['delivered', 'cancelled']))
        <div class="mt-4">
            <label><strong>{{ translate('Update Delivery Status') }}</strong></label>
            <form action="{{ route('delivery-boy.orders.update_delivery_status') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="long" id="long">
                <div class="row">
                    <div class="col-md-9">
                        <select class="form-control aiz-selectpicker" name="status" data-minimum-results-for-search="Infinity">
                            {{-- Delivery boy can only set statuses relevant to their role --}}
                            <option value="picked_up" @if($order->delivery_status == 'picked_up') selected @endif>
                                {{translate('Picked Up')}} ({{ translate('I have collected the order from vendor') }})
                            </option>
                            <option value="on_the_way" @if($order->delivery_status == 'on_the_way') selected @endif>
                                {{translate('On The Way')}} ({{ translate('En route to customer') }})
                            </option>
                            <option value="delivered" @if($order->delivery_status == 'delivered') selected @endif>
                                {{translate('Delivered')}} ({{ translate('Successfully delivered') }})
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-block">{{ translate('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
        @else
            <div class="alert alert-{{ $order->delivery_status == 'delivered' ? 'success' : 'danger' }} mt-4">
                {{ translate('This order has been') }} <strong>{{ translate(ucfirst($order->delivery_status)) }}</strong>.
            </div>
        @endif

    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                $('#lat').val(position.coords.latitude);
                $('#long').val(position.coords.longitude);
            });
        }
    });
</script>
@endsection
