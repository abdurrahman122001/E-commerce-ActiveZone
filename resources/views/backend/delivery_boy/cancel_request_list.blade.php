@extends('backend.layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Delivery Boy Cancel Requests')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Order ID')}}</th>
                    <th>{{translate('Delivery Boy')}}</th>
                    <th>{{translate('Reason')}}</th>
                    <th>{{translate('Date')}}</th>
                </tr>
            </thead>
            <tbody>
                <!-- Records will go here -->
            </tbody>
        </table>
    </div>
</div>

@endsection
