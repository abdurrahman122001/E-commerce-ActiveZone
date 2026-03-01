@extends('franchise.layouts.app')

@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 text-primary">{{ translate('Package Commission Report') }}</h1>
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
            <h5 class="mb-0 h6">{{translate('Commission History')}}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th>{{translate('Source (Who Bought)')}}</th>
                        <th>{{translate('Package')}}</th>
                        <th>{{translate('Package Price')}}</th>
                        <th>{{translate('Commission %')}}</th>
                        <th>{{translate('Your Earning')}}</th>
                        <th data-breakpoints="lg">{{translate('Type')}}</th>
                        <th data-breakpoints="lg">{{translate('Date')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $key => $history)
                        <tr>
                            <td>{{ ($key+1) + ($histories->currentPage() - 1)*$histories->perPage() }}</td>
                            <td>
                                @if($history->type == 'city_to_state')
                                    <span class="badge badge-inline badge-info">{{ translate('City Franchise') }}:</span>
                                    {{ $history->franchise->user->name ?? translate('N/A') }}
                                @elseif($history->type == 'sub_to_city' || $history->type == 'sub_to_state')
                                    <span class="badge badge-inline badge-success">{{ translate('Sub Franchise') }}:</span>
                                    {{ $history->sub_franchise->user->name ?? translate('N/A') }}
                                @else
                                    {{ translate('N/A') }}
                                @endif
                            </td>
                            <td>{{ $history->franchise_package->name ?? translate('N/A') }}</td>
                            <td>{{ single_price($history->franchise_package->price ?? 0) }}</td>
                            <td>{{ $history->percentage }}%</td>
                            <td class="text-success fw-700">
                                {{ single_price($history->amount) }}
                            </td>
                            <td>
                                @php
                                    $type_text = str_replace('_', ' ', $history->type);
                                    $type_text = ucwords($type_text);
                                @endphp
                                {{ translate($type_text) }}
                            </td>
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
