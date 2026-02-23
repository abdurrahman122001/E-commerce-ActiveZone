@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Vendor Registrations by Employees')}}</h1>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Filters')}}</h5>
    </div>
    <div class="card-body">
        <form action="" method="GET">
            <div class="row gutters-5">
                <div class="col-md-3">
                    <select class="form-control aiz-selectpicker" name="franchise_id" id="franchise_id" data-live-search="true">
                        <option value="">{{ translate('All Franchises') }}</option>
                        @foreach($franchises as $franchise)
                            <option value="{{ $franchise->id }}" @if($franchise_id == $franchise->id) selected @endif>{{ $franchise->franchise_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control aiz-selectpicker" name="sub_franchise_id" id="sub_franchise_id" data-live-search="true">
                        <option value="">{{ translate('All Sub-Franchises') }}</option>
                        @foreach($sub_franchises as $sf)
                            <option value="{{ $sf->id }}" @if($sub_franchise_id == $sf->id) selected @endif>
                                {{ $sf->user->name ?? 'Sub-Franchise #'.$sf->id }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control aiz-selectpicker" name="employee_id" id="employee_id" data-live-search="true">
                        <option value="">{{ translate('All Employees') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @if($employee_id == $employee->id) selected @endif>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control aiz-date-range" name="date_range" @isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Select Date Range') }}" autocomplete="off">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                    <a href="{{ route('admin.franchise_employees.vendor_registrations') }}" class="btn btn-outline-secondary">{{ translate('Reset') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Vendor List')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Vendor Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Email')}}</th>
                    <th data-breakpoints="lg">{{translate('Registered By')}}</th>
                    <th data-breakpoints="lg">{{translate('Franchise')}}</th>
                    <th data-breakpoints="lg">{{translate('Status')}}</th>
                    <th data-breakpoints="lg">{{translate('Registration Date')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendors as $key => $vendor)
                    <tr>
                        <td>{{ ($key+1) + ($vendors->currentPage() - 1)*$vendors->perPage() }}</td>
                        <td>{{ $vendor->user->name ?? translate('N/A') }}</td>
                        <td>{{ $vendor->user->email ?? translate('N/A') }}</td>
                        <td>
                            @if($vendor->addedByEmployee)
                                {{ $vendor->addedByEmployee->name }}
                                <br><small class="text-muted">({{ translate('Employee') }})</small>
                            @elseif($vendor->franchise)
                                {{ $vendor->franchise->franchise_name }}
                                <br><small class="text-muted">({{ translate('Franchise Self') }})</small>
                            @else
                                <span class="badge badge-inline badge-secondary">{{ translate('Admin/Direct') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($vendor->sub_franchise)
                                <span class="badge badge-inline badge-info">{{ translate('Sub') }}</span>
                                {{ $vendor->sub_franchise->user->name ?? 'Sub-Franchise #'.$vendor->sub_franchise_id }}
                                <br><small class="text-muted">({{ $vendor->sub_franchise->franchise->franchise_name ?? '' }})</small>
                            @elseif($vendor->franchise)
                                {{ $vendor->franchise->franchise_name }}
                            @else
                                <span class="text-muted">{{ translate('N/A') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($vendor->status == 'approved')
                                <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
                            @elseif($vendor->status == 'rejected')
                                <span class="badge badge-inline badge-danger">{{ translate('Rejected') }}</span>
                            @else
                                <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
                            @endif
                        </td>
                        <td>{{ $vendor->created_at->format('d-m-Y H:i A') }}</td>
                        <td class="text-right">
                            <a href="{{ route('sellers.profile', $vendor->user_id) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('View Profile') }}">
                                <i class="las la-eye"></i>
                            </a>
                            @if($vendor->status == 'pending' || $vendor->status == null)
                                <a href="{{ route('admin.vendors.approve', $vendor->id) }}"
                                   class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                   title="{{ translate('Approve Vendor') }}"
                                   onclick="return confirm('{{ translate('Approve this vendor?') }}')">
                                    <i class="las la-check"></i>
                                </a>
                                <a href="{{ route('admin.vendors.reject', $vendor->id) }}"
                                   class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                   title="{{ translate('Reject Vendor') }}"
                                   onclick="return confirm('{{ translate('Reject this vendor?') }}')">
                                    <i class="las la-times"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $vendors->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection
