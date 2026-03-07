@extends('backend.layouts.app')

@section('content')

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Delivery Boy Configuration')}}</h5>
        </div>
        <div class="card-body">
            <p>{{ translate('Configuration options for delivery boys.') }}</p>
            <!-- Add settings as needed -->
        </div>
    </div>
</div>

@endsection
