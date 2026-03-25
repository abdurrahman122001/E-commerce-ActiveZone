@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('Vendor Referral History Report')}}</h1>
	</div>
</div>

<div class="row">
    <div class="col-md-11 mx-auto">
        <div class="card">
            <form action="{{ route('vendor-referral-history.index') }}" method="GET">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ translate('Referral Commission History') }}</h5>
                    </div>
                    <div class="col-md-3 ml-auto">
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="referrer_id" data-live-search="true">
                            <option value="">{{ translate('Choose Referrer') }}</option>
                            @foreach ($referrers as $referrer)
                                <option value="{{ $referrer->id }}" @if($referrer->id == $referrer_id) selected @endif >
                                    {{ $referrer->user->name ?? $referrer->shop_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <input type="text" class="form-control form-control-sm aiz-date-range" id="search" name="date_range"@isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Daterange') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-sm btn-primary" type="submit">
                            {{ translate('Filter') }}
                        </button>
                    </div>
                </div>
            </form>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Referrer') }}</th>
                            <th>{{ translate('Referred Vendor') }}</th>
                            <th data-breakpoints="lg">{{ translate('Package') }}</th>
                            <th>{{ translate('Commission') }}</th>
                            <th>{{ translate('Amount') }}</th>
                            <th data-breakpoints="lg">{{ translate('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($referral_history as $key => $history)
                        <tr>
                            <td>{{ ($key+1) + ($referral_history->currentPage() - 1)*$referral_history->perPage() }}</td>
                             <td>
                                 @if($history->referrer)
                                     {{ $history->referrer->shop_name ?? ($history->referrer->user->name ?? translate('N/A')) }}
                                     <br><small class="badge badge-inline badge-info">{{ $history->referrer->referral_code }}</small>
                                 @else
                                     <span class="text-danger">{{ translate('Deleted') }}</span>
                                 @endif
                             </td>
                            <td>
                                @if($history->referredVendor)
                                    {{ $history->referredVendor->user->name ?? $history->referredVendor->shop_name }}
                                @else
                                    <span class="text-danger">{{ translate('Deleted') }}</span>
                                @endif
                            </td>
                            <td>{{ $history->package->name ?? translate('N/A') }}</td>
                            <td>
                                {{ $history->commission_value }}
                                @if($history->commission_type == 'percentage') % @else ({{ translate('Flat') }}) @endif
                            </td>
                            <td>{{ single_price($history->amount) }}</td>
                            <td>{{ $history->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    {{ $referral_history->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
