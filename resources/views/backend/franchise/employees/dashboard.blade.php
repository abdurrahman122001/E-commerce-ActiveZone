@extends('backend.franchise.employees.layout')

@section('panel_content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Employee Dashboard') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <form action="{{ route('franchise.employee.logout') }}" method="POST" style="display:inline-block;">
                @csrf
                <button type="submit" class="btn btn-soft-danger">{{ translate('Logout') }}</button>
            </form>
            @if(Auth::guard('franchise_employee')->user()->status == 'approved')
                <a href="{{ route('franchise.employee.vendors.create') }}" class="btn btn-primary">
                    {{ translate('Add New Vendor') }}
                </a>
            @endif
        </div>
    </div>
</div>

@if(Auth::guard('franchise_employee')->user()->status == 'approved')
    <div class="row gutters-10">
        <div class="col-md-3">
            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50">
                        <i class="las la-users la-2x"></i>
                    </div>
                    <div class="mb-2 text-center">
                        <span class="fs-22 fw-600 d-block">{{ $vendors_today }}</span>
                        <span class="opacity-50">{{ translate('Registered Today') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50">
                        <i class="las la-calendar-week la-2x"></i>
                    </div>
                    <div class="mb-2 text-center">
                        <span class="fs-22 fw-600 d-block">{{ $vendors_week }}</span>
                        <span class="opacity-50">{{ translate('Registered This Week') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50">
                        <i class="las la-calendar-alt la-2x"></i>
                    </div>
                    <div class="mb-2 text-center">
                        <span class="fs-22 fw-600 d-block">{{ $vendors_month }}</span>
                        <span class="opacity-50">{{ translate('Registered This Month') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-50">
                        <i class="las la-calendar la-2x"></i>
                    </div>
                    <div class="mb-2 text-center">
                        <span class="fs-22 fw-600 d-block">{{ $vendors_year }}</span>
                        <span class="opacity-50">{{ translate('Registered This Year') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Recently Registered Vendors') }}</h5>
            </div>
            <div class="col-md-4">
                <form action="" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control aiz-date-range" name="date_range" value="{{ $date_range }}" placeholder="{{ translate('Filter by date range') }}" autocomplete="off">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">{{ translate('Filter') }}</button>
                            <a href="{{ route('franchise.employee.dashboard') }}" class="btn btn-soft-secondary">{{ translate('Reset') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('Email') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th>{{ translate('Registered At') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $key => $vendor)
                        <tr>
                            <td>{{ ($key+1) + ($vendors->currentPage() - 1)*$vendors->perPage() }}</td>
                            <td>{{ $vendor->user->name ?? translate('N/A') }}</td>
                            <td>{{ $vendor->user->email ?? translate('N/A') }}</td>
                            <td>
                                @if ($vendor->status == 'approved')
                                    <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
                                @elseif ($vendor->status == 'pending')
                                    <span class="badge badge-inline badge-info">{{ translate('Pending') }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ translate('Rejected') }}</span>
                                @endif
                            </td>
                            <td>{{ $vendor->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $vendors->links() }}
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center p-5">
            <div class="mb-4">
                <i class="las la-clock text-warning" style="font-size: 80px;"></i>
            </div>
            <h2 class="h4 fw-700">{{ translate('Account Pending Approval') }}</h2>
            <p class="fs-16 text-muted mb-0">
                {{ translate('Your account is currently under review by the administrator.') }}<br>
                {{ translate('You will be able to access all features like vendor registration and reports once your account is approved.') }}
            </p>
            <div class="mt-4">
                <span class="badge badge-inline badge-warning p-3 fs-14">{{ translate('Current Status: Pending') }}</span>
            </div>
        </div>
    </div>
@endif
@endsection
