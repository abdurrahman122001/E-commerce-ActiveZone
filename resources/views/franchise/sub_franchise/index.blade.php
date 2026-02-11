@extends('franchise.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Sub-Franchises') }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('franchise.sub_franchises.create') }}" class="btn btn-primary">
                <span>{{ translate('Add New Sub-Franchise') }}</span>
            </a>
        </div>
      </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('All Sub-Franchises') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('Email') }}</th>
                        <th>{{ translate('Phone') }}</th>
                        <th>{{ translate('State') }}</th>
                        <th>{{ translate('City') }}</th>
                        <th data-breakpoints="lg">{{ translate('Area') }}</th>
                        <th data-breakpoints="lg">{{ translate('Package') }}</th>
                        <th data-breakpoints="lg">{{ translate('Referral Code') }}</th>
                        <th data-breakpoints="lg">{{ translate('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subFranchises as $key => $sub)
                        <tr>
                            <td>{{ ($key+1) + ($subFranchises->currentPage() - 1)*$subFranchises->perPage() }}</td>
                            <td>{{ $sub->user->name ?? '' }}</td>
                            <td>{{ $sub->user->email ?? '' }}</td>
                            <td>{{ $sub->user->phone ?? '' }}</td>
                            <td>{{ $sub->state->name ?? '' }}</td>
                            <td>{{ $sub->city->name ?? '' }}</td>
                            <td>{{ $sub->area->name ?? '' }}</td>
                            <td>{{ $sub->franchise_package->getTranslation('name') ?? '' }}</td>
                            <td>{{ $sub->referral_code }}</td>
                            <td>
                                @if($sub->status == 'approved')
                                    <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
                                @elseif($sub->status == 'pending')
                                    <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ translate('Rejected') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $subFranchises->links() }}
            </div>
        </div>
    </div>
@endsection
