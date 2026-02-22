@extends($layout)

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Vendors') }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ $create_route }}" class="btn btn-primary">
                <span>{{ translate('Add New Vendor') }}</span>
            </a>
        </div>
      </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('All Vendors') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('Email') }}</th>
                        <th data-breakpoints="lg">{{ translate('Total Orders') }}</th>
                        <th data-breakpoints="lg">{{ translate('Total Sales') }}</th>
                        <th>{{ translate('Commission (%)') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th>{{ translate('Registered By') }}</th>
                        <th width="10%">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $key => $vendor)
                        @php
                            $shop = $vendor->user->shop ?? null;
                        @endphp
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $vendor->user->name ?? '' }}</td>
                            <td>{{ $vendor->user->email ?? '' }}</td>
                            <td>{{ $vendor->orders_count }}</td>
                            <td>{{ single_price($vendor->orders_sum_grand_total) }}</td>
                            <td>{{ $vendor->commission_percentage }}</td>
                            <td>
                                @if($vendor->status == 'approved')
                                    <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
                                @elseif($vendor->status == 'pending')
                                    <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ translate('Rejected') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($vendor->addedByEmployee)
                                    {{ $vendor->addedByEmployee->name }}
                                @else
                                    <span class="badge badge-inline badge-secondary">{{ translate('Direct') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($shop)
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                        <i class="las la-ellipsis-v seller-list-icon"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                        
                                        <a href="{{route('sellers.profile', encrypt($shop->id))}}" class="dropdown-item">
                                            {{translate('Profile')}}
                                        </a>
         
                                        <a href="{{route('sellers.login', encrypt($shop->id))}}" class="dropdown-item">
                                            {{translate('Log in as this Seller')}}
                                        </a>
            
                                        <a href="javascript:void();" onclick="show_seller_payment_modal('{{$shop->id}}');" class="dropdown-item">
                                            {{translate('Go to Payment')}}
                                        </a>
        
                                        <a href="{{route('sellers.payment_history', encrypt($shop->user_id))}}" class="dropdown-item">
                                            {{translate('Payment History')}}
                                        </a>

                                        <a href="{{route($edit_route_prefix . '.edit', encrypt($vendor->id))}}" class="dropdown-item">
                                            {{translate('Edit Vendor')}}
                                        </a>
                                        
                                        <a href="{{route('sellers.edit', encrypt($shop->id))}}" class="dropdown-item">
                                            {{translate('Edit')}}
                                        </a>

                                            @if($shop->user->banned != 1)
                                                <a href="javascript:void();" onclick="confirm_ban('{{route('sellers.ban', $shop->id)}}');" class="dropdown-item">
                                                    {{translate('Ban this seller')}}
                                                    <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                                                </a>
                                            @else
                                                <a href="javascript:void();" onclick="confirm_unban('{{route('sellers.ban', $shop->id)}}');" class="dropdown-item">
                                                    {{translate('Unban this seller')}}
                                                    <i class="fa fa-check text-success" aria-hidden="true"></i>
                                                </a>
                                            @endif

                                            @if($shop->user->is_suspicious == 1)
                                                <a href="javascript:void();" onclick="confirm_suspicious('{{route('seller.suspicious', encrypt($shop->user->id))}}', true);" class="dropdown-item">
                                                        {{ translate(" Mark as " . ($shop->user->is_suspicious == 1 ? 'unsuspect' : 'suspicious') . " ") }}
                                                </a>
                                            @else
                                                <a href="javascript:void();" onclick="confirm_suspicious('{{route('seller.suspicious', encrypt($shop->user->id))}}', false);" class="dropdown-item">
                                                        {{ translate(" Mark as " . ($shop->user->is_suspicious == 1 ? 'unsuspect' : 'suspicious') . " ") }}
                                                </a>
                                            @endif

                                            <a href="javascript:void();" class="dropdown-item confirm-delete" data-href="{{route('sellers.destroy', $shop->id)}}" >
                                                {{translate('Delete')}}
                                            </a>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modal')
	<!-- Seller Payment Modal -->
	<div class="modal fade" id="payment_modal">
	    <div class="modal-dialog modal-dialog-centered">
	        <div class="modal-content" id="payment-modal-content">

	        </div>
	    </div>
	</div>

	<!-- Reusable Confirmation Modal -->
    <div class="modal fade" id="universal-confirm-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6" id="universal-modal-title">{{ translate('Confirmation') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="universal-modal-message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <a class="btn btn-primary" id="universal-confirm-button">{{ translate('Proceed!') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function show_seller_payment_modal(id){
            $.post('{{ route('sellers.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#payment_modal #payment-modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }

        // Ban
        function confirm_ban(url) {
            showConfirmationModal({
                url: url,
                message: '{{ translate("Do you really want to ban this seller?") }}'
            });
        }

        // Unban
        function confirm_unban(url) {
            showConfirmationModal({
                url: url,
                message: '{{ translate("Do you really want to unban this seller?") }}'
            });
        }

        function showConfirmationModal({ url, message }) {
            if ('{{ env('DEMO_MODE') }}' === 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            // Set dynamic content
            document.getElementById('universal-modal-message').innerText = message;
            document.getElementById('universal-confirm-button').setAttribute('href', url);

            // Show the modal
            $('#universal-confirm-modal').modal('show', { backdrop: 'static' });
        }

        // Suspicious / Unsuspicious
        function confirm_suspicious(url, isSuspicious) {
            const action = isSuspicious ? 'unsuspect' : 'suspect';
            showConfirmationModal({
                url: url,
                message: '{{ translate("Do you really want to") }} ' + action + ' {{ translate("this seller?") }}'
            });
        }
    </script>
@endsection
