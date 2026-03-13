@extends('delivery_boy.layouts.app')

@section('content')

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 h6">
            {{ translate('Directions to') }} 
            @if($type == 'vendor')
                {{ translate('Vendor') }}
            @elseif($type == 'shop')
                {{ translate('Shop') }}
            @elseif($type == 'customer')
                {{ translate('Customer') }}
            @endif
        </h5>
        <a href="{{ route('delivery-boy.order-detail', $order->id) }}" class="btn btn-sm btn-primary">
            <i class="las la-arrow-left"></i> {{ translate('Back to Order') }}
        </a>
    </div>
    <div class="card-body">
        @if(get_setting('google_map') == 1)
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0 position-relative">
                            <div class="p-3 text-center bg-white border-bottom">
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($destination) }}&origin={{ urlencode($origin) }}&travelmode=driving" target="_blank" class="btn btn-success btn-block rounded-pill fw-700 shadow-sm start-ride-btn">
                                    <i class="las la-location-arrow fs-20 mr-1"></i> {{ translate('Start Ride / Navigation') }}
                                </a>
                                <small class="text-muted mt-2 d-block">{{ translate('This will open your native map application to begin turn-by-turn navigation.') }}</small>
                            </div>
                            @php
                                $mapParams = 'key=' . get_setting('google_map_key') . '&destination=' . urlencode($destination);
                                if($origin) {
                                    $mapParams .= '&origin=' . urlencode($origin);
                                }
                            @endphp
                            <iframe width="100%" height="500" frameborder="0" style="border:0;" 
                                src="https://www.google.com/maps/embed/v1/directions?{{ $mapParams }}" 
                                allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-danger">
                {{ translate('Google Maps is disabled or not configured in system settings.') }}
            </div>
            <div class="text-center mt-3">
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($destination) }}&origin={{ urlencode($origin) }}" target="_blank" class="btn btn-primary">
                    <i class="las la-external-link-square-alt"></i> {{ translate('Open map in new tab anyway') }}
                </a>
            </div>
        @endif
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var long = position.coords.longitude;
                var originParam = lat + ',' + long;
                
                // Update the Start Ride button with exact real-time location if not already properly set
                var btn = $('.start-ride-btn');
                if(btn.length > 0) {
                    var href = btn.attr('href');
                    if(href.indexOf('origin=') !== -1 && href.indexOf('origin=&') === -1) {
                         // Origin is present and not empty, but we might want to update to latest location anyway.
                         // But for now, just append if it's empty
                    } else {
                         // Replace empty origin or add it
                         href = href.replace('origin=', 'origin=' + originParam);
                         btn.attr('href', href);
                    }
                }
            });
        }
    });
</script>
@endsection
