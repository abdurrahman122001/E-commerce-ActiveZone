@extends('franchise.layouts.app')

@section('panel_content')

@php
    $route_prefix = Auth::guard('franchise_employee')->check() ? 'franchise.employee.' : 'franchise.';
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Delivery Boys')}}</h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="{{ route($route_prefix.'delivery_boys.create') }}" class="btn btn-circle btn-info">
				<span>{{translate('Add New Delivery Boy')}}</span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Delivery Boys')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Email')}}</th>
                    <th data-breakpoints="lg">{{translate('Phone')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th width="10%" class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($delivery_boys as $key => $delivery_boy)
                    @if ($delivery_boy->user != null)
                        <tr>
                            <td>{{ ($key+1) + ($delivery_boys->currentPage() - 1)*$delivery_boys->perPage() }}</td>
                            <td>{{$delivery_boy->user->name}}</td>
                            <td>{{$delivery_boy->user->email}}</td>
                            <td>{{$delivery_boy->user->phone}}</td>
                            <td>
                                @if ($delivery_boy->status == 1)
                                    <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                                @else
                                    <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route($route_prefix.'delivery_boys.edit', $delivery_boy->id)}}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $delivery_boys->links() }}
        </div>
    </div>
</div>

@endsection
