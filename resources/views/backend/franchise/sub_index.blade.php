@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Sub Franchises')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('admin.sub_franchises.create') }}" class="btn btn-primary">
                <span>{{translate('Add New Sub-Franchise')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-0 h6">{{translate('Sub-Franchises')}}</h5>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Email')}}</th>
                    <th>{{translate('State')}}</th>
                    <th>{{translate('City')}}</th>
                    <th>{{translate('Area')}}</th>
                    <th>{{translate('Parent Franchise')}}</th>
                    <th>{{translate('Package')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th>{{translate('ID Proof')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subFranchises as $key => $sub)
                    <tr>
                        <td>{{ ($subFranchises->currentPage()-1) * $subFranchises->perPage() + $key + 1 }}</td>
                        <td>{{ $sub->user->name ?? '' }}</td>
                        <td>{{ $sub->user->email ?? '' }}</td>
                        <td>{{ $sub->state->name ?? '' }}</td>
                        <td>{{ $sub->city->name ?? '' }}</td>
                        <td>{{ $sub->area->name ?? '' }}</td>
                        <td>{{ $sub->franchise->franchise_name ?? translate('No Parent') }}</td>
                        <td>{{ $sub->franchise_package ? $sub->franchise_package->getTranslation('name') : '' }}</td>
                        <td>
                            @if ($sub->status == 'pending')
                                <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                            @elseif ($sub->status == 'approved')
                                <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                            @endif
                        </td>
                        <td>
                            @if($sub->id_proof)
                                <a href="{{ asset('storage/'.$sub->id_proof) }}" target="_blank" class="btn btn-sm btn-info">{{ translate('View') }}</a>
                            @endif
                        </td>
                        <td class="text-right">
                             @if($sub->status == 'pending')
                                <a class="btn btn-soft-success btn-icon btn-circle btn-sm" href="{{ route('admin.franchise.approve', ['id'=>$sub->id, 'type'=>'sub_franchise']) }}" title="{{ translate('Approve') }}">
                                    <i class="las la-check"></i>
                                </a>
                                <a class="btn btn-soft-danger btn-icon btn-circle btn-sm" href="{{ route('admin.franchise.reject', ['id'=>$sub->id, 'type'=>'sub_franchise']) }}" title="{{ translate('Reject') }}">
                                    <i class="las la-times"></i>
                                </a>
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
