@extends('backend.franchise.employees.layout')

@section('panel_content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Earnings & Payouts') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-none rounded-0 border">
                <div class="card-body">
                    <div class="text-center">
                        <span class="avatar avatar-md border p-1 mb-3">
                            <i class="las la-money-check-alt fs-24 text-primary"></i>
                        </span>
                        <h5 class="mb-1 fw-600">{{ single_price($payouts->where('type', 'salary')->sum('amount')) }}</h5>
                        <div class="text-muted">{{ translate('Total Salary Received') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-none rounded-0 border">
                <div class="card-body">
                    <div class="text-center">
                        <span class="avatar avatar-md border p-1 mb-3">
                            <i class="las la-gift fs-24 text-success"></i>
                        </span>
                        <h5 class="mb-1 fw-600">{{ single_price($payouts->where('type', 'bonus')->sum('amount')) }}</h5>
                        <div class="text-muted">{{ translate('Total Bonus Received') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-none rounded-0 border">
                <div class="card-body">
                    <div class="text-center">
                        <span class="avatar avatar-md border p-1 mb-3">
                            <i class="las la-wallet fs-24 text-info"></i>
                        </span>
                        <h5 class="mb-1 fw-600">{{ single_price($payouts->sum('amount')) }}</h5>
                        <div class="text-muted">{{ translate('Total Earnings') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Payout History') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th>{{ translate('Date') }}</th>
                        <th>{{ translate('Amount') }}</th>
                        <th>{{ translate('Type') }}</th>
                        <th data-breakpoints="lg">{{ translate('Payment Method') }}</th>
                        <th data-breakpoints="lg">{{ translate('Remark') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payouts as $key => $payout)
                        <tr>
                            <td>{{ ($key+1) + ($payouts->currentPage() - 1)*$payouts->perPage() }}</td>
                            <td>{{ $payout->created_at->format('d-m-Y') }}</td>
                            <td>{{ single_price($payout->amount) }}</td>
                            <td>
                                @if($payout->type == 'salary')
                                    <span class="badge badge-inline badge-info">{{ translate('Salary') }}</span>
                                @else
                                    <span class="badge badge-inline badge-success">{{ translate('Bonus') }}</span>
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
