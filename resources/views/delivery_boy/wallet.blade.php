@extends('delivery_boy.layouts.app')

@section('content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('My Wallet') }}</h1>
        </div>
    </div>
</div>

@php
    $delivery_boy = Auth::user()->delivery_boy;
@endphp

<div class="row gutters-10">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>{{ translate('Total Earnings') }}</h5>
                <h2>{{ single_price($delivery_boy->total_earning ?? 0) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h5>{{ translate('Cash Collected') }}</h5>
                <h2>{{ single_price($delivery_boy->cash_collected ?? 0) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5>{{ translate('Balance') }}</h5>
                <h2>{{ single_price(($delivery_boy->total_earning ?? 0) - ($delivery_boy->cash_collected ?? 0)) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row gutters-10 mt-3">
    <div class="col-md-12">
        <div class="card mb-3 mb-lg-0">
            <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fs-16">{{ translate('Withdraw Request history')}}</h5>
                <button class="btn btn-primary" onclick="show_withdraw_modal()">{{ translate('Send Withdraw Request') }}</button>
            </div>
            <div class="card-body p-0">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Amount')}}</th>
                            <th>{{ translate('Status')}}</th>
                            <th>{{ translate('Message')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($withdraw_requests) > 0)
                            @foreach ($withdraw_requests as $key => $withdraw_request)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ date('d-m-Y', strtotime($withdraw_request->created_at)) }}</td>
                                    <td>{{ single_price($withdraw_request->amount) }}</td>
                                    <td>
                                        @if ($withdraw_request->status == 1)
                                            <span class="badge badge-inline badge-success">{{ translate('Paid')}}</span>
                                        @else
                                            <span class="badge badge-inline badge-warning">{{ translate('Pending')}}</span>
                                        @endif
                                    </td>
                                    <td>{{ $withdraw_request->message }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="5">{{ translate('No data found') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $withdraw_requests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('modal')
<div class="modal fade" id="withdraw_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('Send A Withdraw Request') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php
                    $balance = ($delivery_boy->total_earning ?? 0) - ($delivery_boy->cash_collected ?? 0);
                @endphp
                @if ($balance > 0)
                    <form class="" action="{{ route('delivery-boy.withdraw_request.store') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ translate('Amount') }}</label>
                            <div class="col-sm-9">
                                <input type="number" step="0.01" lang="en" class="form-control mb-3" name="amount" min="1" max="{{ $balance }}" placeholder="{{ translate('Amount') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ translate('Message') }}</label>
                            <div class="col-sm-9">
                                <textarea name="message" rows="5" class="form-control mb-3" placeholder="{{ translate('Specify your bank details or message.') }}"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9 text-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                                <button type="submit" class="btn btn-primary">{{ translate('Send') }}</button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-center alert alert-warning mb-0">
                        {{ translate('You do not have enough balance to send withdraw request') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    function show_withdraw_modal(){
        $('#withdraw_modal').modal('show');
    }
</script>
@endsection
