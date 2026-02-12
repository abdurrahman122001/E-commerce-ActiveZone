@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Franchise Profile')}}</h5>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{translate('Name')}}</label>
                            <p>{{ $user->name }}</p>
                        </div>
                        <div class="form-group">
                            <label>{{translate('Email')}}</label>
                            <p>{{ $user->email }}</p>
                        </div>
                        <div class="form-group">
                            <label>{{translate('Phone')}}</label>
                            <p>{{ $user->phone }}</p>
                        </div>
                        <div class="form-group">
                             <label>{{translate('Status')}}</label>
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
                    <div class="col-md-6">
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
                             <div class="form-group">
                                <label>{{translate('ID Proof')}}</label>
                                @if($user->franchise->id_proof)
                                    <br>
                                    <a href="{{ asset('storage/'.$user->franchise->id_proof) }}" target="_blank" class="btn btn-sm btn-info">{{ translate('View') }}</a>
                                @else
                                    <p>{{ translate('Not Uploaded') }}</p>
                                @endif
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
                             <div class="form-group">
                                <label>{{translate('ID Proof')}}</label>
                                @if($user->sub_franchise->id_proof)
                                    <br>
                                    <a href="{{ asset('storage/'.$user->sub_franchise->id_proof) }}" target="_blank" class="btn btn-sm btn-info">{{ translate('View') }}</a>
                                @else
                                    <p>{{ translate('Not Uploaded') }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
