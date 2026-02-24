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
                    <th>{{translate('Package')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th>{{translate('ID Proof')}}</th>
                    <th>{{translate('Commission (%)')}}</th>
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
                        <td>{{ $franchise->commission_percentage }}</td>
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
                                        <a href="{{ route('admin.franchise.reject', ['id'=>$franchise->id, 'type'=>'state_franchise']) }}" class="dropdown-item">
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
    </script>
@endsection
