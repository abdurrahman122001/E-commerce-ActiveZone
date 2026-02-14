@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Payout History for')}} {{ $employee->name }}</h1>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Payout Logs')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Date')}}</th>
                    <th>{{translate('Amount')}}</th>
                    <th>{{translate('Type')}}</th>
                    <th data-breakpoints="lg">{{translate('Payment Method')}}</th>
                    <th data-breakpoints="lg">{{translate('Remark')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payouts as $key => $payout)
                    <tr>
                        <td>{{ ($key+1) + ($payouts->currentPage() - 1)*$payouts->perPage() }}</td>
                        <td>{{ $payout->created_at->format('d-m-Y H:i A') }}</td>
                        <td>{{ single_price($payout->amount) }}</td>
                        <td>
                            @if($payout->type == 'salary')
                                <span class="badge badge-inline badge-info">{{translate('Salary')}}</span>
                            @else
                                <span class="badge badge-inline badge-success">{{translate('Bonus')}}</span>
                            @endif
                        </td>
                        <td>{{ $payout->payment_method }}</td>
                        <td>{{ $payout->remark }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $payouts->links() }}
        </div>
    </div>
</div>

@endsection
