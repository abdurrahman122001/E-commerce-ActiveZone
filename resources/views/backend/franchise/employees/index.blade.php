@extends('franchise.layouts.app')

@section('panel_content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Franchise Employees')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('franchise.employees.create') }}" class="btn btn-primary">
                <span>{{translate('Add New Employee')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-0 h6">{{translate('Employees')}}</h5>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Email')}}</th>
                    <th>{{translate('Mobile')}}</th>
                    <th>{{translate('Role')}}</th>
                    <th>{{translate('Level')}}</th>
                    <th>{{translate('City')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $key => $employee)
                    <tr>
                        <td>{{ ($employees->currentPage()-1) * $employees->perPage() + $key + 1 }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->mobile }}</td>
                        <td>{{ $employee->role }}</td>
                        <td>
                            @if($employee->franchise_level == 'CITY')
                                <span class="badge badge-inline badge-primary">{{translate('City')}}</span>
                            @else
                                <span class="badge badge-inline badge-info">{{translate('Sub Franchise')}}</span>
                            @endif
                        </td>
                        <td>{{ $employee->city->name ?? '' }}</td>
                        <td>
                            @if($employee->status == 'pending')
                                <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                            @elseif($employee->status == 'approved')
                                <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                            @elseif($employee->status == 'rejected')
                                <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('franchise.employees.edit', $employee->id) }}" title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $employees->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
