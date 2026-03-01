@extends('vendors.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="text-center">
            <h1 class="h3 fw-700 text-primary">{{ translate('Choose Your Vendor Package') }}</h1>
            <p class="text-muted">{{ translate('You are currently in unpaid status. Please select a package to activate your shop and start selling.') }}</p>
        </div>
    </div>

    <div class="row justify-content-center">
        @forelse($packages as $key => $package)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-0 position-relative overflow-hidden package-card" style="transition: all 0.3s ease;">
                    @if($key == 1)
                        <div class="position-absolute text-white px-3 py-1 fs-11 fw-700" style="background: linear-gradient(135deg, #f5576c, #ff6b6b); top:15px; right:-30px; transform: rotate(45deg); width:130px; text-align:center; z-index: 1;">
                            {{ translate('Popular') }}
                        </div>
                    @endif
                    <div class="card-body p-4 d-flex flex-column">
                        {{-- Package Logo --}}
                        <div class="text-center mb-3">
                            @if($package->logo)
                                <img src="{{ uploaded_asset($package->logo) }}" alt="{{ $package->getTranslation('name') }}" class="img-fluid mb-3" style="max-height: 80px;">
                            @else
                                <div class="d-inline-flex align-items-center justify-content-center bg-soft-primary rounded-circle mb-3" style="width: 80px; height: 80px;">
                                    <i class="las la-box fs-40 text-primary"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Package Name --}}
                        <h4 class="fw-700 text-center mb-2">{{ $package->getTranslation('name') }}</h4>

                        {{-- Price --}}
                        <div class="text-center mb-4">
                            <span class="fw-700" style="font-size: 2.5rem; color: #1a1a2e;">{{ single_price($package->price) }}</span>
                            @if($package->duration > 0)
                                <span class="text-secondary fs-14">/ {{ $package->duration }} {{ translate('Days') }}</span>
                            @else
                                <span class="text-secondary fs-14">/ {{ translate('Lifetime') }}</span>
                            @endif
                        </div>

                        {{-- Details --}}
                        <ul class="list-unstyled mb-4 flex-grow-1">
                            <li class="d-flex align-items-center py-3 border-bottom">
                                <i class="las la-check-circle text-success mr-3 fs-20"></i>
                                <span class="fs-14">
                                    {{ translate('Product Limit') }}: 
                                    <strong class="text-dark">{{ $package->product_limit }} {{ translate('Products') }}</strong>
                                </span>
                            </li>
                            <li class="d-flex align-items-center py-3 border-bottom">
                                <i class="las la-clock text-primary mr-3 fs-20"></i>
                                <span class="fs-14">
                                    {{ translate('Duration') }}: 
                                    <strong class="text-dark">{{ $package->duration > 0 ? $package->duration . ' ' . translate('Days') : translate('Lifetime') }}</strong>
                                </span>
                            </li>
                            @if($package->features)
                                @foreach(array_filter(array_map('trim', preg_split('/[\n,]+/', $package->features))) as $feature)
                                    <li class="d-flex align-items-center py-3 border-bottom">
                                        <i class="las la-check-circle text-success mr-3 fs-20"></i>
                                        <span class="fs-14">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            @endif
                        </ul>

                        {{-- Select Button --}}
                        <button type="button" class="btn {{ $key == 1 ? 'btn-primary' : 'btn-soft-primary' }} btn-block rounded-0 py-3 fw-700 mt-auto shadow-none border-0" onclick="showPurchaseModal('{{ $package->id }}', '{{ $package->getTranslation('name') }}', '{{ single_price($package->price) }}')">
                            {{ translate('Choose This Package') }} <i class="las la-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white shadow-sm">
                    <i class="las la-frown fs-60 text-secondary opacity-50"></i>
                    <p class="text-secondary fs-18 mt-3">{{ translate('No vendor packages found.') }}</p>
                    <p class="text-muted">{{ translate('Please contact administration to assign or create vendor packages.') }}</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Purchase Modal Placeholder --}}
    <div id="purchase_modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-700">{{ translate('Purchase Package') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-4">
                    <div class="text-center mb-4">
                        <p class="text-muted mb-1">{{ translate('You are buying') }}</p>
                        <h4 id="modal_package_name" class="fw-700 text-primary mb-2"></h4>
                        <div class="h3 fw-700 text-dark" id="modal_package_price"></div>
                    </div>
                    
                    <form action="{{ route('vendor.package.purchase') }}" method="POST">
                        @csrf
                        <input type="hidden" name="package_id" id="modal_package_id">
                        
                        <div class="form-group">
                            <label class="fs-13 fw-600 text-muted">{{ translate('Select Payment Method') }}</label>
                            <select class="form-control aiz-selectpicker" name="payment_option" required>
                                <option value="offline" data-content='<div class="d-flex align-items-center"><i class="las la-university mr-2 fs-18"></i>{{ translate("Offline Payment") }}</div>'>{{ translate('Offline Payment') }}</option>
                                {{-- Add other payment methods here like stripe, paypal etc if available --}}
                            </select>
                        </div>
                        
                        <div class="alert alert-info rounded-0 fs-12 mb-4">
                            <i class="las la-info-circle mr-1"></i>
                            {{ translate('After successfull payment, your account will be activated by our administration team.') }}
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-light rounded-0" data-dismiss="modal">{{ translate('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary rounded-0 px-4">{{ translate('Confirm Purchase') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function showPurchaseModal(id, name, price) {
            $('#modal_package_id').val(id);
            $('#modal_package_name').text(name);
            $('#modal_package_price').text(price);
            $('#purchase_modal').modal('show');
        }

        $(document).ready(function(){
            $('.package-card').hover(
                function(){ 
                    $(this).css({
                        'transform':'translateY(-10px)',
                        'box-shadow':'0 15px 30px rgba(0,0,0,0.1)'
                    }); 
                },
                function(){ 
                    $(this).css({
                        'transform':'translateY(0)',
                        'box-shadow':'0 0.125rem 0.25rem rgba(0,0,0,0.075)'
                    }); 
                }
            );
        });
    </script>
@endsection
