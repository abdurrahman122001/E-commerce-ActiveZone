@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Franchise Payment History')}}</h5>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="mb-0 h6">{{translate('Payment History')}}</h3>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th data-breakpoints="lg">{{translate('Date')}}</th>
                    <th>{{translate('Franchise')}}</th>
                    <th>{{translate('Amount')}}</th>
                    <th data-breakpoints="lg">{{ translate('Payment Details') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $key => $payment)
                    @php 
                        $user = \App\Models\User::find($payment->seller_id); 
                    @endphp
                    @if ($user)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $payment->created_at }}</td>
                            <td>
                                {{ $user->name }}
                                @if($user->franchise)
                                    ({{ $user->franchise->franchise_name }})
                                @elseif($user->sub_franchise)
                                    ({{ translate('Sub-Franchise') }})
                                @endif
                            </td>
                            <td>
                                {{ single_price($payment->amount) }}
                            </td>
                            <td>{{ translate(ucfirst(str_replace('_', ' ', $payment->payment_method))) }} @if ($payment->txn_code != null) ({{ translate('TRX ID') }} : {{ $payment->txn_code }}) @endif</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
              {{ $payments->links() }}
        </div>
    </div>
</div>

@endsection
