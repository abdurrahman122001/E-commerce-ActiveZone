@extends('franchise.layouts.app')

@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
        </div>

        <div class="card-body">
            <div class="row gutters-5 mb-3">
                <div class="col text-md-left text-center">
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                @endphp
                <div class="col-md-3 ml-auto">
                    <label for="update_payment_status">{{ translate('Payment Status') }}</label>
                    <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity" id="update_payment_status">
                        <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>{{ translate('Unpaid') }}</option>
                        <option value="paid" @if ($payment_status == 'paid') selected @endif>{{ translate('Paid') }}</option>
                    </select>
                </div>
                <div class="col-md-3 ml-auto">
                    <label for="update_delivery_status">{{ translate('Delivery Status') }}</label>
                    <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity" id="update_delivery_status">
                        <option value="pending" @if ($delivery_status == 'pending') selected @endif>{{ translate('Pending') }}</option>
                        <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>{{ translate('Confirmed') }}</option>
                        <option value="ready_to_pick" @if ($delivery_status == 'ready_to_pick') selected @endif>{{ translate('Ready to Pick') }}</option>
                        <option value="on_delivery" @if ($delivery_status == 'on_delivery') selected @endif>{{ translate('On Delivery') }}</option>
                        <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>{{ translate('Delivered') }}</option>
                        <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>{{ translate('Cancelled') }}</option>
                    </select>
                </div>
            </div>

            <div class="row gutters-5 mt-2">
                <div class="col text-md-left text-center">
                    @if(json_decode($order->shipping_address))
                        <address>
                            <strong class="text-main">
                                {{ json_decode($order->shipping_address)->name }}
                            </strong><br>
                            {{ json_decode($order->shipping_address)->email }}<br>
                            {{ json_decode($order->shipping_address)->phone }}<br>
                            {{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, {{ json_decode($order->shipping_address)->postal_code }}<br>
                            {{ json_decode($order->shipping_address)->country }}
                        </address>
                    @endif
                </div>
                <div class="col-md-4">
                    <table class="ml-auto">
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order #') }}</td>
                                <td class="text-info text-bold text-right">{{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Status') }}</td>
                                <td class="text-right">
                                    <span class="badge badge-inline badge-info">{{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Date') }}</td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Total amount') }}</td>
                                <td class="text-right">{{ single_price($order->grand_total) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Payment method') }}</td>
                                <td class="text-right">{{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table-bordered aiz-table invoice-summary table">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th class="min-col">#</th>
                                <th width="10%">{{ translate('Photo') }}</th>
                                <th>{{ translate('Description') }}</th>
                                <th>{{ translate('Delivery Type') }}</th>
                                <th class="text-center">{{ translate('Qty') }}</th>
                                <th class="text-center">{{ translate('Price') }}</th>
                                <th class="text-right">{{ translate('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">
                                                <img height="50" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <strong>{{ $orderDetail->product->getTranslation('name') }}</strong><br>
                                            <small>{{ $orderDetail->variation }}</small>
                                        @endif
                                    </td>
                                    <td>{{ translate(ucfirst(str_replace('_', ' ', $orderDetail->shipping_type))) }}</td>
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">{{ single_price($orderDetail->price / $orderDetail->quantity) }}</td>
                                    <td class="text-right">{{ single_price($orderDetail->price) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td><strong class="text-muted">{{ translate('Sub Total') }} :</strong></td>
                            <td>{{ single_price($order->orderDetails->sum('price')) }}</td>
                        </tr>
                        <tr>
                            <td><strong class="text-muted">{{ translate('Tax') }} :</strong></td>
                            <td>{{ single_price($order->orderDetails->sum('tax')) }}</td>
                        </tr>
                        <tr>
                            <td><strong class="text-muted">{{ translate('Shipping') }} :</strong></td>
                            <td>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
                        </tr>
                        <tr>
                            <td><strong class="text-muted">{{ translate('Coupon') }} :</strong></td>
                            <td>{{ single_price($order->coupon_discount) }}</td>
                        </tr>
                        <tr>
                            <td><strong class="text-muted">{{ translate('TOTAL') }} :</strong></td>
                            <td class="text-muted h5">{{ single_price($order->grand_total) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('franchise.orders.update_delivery_status') }}', {
                _token: '{{ csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Order status has been updated') }}');
                location.reload();
            });
        });

        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('franchise.orders.update_payment_status') }}', {
                _token: '{{ csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
                location.reload();
            });
        });
    </script>
@endsection
