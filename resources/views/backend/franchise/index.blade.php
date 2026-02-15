@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All City Franchises')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('admin.franchises.create') }}" class="btn btn-primary">
                <span>{{translate('Add New Franchise')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-0 h6">{{translate('Franchises')}}</h5>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Email')}}</th>
                    <th>{{translate('Phone')}}</th>
                    <th>{{translate('State')}}</th>
                    <th>{{translate('City')}}</th>
                    <th>{{translate('Package')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th>{{translate('ID Proof')}}</th>
                    <th>{{translate('PAN Number')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($franchises as $key => $franchise)
                    <tr>
                        <td>{{ ($franchises->currentPage()-1) * $franchises->perPage() + $key + 1 }}</td>
                        <td>{{ $franchise->user->name ?? '' }}</td>
                        <td>{{ $franchise->user->email ?? '' }}</td>
                        <td>{{ $franchise->user->phone ?? '' }}</td>
                        <td>{{ $franchise->state->name ?? '' }}</td>
                        <td>{{ $franchise->city->name ?? '' }}</td>
                        <td>{{ $franchise->franchise_package ? $franchise->franchise_package->getTranslation('name') : '' }}</td>
                        <td>
                            @if ($franchise->status == 'pending')
                                <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                            @elseif ($franchise->status == 'approved')
                                <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                            @endif
                        </td>
                        <td>
                            @if($franchise->id_proof)
                                <a href="{{ asset('storage/'.$franchise->id_proof) }}" target="_blank" class="btn btn-sm btn-info">{{ translate('View') }}</a>
                            @endif
                        </td>
                        <td>{{ $franchise->pan_number }}</td>
                        <td class="text-right">
                             <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="las la-ellipsis-v seller-list-icon"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                    
                                    @if($franchise->status == 'pending')
                                        <a href="{{ route('admin.franchise.approve', ['id'=>$franchise->id, 'type'=>'franchise']) }}" class="dropdown-item">
                                            {{translate('Approve')}}
                                        </a>
                                        <a href="{{ route('admin.franchise.reject', ['id'=>$franchise->id, 'type'=>'franchise']) }}" class="dropdown-item">
                                            {{translate('Reject')}}
                                        </a>
                                    @endif

                                    <a href="{{route('admin.franchises.profile', encrypt($franchise->user_id))}}" class="dropdown-item">
                                        {{translate('Profile')}}
                                    </a>
        
                                    <a href="{{route('admin.franchises.login', encrypt($franchise->user_id))}}" class="dropdown-item">
                                        {{translate('Log in as this Franchise')}}
                                    </a>
        
                                    <a href="javascript:void();" onclick="show_seller_payment_modal('{{$franchise->user_id}}');" class="dropdown-item">
                                        {{translate('Go to Payment')}}
                                    </a>

                                    <a href="{{route('admin.franchises.payment_history', encrypt($franchise->user_id))}}" class="dropdown-item">
                                        {{translate('Payment History')}}
                                    </a>

                                    <a href="{{route('admin.franchises.edit', $franchise->id)}}" class="dropdown-item">
                                        {{translate('Edit')}}
                                    </a>

                                    @if($franchise->user && $franchise->user->banned != 1)
                                        <a href="javascript:void();" onclick="confirm_ban('{{route('admin.franchises.ban', $franchise->user_id)}}');" class="dropdown-item">
                                            {{translate('Ban this Franchise')}}
                                            <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                                        </a>
                                    @elseif($franchise->user)
                                        <a href="javascript:void();" onclick="confirm_unban('{{route('admin.franchises.ban', $franchise->user_id)}}');" class="dropdown-item">
                                            {{translate('Unban this Franchise')}}
                                            <i class="fa fa-check text-success" aria-hidden="true"></i>
                                        </a>
                                    @endif

                                    @if($franchise->user && $franchise->user->is_suspicious == 1)
                                        <a href="javascript:void();" onclick="confirm_suspicious('{{route('admin.franchises.suspicious', encrypt($franchise->user->id))}}', true);" class="dropdown-item">
                                                {{ translate(" Mark as " . ($franchise->user->is_suspicious == 1 ? 'unsuspect' : 'suspicious') . " ") }}
                                        </a>
                                    @elseif($franchise->user)
                                        <a href="javascript:void();" onclick="confirm_suspicious('{{route('admin.franchises.suspicious', encrypt($franchise->user->id))}}', false);" class="dropdown-item">
                                                {{ translate(" Mark as " . ($franchise->user->is_suspicious == 1 ? 'unsuspect' : 'suspicious') . " ") }}
                                        </a>
                                    @endif

                                    <a href="javascript:void();" class="dropdown-item confirm-delete" data-href="{{route('admin.franchises.destroy', $franchise->id)}}" >
                                        {{translate('Delete')}}
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $franchises->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
	<!-- Payment Modal -->
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
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function show_seller_payment_modal(id){
            $.post('{{ route('admin.franchises.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#payment_modal #payment-modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }

        // Ban
        function confirm_ban(url) {
            showConfirmationModal({
                url: url,
                message: '{{ translate("Do you really want to ban this Franchise?") }}'
            });
        }

        // Unban
        function confirm_unban(url) {
            showConfirmationModal({
                url: url,
                message: '{{ translate("Do you really want to unban this Franchise?") }}'
            });
        }

        function showConfirmationModal({ url, message }) {
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
                message: '{{ translate("Do you really want to") }} ' + action + ' {{ translate("this Franchise?") }}'
            });
        }
    </script>
@endsection
