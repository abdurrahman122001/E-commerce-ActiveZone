@extends('delivery_boy.layouts.app')

@section('content')

<div class="row gutters-10">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="las la-check-circle la-3x mb-3 text-success"></i>
                <h3 class="h4">{{ $total_completed }}</h3>
                <p class="text-muted">{{ translate('Completed Deliveries') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="las la-clock la-3x mb-3 text-warning"></i>
                <h3 class="h4">{{ $total_pending }}</h3>
                <p class="text-muted">{{ translate('Pending Deliveries') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="las la-times-circle la-3x mb-3 text-danger"></i>
                <h3 class="h4">{{ $total_cancelled }}</h3>
                <p class="text-muted">{{ translate('Cancelled Deliveries') }}</p>
            </div>
        </div>
    </div>
</div>

@endsection
