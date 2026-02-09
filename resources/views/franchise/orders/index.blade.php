@extends('franchise.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar text-left pb-5px">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3 fw-bold">{{ translate('Orders') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <form class="" id="sort_orders" action="" method="GET">
            <div class="card-header row border-0 pb-0 mt-2">
                <div class="col pl-0 pl-md-3">
                    <div class="input-group mb-0 border border-light px-3 bg-light rounded-1">
                        <input type="text" class="form-control form-control-sm border-0 px-2 bg-transparent"
                            id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type Order code & hit Enter') }}">
                    </div>
                </div>

                <div class="col-md-3 ml-auto">
                    <select class="form-control mb-2 mb-md-0 bg-light" name="delivery_status" id="delivery_status" onchange="sort_orders()">
                        <option value="">{{ translate('Filter by Delivery Status') }}</option>
                        <option value="pending" @if ($delivery_status == 'pending') selected @endif>{{ translate('Pending') }}</option>
                        <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>{{ translate('Confirmed') }}</option>
                        <option value="on_delivery" @if ($delivery_status == 'on_delivery') selected @endif>{{ translate('On delivery') }}</option>
                        <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>{{ translate('Delivered') }}</option>
                        <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>{{ translate('Cancelled') }}</option>
                    </select>
                </div>

                <div class="col-md-3 ml-auto">
                    <select class="form-control mb-2 mb-md-0 bg-light" name="payment_status" id="payment_status" onchange="sort_orders()">
                        <option value="">{{ translate('Filter by Payment Status') }}</option>
                        <option value="paid" @if ($payment_status == 'paid') selected @endif>{{ translate('Paid') }}</option>
                        <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>{{ translate('Unpaid') }}</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Order Code') }}</th>
                        <th data-breakpoints="md">{{ translate('Num. of Products') }}</th>
                        <th data-breakpoints="md">{{ translate('Customer') }}</th>
                        <th data-breakpoints="md">{{ translate('Amount') }}</th>
                        <th data-breakpoints="md">{{ translate('Delivery Status') }}</th>
                        <th data-breakpoints="md">{{ translate('Payment Status') }}</th>
                        <th class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                        <tr>
                            <td>{{ ($key+1) + ($orders->currentPage() - 1)*$orders->perPage() }}</td>
                            <td>{{ $order->code }}</td>
                            <td>{{ count($order->orderDetails) }}</td>
                            <td>
                                @if ($order->user != null)
                                    {{ $order->user->name }}
                                @else
                                    Guest ({{ $order->guest_id }})
                                @endif
                            </td>
                            <td>{{ single_price($order->grand_total) }}</td>
                            <td>
                                @php
                                    $status = $order->delivery_status;
                                    if($status == 'delivered') $class = 'success';
                                    elseif($status == 'pending') $class = 'info';
                                    elseif($status == 'cancelled') $class = 'danger';
                                    else $class = 'warning';
                                @endphp
                                <span class="badge badge-inline badge-{{ $class }}">{{ translate(ucfirst(str_replace('_', ' ', $status))) }}</span>
                            </td>
                            <td>
                                @if ($order->payment_status == 'paid')
                                    <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('franchise.orders.show', encrypt($order->id)) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Order Details') }}">
                                    <i class="las la-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $orders->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function sort_orders(el){
            $('#sort_orders').submit();
        }
    </script>
@endsection
