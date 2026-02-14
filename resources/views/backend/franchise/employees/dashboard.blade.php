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
            <a href="{{ route('franchise.employee.vendors.create') }}" class="btn btn-primary">
                {{ translate('Add New Vendor') }}
            </a>
        </div>
    </div>
</div>

<div class="row gutters-10">
    <div class="col-md-4">
        <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
            <div class="px-3 pt-3">
                <div class="opacity-50">
                    <i class="las la-users la-3x"></i>
                </div>
                <div class="mb-2 text-center">
                    <span class="fs-26 fw-600 d-block">{{ $vendors_count }}</span>
                    <span class="opacity-50">{{ translate('Total Vendors Registered') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Recently Registered Vendors') }}</h5>
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
@endsection
