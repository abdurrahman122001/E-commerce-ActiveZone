@extends('vendors.layouts.app')

@section('panel_content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-0">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <i class="las la-clock text-warning" style="font-size: 80px;"></i>
                </div>
                <h2 class="fw-700 mb-3">{{ translate('Registration Pending Approval') }}</h2>
                <p class="fs-16 text-muted mb-4">
                    {{ translate('Thank you for choosing our platform!') }} <br>
                    {{ translate('Your selected package is') }}: <strong class="text-primary">{{ $vendor->franchise_package?->getTranslation('name') ?? translate('N/A') }}</strong>
                </p>
                <div class="alert alert-info border-0 rounded-0 py-3 mb-4">
                    <i class="las la-info-circle mr-2"></i>
                    {{ translate('Our administration team is currently reviewing your registration and payment. You will receive an email notification once your shop is activated.') }}
                </div>
                <p class="text-secondary mb-0">
                    {{ translate('Need help?') }} <a href="mailto:{{ get_setting('contact_email') }}" class="fw-600">{{ translate('Contact Support') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
