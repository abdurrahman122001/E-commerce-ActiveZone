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
                    <select class="form-control aiz-selectpicker" name="employee_id" id="employee_id" data-live-search="true">
                        <option value="">{{ translate('All Employees') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @if($employee_id == $employee->id) selected @endif>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control aiz-date-range" name="date_range" @isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Select Date Range') }}" autocomplete="off">
                </div>
                <div class="col-md-3">
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
                            @if($vendor->franchise)
                                {{ $vendor->franchise->franchise_name }}
                            @else
                                <span class="text-muted">{{ translate('N/A') }}</span>
                            @endif
                        </td>
                        <td>{{ $vendor->created_at->format('d-m-Y H:i A') }}</td>
                        <td class="text-right">
                            <a href="{{ route('sellers.profile', $vendor->user_id) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('View Profile') }}">
                                <i class="las la-eye"></i>
                            </a>
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
