@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Franchise Profile')}}</h5>
</div>

<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body text-center">
                <span class="avatar avatar-xl mb-3">
                    @if($user->franchise && $user->franchise->photo)
                        <img src="{{ uploaded_asset($user->franchise->photo) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @elseif($user->sub_franchise && $user->sub_franchise->photo)
                        <img src="{{ uploaded_asset($user->sub_franchise->photo) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @else
                        <img src="{{ static_asset('assets/img/avatar-place.png') }}">
                    @endif
                </span>
                <h1 class="h5 mb-1">{{ $user->name }}</h1>
                <p class="text-muted mb-0">{{ $user->email }}</p>
                <p class="text-muted">{{ $user->phone }}</p>
                
                <div class="mt-3">
                     @if ($user->franchise && $user->franchise->status == 'pending')
                        <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                    @elseif ($user->franchise && $user->franchise->status == 'approved')
                        <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                    @elseif($user->sub_franchise && $user->sub_franchise->status == 'pending')
                        <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                    @elseif($user->sub_franchise && $user->sub_franchise->status == 'approved')
                        <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                    @else
                        <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Basic Info')}}</h5>
            </div>
            <div class="card-body">
                 @if($user->franchise)
                    <div class="form-group">
                        <label>{{translate('Franchise Name')}}</label>
                        <p>{{ $user->franchise->franchise_name }}</p>
                    </div>
                    <div class="form-group">
                        <label>{{translate('State')}}</label>
                        <p>{{ $user->franchise->state->name ?? '' }}</p>
                    </div>
                    <div class="form-group">
                        <label>{{translate('City')}}</label>
                        <p>{{ $user->franchise->city->name ?? '' }}</p>
                    </div>
                     <div class="form-group">
                        <label>{{translate('Package')}}</label>
                        <p>{{ $user->franchise->franchise_package->name ?? '' }}</p>
                    </div>
                @elseif($user->sub_franchise)
                    <div class="form-group">
                        <label>{{translate('Parent Franchise')}}</label>
                        <p>{{ $user->sub_franchise->franchise->franchise_name ?? translate('No Parent') }}</p>
                    </div>
                     <div class="form-group">
                        <label>{{translate('State')}}</label>
                        <p>{{ $user->sub_franchise->state->name ?? '' }}</p>
                    </div>
                    <div class="form-group">
                        <label>{{translate('City')}}</label>
                        <p>{{ $user->sub_franchise->city->name ?? '' }}</p>
                    </div>
                    <div class="form-group">
                        <label>{{translate('Area')}}</label>
                        <p>{{ $user->sub_franchise->area->name ?? '' }}</p>
                    </div>
                     <div class="form-group">
                        <label>{{translate('Package')}}</label>
                        <p>{{ $user->sub_franchise->franchise_package->name ?? '' }}</p>
                    </div>
                @endif
                <hr>
                <h5 class="mb-3 h6">{{translate('Verification Documents')}}</h5>
                @php
                    $id_proof = $user->franchise ? $user->franchise->id_proof : ($user->sub_franchise ? $user->sub_franchise->id_proof : null);
                    $pan_number = $user->franchise ? $user->franchise->pan_number : ($user->sub_franchise ? $user->sub_franchise->pan_number : null);
                @endphp
                <div class="form-group">
                    <label>{{translate('PAN Number')}}</label>
                    <p>{{ $pan_number ?? translate('Not Provided') }}</p>
                </div>
                <div class="form-group">
                    <label>{{translate('Aadhar Card')}}</label>
                    @if($id_proof)
                        <div>
                            <a href="{{ asset('storage/'.$id_proof) }}" target="_blank" class="btn btn-sm btn-soft-info">{{ translate('View Document') }}</a>
                        </div>
                    @else
                        <p>{{ translate('Not Provided') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                     <div class="card-body text-center">
                        <h1 class="display-4">{{ $subFranchises->count() }}</h1>
                        <p>{{ translate('Sub-Franchises') }}</p>
                     </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                     <div class="card-body text-center">
                        <h1 class="display-4">{{ $employees->count() }}</h1>
                        <p>{{ translate('Employees') }}</p>
                     </div>
                </div>
            </div>
            <div class="col-md-4">
                 <div class="card bg-success text-white">
                     <div class="card-body text-center">
                        <h1 class="display-4">{{ $vendors->count() }}</h1>
                        <p>{{ translate('Vendors') }}</p>
                     </div>
                </div>
            </div>
        </div>

        @if($subFranchises->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Sub-Franchises')}}</h5>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{translate('Name')}}</th>
                            <th>{{translate('Email')}}</th>
                            <th>{{translate('Phone')}}</th>
                            <th>{{translate('Area')}}</th>
                            <th>{{translate('Status')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subFranchises as $sub)
                            <tr>
                                <td>{{ $sub->user->name ?? 'N/A' }}</td>
                                <td>{{ $sub->user->email ?? 'N/A' }}</td>
                                <td>{{ $sub->user->phone ?? 'N/A' }}</td>
                                <td>{{ $sub->area->name ?? '' }}</td>
                                <td>
                                    @if ($sub->status == 'pending')
                                        <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                                    @elseif ($sub->status == 'approved')
                                        <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                 <h5 class="mb-0 h6">{{translate('Employees & Vendors Stats')}}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>{{ translate('Employees') }} ({{ $employees->count() }})</h6>
                         <ul class="list-group">
                            @forelse($employees->take(5) as $employee)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $employee->name }}
                                    <span class="badge badge-primary badge-pill">{{ $employee->role }}</span>
                                </li>
                            @empty
                                <li class="list-group-item">{{ translate('No employees found.') }}</li>
                            @endforelse
                             @if($employees->count() > 5)
                                <li class="list-group-item text-center">
                                    <a href="#">{{ translate('View All') }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>{{ translate('Vendors') }} ({{ $vendors->count() }})</h6>
                        <ul class="list-group">
                             @forelse($vendors->take(5) as $vendor)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $vendor->user->name ?? 'N/A' }}
                                    @if($vendor->status == 1)
                                        <span class="badge badge-success badge-pill">{{ translate('Active') }}</span>
                                    @else
                                        <span class="badge badge-secondary badge-pill">{{ translate('Inactive') }}</span>
                                    @endif
                                </li>
                            @empty
                                <li class="list-group-item">{{ translate('No vendors found.') }}</li>
                            @endforelse
                             @if($vendors->count() > 5)
                                <li class="list-group-item text-center">
                                    <a href="#">{{ translate('View All') }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
