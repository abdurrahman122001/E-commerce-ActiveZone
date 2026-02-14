@extends('franchise.layouts.app')

@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 text-primary">{{ translate('Sales & Commission Report') }}</h1>
            </div>
        </div>
    </div>

    <div class="card shadow-none mb-4">
        <div class="card-header border-bottom-0">
            <h5 class="mb-0 h6">{{translate('Filters')}}</h5>
        </div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="row gutters-5">
                    <div class="col-md-8">
                        <input type="text" class="form-control aiz-date-range" name="date_range" @isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Select Date Range') }}" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-block">{{ translate('Filter') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-none">
        <div class="card-header border-bottom-0">
            <h5 class="mb-0 h6">{{translate('Sales List')}}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th>{{translate('Order Code')}}</th>
                        <th>{{translate('Product')}}</th>
                        <th data-breakpoints="lg">{{translate('Vendor')}}</th>
                        <th>{{translate('Commission')}}</th>
                        <th data-breakpoints="lg">{{translate('Date')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $key => $history)
                        <tr>
                            <td>{{ ($key+1) + ($histories->currentPage() - 1)*$histories->perPage() }}</td>
                            <td>{{ $history->order->code ?? translate('N/A') }}</td>
                            <td>
                                @if($history->order_detail && $history->order_detail->product)
                                    {{ $history->order_detail->product->name }}
                                @else
                                    {{ translate('N/A') }}
                                @endif
                            </td>
                            <td>{{ $history->vendor->user->name ?? translate('N/A') }}</td>
                            <td>{{ single_price($history->commission_amount) }}</td>
                            <td>{{ $history->created_at->format('d-m-Y H:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $histories->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection
