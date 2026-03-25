@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All State Franchises')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('admin.state_franchises.create') }}" class="btn btn-primary">
                <span>{{translate('Add New State Franchise')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-0 h6">{{translate('State Franchises')}}</h5>
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
                    <th>{{translate('Pin Code')}}</th>
                    <th>{{translate('Address')}}</th>
                    <th>{{translate('Package')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th>{{translate('Payment')}}</th>
                    <th>{{translate('ID Proof')}}</th>
                    <th>{{translate('Commission')}}</th>
                    <th>{{translate('Registration Date')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stateFranchises as $key => $franchise)
                    <tr>
                        <td>{{ ($stateFranchises->currentPage()-1) * $stateFranchises->perPage() + $key + 1 }}</td>
                        <td>{{ $franchise->user->name ?? '' }}</td>
                        <td>{{ $franchise->user->email ?? '' }}</td>
                        <td>{{ $franchise->user->phone ?? '' }}</td>
                        <td>{{ $franchise->state->name ?? '' }}</td>
                        <td>{{ $franchise->pincode ?? '' }}</td>
                        <td>{{ $franchise->address ?? '' }}</td>
                        <td>
                            {{ $franchise->franchise_package ? $franchise->franchise_package->getTranslation('name') : '' }}
                            @if($franchise->franchise_package)
                                <br><b>{{ single_price($franchise->franchise_package->price) }}</b>
                            @endif
                        </td>
                        <td>
                            @if($franchise->user && $franchise->user->banned == 1)
                                <span class="badge badge-inline badge-danger">{{translate('Banned')}}</span>
                            @elseif ($franchise->status == 'pending')
                                <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                            @elseif ($franchise->status == 'approved')
                                <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                            @endif
                        </td>
                        <td>
                            @if($franchise->package_payment_status == 'paid')
                                <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                            @elseif($franchise->package_payment_status == 'pending')
                                <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                                @if($franchise->offline_package_payment_proof)
                                    <a href="{{ asset('storage/'.$franchise->offline_package_payment_proof) }}" target="_blank" class="btn btn-xs btn-outline-info ml-1">{{ translate('View Proof') }}</a>
                                @endif
                                <a href="{{ route('admin.franchise.payment_approve', ['id'=>$franchise->id, 'type'=>'state_franchise']) }}" class="btn btn-xs btn-outline-success ml-1" title="{{ translate('Approve Payment') }}"><i class="las la-check"></i></a>
                            @else
                                <span class="badge badge-inline badge-danger">{{translate('Unpaid')}}</span>
                            @endif
                        </td>
                        <td>
                            @if($franchise->id_proof)
                                <a href="{{ asset('storage/'.$franchise->id_proof) }}" target="_blank" class="btn btn-sm btn-info mb-1">{{ translate('Front') }}</a>
                            @endif
                            @if($franchise->id_proof_back)
                                <a href="{{ asset('storage/'.$franchise->id_proof_back) }}" target="_blank" class="btn btn-sm btn-info">{{ translate('Back') }}</a>
                            @endif
                        </td>
                        <td>
                            {{ $franchise->commission_percentage }}
                            @if(($franchise->commission_type ?? 'percentage') == 'percentage')
                                %
                            @endif
                        </td>
                        <td>{{ $franchise->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="text-right">
                             <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="las la-ellipsis-v seller-list-icon"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                    
                                    @if($franchise->status == 'pending')
                                        <a href="{{ route('admin.franchise.approve', ['id'=>$franchise->id, 'type'=>'state_franchise']) }}" class="dropdown-item">
                                            {{translate('Approve')}}
                                        </a>
                                        <a href="javascript:void(0);" data-href="{{ route('admin.franchise.reject', ['id'=>$franchise->id, 'type'=>'state_franchise']) }}" class="dropdown-item confirm-reject">
                                            {{translate('Reject')}}
                                         </a>
                                    @endif

                                    <a href="{{route('admin.franchises.profile', encrypt($franchise->user_id))}}" class="dropdown-item">
                                        {{translate('Profile')}}
                                    </a>
        
                                    <a href="{{route('admin.franchises.login', encrypt($franchise->user_id))}}" class="dropdown-item">
                                        {{translate('Log in as this Franchise')}}
                                    </a>
        
                                    <a href="{{route('admin.state_franchises.edit', $franchise->id)}}" class="dropdown-item">
                                        {{translate('Edit')}}
                                    </a>

                                    <a href="javascript:void(0);" onclick="show_commission_modal('{{$franchise->id}}', '{{$franchise->commission_percentage}}', '{{$franchise->commission_type ?? 'percentage'}}');" class="dropdown-item">
                                        {{translate('Set Commission')}}
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

                                    <a href="javascript:void();" class="dropdown-item confirm-delete" data-href="{{route('admin.state_franchises.destroy', $franchise->id)}}" >
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
            {{ $stateFranchises->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
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
                <form action="{{ route('admin.state_franchises.set_commission') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="state_franchise_id">
                    <div class="modal-header">
                        <h5 class="modal-title h6">{{ translate('Set State Franchise Commission') }}</h5>
                        <button type="button" class="close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="commission_percentage">{{ translate('Commission') }}</label>
                            <div class="row gutters-5">
                                <div class="col-sm-7">
                                    <input type="number" step="0.01" min="0" name="commission_percentage" id="commission_percentage_input" class="form-control" placeholder="0" required>
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control aiz-selectpicker" name="commission_type" id="commission_type_select">
                                        <option value="percentage">{{translate('Percentage (%)')}}</option>
                                        <option value="flat">{{translate('Flat Amount')}}</option>
                                    </select>
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
        function show_commission_modal(id, commission, type){
            $('#state_franchise_id').val(id);
            $('#commission_percentage_input').val(commission);
            $('#commission_type_select').val(type);
            $('#commission_type_select').selectpicker('refresh');
            $('#commission_modal').modal('show');
        }
    </script>
@endsection
