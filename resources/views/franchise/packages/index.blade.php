@extends('franchise.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Franchise Package') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            @if(isset($package))
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Current Package Details') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center border-right">
                                <img src="{{ uploaded_asset($package->logo) }}" class="img-fluid mb-3" style="max-height: 100px;">
                                <h4 class="fw-700 text-primary">{{ $package->getTranslation('name') }}</h4>
                            </div>
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="w-50 fw-600 text-muted">{{ translate('Package Price') }}:</td>
                                        <td class="fw-700 text-primary">{{ single_price($package->price) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 text-muted">{{ translate('Status') }}:</td>
                                        <td>
                                            @if($franchise->package_payment_status == 'paid')
                                                <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                            @elseif($franchise->package_payment_status == 'pending')
                                                <span class="badge badge-inline badge-warning">{{ translate('Pending Verification') }}</span>
                                            @else
                                                <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($package->duration > 0)
                                    <tr>
                                        <td class="w-50 fw-600 text-muted">{{ translate('Duration') }}:</td>
                                        <td>{{ $package->duration }} {{ translate('Days') }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if($franchise->package_payment_status != 'paid')
                    <div class="card mt-4">
                        <div class="card-header bg-soft-warning">
                            <h5 class="mb-0 h6 text-warning">{{ translate('Package Payment (Offline)') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info border-0 mb-4" role="alert">
                                <strong>{{ translate('Offline Payment Instructions') }}:</strong><br>
                                {!! get_setting('franchise_offline_payment_instructions') !!}
                                <p class="mt-2 text-dark">
                                    {{ translate('Please pay') }} <strong>{{ single_price($package->price) }}</strong> {{ translate('to the above account and upload the payment receipt below.') }}
                                </p>
                            </div>

                            @if($franchise->offline_package_payment_proof || $franchise->offline_payment_id)
                                <div class="mb-4 text-center">
                                    @if($franchise->offline_package_payment_proof)
                                        <h6 class="text-muted fw-600 mb-2">{{ translate('Currently Uploaded Proof') }}</h6>
                                        <a href="{{ asset('public/storage/' . $franchise->offline_package_payment_proof) }}" target="_blank" class="btn btn-soft-info btn-sm">
                                            <i class="las la-eye mr-1"></i>{{ translate('View Current Receipt') }}
                                        </a>
                                    @endif
                                    @if($franchise->offline_payment_id)
                                        <div class="mt-2 text-dark">
                                            <strong>{{ translate('Transaction ID') }}:</strong> {{ $franchise->offline_payment_id }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <form action="{{ route('franchise.packages.payment_upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('Transaction ID') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" name="offline_payment_id" class="form-control" placeholder="{{ translate('Enter Transaction ID') }}" required value="{{ $franchise->offline_payment_id }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('Payment Receipt (Image/PDF)') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('payment_proof') is-invalid @enderror" name="payment_proof" id="payment_proof" required>
                                            <label class="custom-file-label" for="payment_proof">{{ translate('Choose file') }}</label>
                                        </div>
                                        @error('payment_proof')
                                            <div class="text-danger mt-1 fs-12">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-0 text-right">
                                    <button type="submit" class="btn btn-primary px-4">{{ translate('Upload & Submit for Approval') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center p-5 card">
                    <i class="las la-frown la-4x text-muted mb-3"></i>
                    <h4>{{ translate('No Package Assigned') }}</h4>
                    <p class="text-muted">{{ translate('Please contact admin to assign a package to your account.') }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).on('change', '.custom-file-input', function() {
            var input = $(this);
            var file = this.files[0];
            var label = input.siblings('.custom-file-label');
            
            if (file) {
                var fileName = file.name;
                label.addClass("selected").html(fileName);
            }
        });
    </script>
@endsection
