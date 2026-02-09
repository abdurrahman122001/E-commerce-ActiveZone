@extends($layout)

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Vendor Commission History') }}</h1>
        </div>
      </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Commission History') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>{{ translate('Order Code') }}</th>
                        <th>{{ translate('Vendor') }}</th>
                        <th data-breakpoints="lg">{{ translate('Commission Amount') }}</th>
                        <th data-breakpoints="lg">{{ translate('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commission_history as $key => $history)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if($history->order)
                                    <a href="{{ route('all_orders.show', encrypt($history->order->id)) }}">{{ $history->order->code }}</a>
                                @else
                                    {{ translate('Order Deleted') }}
                                @endif
                            </td>
                            <td>
                                @if($history->vendor && $history->vendor->user)
                                    {{ $history->vendor->user->name }}
                                @else
                                    {{ translate('Vendor Deleted') }}
                                @endif
                            </td>
                            <td>{{ single_price($history->commission_amount) }}</td>
                            <td>{{ $history->created_at->format('d-m-Y H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $commission_history->links() }}
            </div>
        </div>
    </div>
@endsection
