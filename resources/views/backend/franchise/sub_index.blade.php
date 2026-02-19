@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Sub Franchises')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('admin.sub_franchises.create') }}" class="btn btn-primary">
                <span>{{translate('Add New Sub-Franchise')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-0 h6">{{translate('Sub-Franchises')}}</h5>
        </div>
    </div>
     <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Email')}}</th>
                    <th>{{translate('State')}}</th>
                    <th>{{translate('City')}}</th>
                    <th>{{translate('Area')}}</th>
                    <th>{{translate('Parent Franchise')}}</th>
                    <th>{{translate('Package')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th>{{translate('ID Proof')}}</th>
                    <th>{{translate('PAN Number')}}</th>
                    <th>{{translate('Commission (%)')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subFranchises as $key => $sub)
                    <tr>
                        <td>{{ ($subFranchises->currentPage()-1) * $subFranchises->perPage() + $key + 1 }}</td>
                        <td>{{ $sub->user->name ?? '' }}</td>
                        <td>{{ $sub->user->email ?? '' }}</td>
                        <td>{{ $sub->state->name ?? '' }}</td>
                        <td>{{ $sub->city->name ?? '' }}</td>
                        <td>{{ $sub->area->name ?? '' }}</td>
                        <td>{{ $sub->franchise->franchise_name ?? translate('No Parent') }}</td>
                        <td>{{ $sub->franchise_package ? $sub->franchise_package->getTranslation('name') : '' }}</td>
                        <td>
                            @if ($sub->status == 'pending')
                                <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                            @elseif ($sub->status == 'approved')
                                <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                            @endif
                        </td>
                        <td>
                            @if($sub->id_proof)
                                <a href="{{ asset('storage/'.$sub->id_proof) }}" target="_blank" class="btn btn-sm btn-info">{{ translate('View') }}</a>
                            @endif
                        </td>
                        <td>{{ $sub->pan_number }}</td>
                        <td>{{ $sub->commission_percentage }}</td>
                        <td class="text-right">
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="las la-ellipsis-v seller-list-icon"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                    
                                    @if($sub->status == 'pending')
                                        <a href="{{ route('admin.franchise.approve', ['id'=>$sub->id, 'type'=>'sub_franchise']) }}" class="dropdown-item">
                                            {{translate('Approve')}}
                                        </a>
                                        <a href="{{ route('admin.franchise.reject', ['id'=>$sub->id, 'type'=>'sub_franchise']) }}" class="dropdown-item">
                                            {{translate('Reject')}}
                                        </a>
                                    @endif

                                    <a href="{{route('admin.franchises.profile', encrypt($sub->user_id))}}" class="dropdown-item">
                                        {{translate('Profile')}}
                                    </a>
        
                                    <a href="{{route('admin.franchises.login', encrypt($sub->user_id))}}" class="dropdown-item">
                                        {{translate('Log in as this Franchise')}}
                                    </a>
        
                                    <a href="javascript:void();" onclick="show_seller_payment_modal('{{$sub->user_id}}');" class="dropdown-item">
                                        {{translate('Go to Payment')}}
                                    </a>

                                    <a href="{{route('admin.franchises.payment_history', encrypt($sub->user_id))}}" class="dropdown-item">
                                        {{translate('Payment History')}}
                                    </a>

                                    <a href="javascript:void(0);" onclick="show_commission_modal('{{$sub->id}}', '{{$sub->commission_percentage}}');" class="dropdown-item">
                                        {{translate('Set Commission')}}
                                    </a>

                                    <a href="{{route('admin.sub_franchises.edit', $sub->id)}}" class="dropdown-item">
                                        {{translate('Edit')}}
                                    </a>

                                    @if($sub->user && $sub->user->banned != 1)
                                        <a href="javascript:void();" onclick="confirm_ban('{{route('admin.franchises.ban', $sub->user_id)}}');" class="dropdown-item">
                                            {{translate('Ban this Franchise')}}
                                            <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                                        </a>
                                    @elseif($sub->user)
                                        <a href="javascript:void();" onclick="confirm_unban('{{route('admin.franchises.ban', $sub->user_id)}}');" class="dropdown-item">
                                            {{translate('Unban this Franchise')}}
                                            <i class="fa fa-check text-success" aria-hidden="true"></i>
                                        </a>
                                    @endif

                                    @if($sub->user && $sub->user->is_suspicious == 1)
                                        <a href="javascript:void();" onclick="confirm_suspicious('{{route('admin.franchises.suspicious', encrypt($sub->user->id))}}', true);" class="dropdown-item">
                                                {{ translate(" Mark as " . ($sub->user->is_suspicious == 1 ? 'unsuspect' : 'suspicious') . " ") }}
                                        </a>
                                    @elseif($sub->user)
                                        <a href="javascript:void();" onclick="confirm_suspicious('{{route('admin.franchises.suspicious', encrypt($sub->user->id))}}', false);" class="dropdown-item">
                                                {{ translate(" Mark as " . ($sub->user->is_suspicious == 1 ? 'unsuspect' : 'suspicious') . " ") }}
                                        </a>
                                    @endif

                                    <a href="javascript:void();" class="dropdown-item confirm-delete" data-href="{{route('admin.sub_franchises.destroy', $sub->id)}}" >
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
            {{ $subFranchises->links() }}
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
    <div class="modal fade" id="commission_modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.sub_franchises.set_commission') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="sub_franchise_id">
                    <div class="modal-header">
                        <h5 class="modal-title h6">{{ translate('Set Sub-Franchise Commission') }}</h5>
                        <button type="button" class="close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="commission_percentage">{{ translate('Commission Percentage') }}</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" name="commission_percentage" id="commission_percentage_input" class="form-control" placeholder="0" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
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

        function show_commission_modal(id, commission){
            $('#sub_franchise_id').val(id);
            $('#commission_percentage_input').val(commission);
            $('#commission_modal').modal('show');
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
