@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Franchise Sales & Commission Report')}}</h1>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Filters')}}</h5>
    </div>
    <div class="card-body">
        <form action="" method="GET">
            <div class="row gutters-5">
                <div class="col-md-3">
                    <select class="form-control aiz-selectpicker" name="franchise_id" id="franchise_id" data-live-search="true">
                        <option value="">{{ translate('All Franchises') }}</option>
                        @foreach($franchises as $franchise)
                            <option value="{{ $franchise->id }}" @if($franchise_id == $franchise->id) selected @endif>{{ $franchise->franchise_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control aiz-selectpicker" name="sub_franchise_id" id="sub_franchise_id" data-live-search="true">
                        <option value="">{{ translate('All Sub Franchises') }}</option>
                        @foreach($sub_franchises as $sub)
                            <option value="{{ $sub->id }}" @if($sub_franchise_id == $sub->id) selected @endif>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control aiz-date-range" name="date_range" @isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Select Date Range') }}" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                    <a href="{{ route('admin.franchise_employees.sales_report') }}" class="btn btn-outline-secondary">{{ translate('Reset') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
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
                    <th data-breakpoints="lg">{{translate('Franchise')}}</th>
                    <th data-breakpoints="lg">{{translate('Sub Franchise')}}</th>
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
                        <td>{{ $history->franchise->franchise_name ?? translate('N/A') }}</td>
                        <td>{{ $history->sub_franchise->name ?? translate('N/A') }}</td>
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
