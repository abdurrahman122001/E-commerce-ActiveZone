@extends($layout)

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Vendors') }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('vendors.create') }}" class="btn btn-primary">
                <span>{{ translate('Add New Vendor') }}</span>
            </a>
        </div>
      </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('All Vendors') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('Email') }}</th>
                        <th data-breakpoints="lg">{{ translate('Total Orders') }}</th>
                        <th data-breakpoints="lg">{{ translate('Total Sales') }}</th>
                        <th>{{ translate('Commission (%)') }}</th>
                        <th>{{ translate('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $key => $vendor)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $vendor->user->name ?? '' }}</td>
                            <td>{{ $vendor->user->email ?? '' }}</td>
                            <td>{{ $vendor->orders_count }}</td>
                            <td>{{ single_price($vendor->orders_sum_grand_total) }}</td>
                            <td>{{ $vendor->commission_percentage }}</td>
                            <td>
                                @if($vendor->status == 'approved')
                                    <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
                                @elseif($vendor->status == 'pending')
                                    <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ translate('Rejected') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
