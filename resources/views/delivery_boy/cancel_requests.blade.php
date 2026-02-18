@extends('delivery_boy.layouts.app')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Cancel Requests') }}</h1>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Order Code') }}</th>
                    <th>{{ translate('Date') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th class="text-right">{{ translate('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $key => $order)
                <tr>
                    <td>{{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                    <td>{{ $order->code }}</td>
                    <td>{{ date('d-m-Y', strtotime($order->created_at)) }}</td>
                    <td>
                        <span class="badge badge-warning">{{ translate('Pending Cancel') }}</span>
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
        <div class="aiz-pagination mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
