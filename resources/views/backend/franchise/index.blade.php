@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All City Franchises')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('admin.franchises.create') }}" class="btn btn-primary">
                <span>{{translate('Add New Franchise')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-0 h6">{{translate('Franchises')}}</h5>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Email')}}</th>
                    <th>{{translate('Phone')}}</th>
                    <th>{{translate('State')}}</th>
                    <th>{{translate('City')}}</th>
                    <th>{{translate('Package')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th>{{translate('ID Proof')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($franchises as $key => $franchise)
                    <tr>
                        <td>{{ ($franchises->currentPage()-1) * $franchises->perPage() + $key + 1 }}</td>
                        <td>{{ $franchise->user->name ?? '' }}</td>
                        <td>{{ $franchise->user->email ?? '' }}</td>
                        <td>{{ $franchise->user->phone ?? '' }}</td>
                        <td>{{ $franchise->state->name ?? '' }}</td>
                        <td>{{ $franchise->city->name ?? '' }}</td>
                        <td>{{ $franchise->franchise_package->getTranslation('name') ?? '' }}</td>
                        <td>
                            @if ($franchise->status == 'pending')
                                <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                            @elseif ($franchise->status == 'approved')
                                <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                            @endif
                        </td>
                        <td>
                            @if($franchise->id_proof)
                                <a href="{{ asset('storage/'.$franchise->id_proof) }}" target="_blank" class="btn btn-sm btn-info">{{ translate('View') }}</a>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($franchise->status == 'pending')
                                <a class="btn btn-soft-success btn-icon btn-circle btn-sm" href="{{ route('admin.franchise.approve', ['id'=>$franchise->id, 'type'=>'franchise']) }}" title="{{ translate('Approve') }}">
                                    <i class="las la-check"></i>
                                </a>
                                <a class="btn btn-soft-danger btn-icon btn-circle btn-sm" href="{{ route('admin.franchise.reject', ['id'=>$franchise->id, 'type'=>'franchise']) }}" title="{{ translate('Reject') }}">
                                    <i class="las la-times"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $franchises->links() }}
        </div>
    </div>
</div>

@endsection
