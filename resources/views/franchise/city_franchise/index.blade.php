@extends('franchise.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('City Franchises') }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('franchise.city_franchises.create') }}" class="btn btn-primary">
                <span>{{ translate('Add New City Franchise') }}</span>
            </a>
        </div>
      </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('All City Franchises') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('Email') }}</th>
                        <th>{{ translate('Phone') }}</th>
                        <th>{{ translate('City') }}</th>
                        <th data-breakpoints="lg">{{ translate('Package') }}</th>
                        <th data-breakpoints="lg">{{ translate('Referral Code') }}</th>
                        <th data-breakpoints="lg">{{ translate('Status') }}</th>
                        <th>{{ translate('Commission (%)') }}</th>
                        <th class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cityFranchises as $key => $franchise)
                        <tr>
                            <td>{{ ($key+1) + ($cityFranchises->currentPage() - 1)*$cityFranchises->perPage() }}</td>
                            <td>{{ $franchise->user->name ?? '' }}</td>
                            <td>{{ $franchise->user->email ?? '' }}</td>
                            <td>{{ $franchise->user->phone ?? '' }}</td>
                            <td>{{ $franchise->city->name ?? '' }}</td>
                            <td>{{ $franchise->franchise_package ? $franchise->franchise_package->getTranslation('name') : '' }}</td>
                            <td>{{ $franchise->referral_code }}</td>
                            <td>
                                @if($franchise->status == 'approved')
                                    <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
                                @elseif($franchise->status == 'pending')
                                    <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ translate('Rejected') }}</span>
                                @endif
                            </td>
                            <td>{{ $franchise->commission_percentage }}%</td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                        <i class="las la-ellipsis-v seller-list-icon"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                        <a href="{{route('franchise.city_franchises.login', encrypt($franchise->id))}}" class="dropdown-item">
                                            {{translate('Login')}}
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $cityFranchises->links() }}
            </div>
        </div>
    </div>
@endsection
