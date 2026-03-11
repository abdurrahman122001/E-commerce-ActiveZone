@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Franchise Registrations')}}</h1>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Filters')}}</h5>
    </div>
    <div class="card-body">
        <form action="" method="GET">
            <div class="row gutters-5">
                <div class="col-md-3">
                    <select class="form-control aiz-selectpicker" name="type">
                        <option value="">{{ translate('All Types') }}</option>
                        <option value="state" @if($type == 'state') selected @endif>{{ translate('State Franchise') }}</option>
                        <option value="city" @if($type == 'city') selected @endif>{{ translate('City Franchise') }}</option>
                        <option value="sub" @if($type == 'sub') selected @endif>{{ translate('Sub-Franchise') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control aiz-selectpicker" name="status">
                        <option value="">{{ translate('All Status') }}</option>
                        <option value="pending" @if($status == 'pending') selected @endif>{{ translate('Pending') }}</option>
                        <option value="approved" @if($status == 'approved') selected @endif>{{ translate('Approved') }}</option>
                        <option value="rejected" @if($status == 'rejected') selected @endif>{{ translate('Rejected') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="{{ translate('Search by name or email') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                    <a href="{{ route('admin.all_franchises.registrations') }}" class="btn btn-outline-secondary">{{ translate('Reset') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Franchise Info')}}</th>
                    <th>{{translate('Type')}}</th>
                    <th>{{translate('Location')}}</th>
                    <th>{{translate('Package')}}</th>
                    <th>{{translate('Registered By / Hierarchy')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th>{{translate('Payment')}}</th>
                    <th>{{translate('Registration Date')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $key => $reg)
                    <tr>
                        <td>{{ ($key + 1) + ($registrations->currentPage() - 1)*$registrations->perPage() }}</td>
                        <td>
                            <b>{{ $reg->franchise_name ?? $reg->user->name ?? '' }}</b><br>
                            <small class="text-muted">{{ $reg->user->name ?? '' }}</small><br>
                            <small class="text-muted">{{ $reg->user->email ?? '' }}</small><br>
                            <small class="text-muted">{{ $reg->user->phone ?? '' }}</small>
                        </td>
                        <td>
                            @if($reg->franchise_type == 'State Franchise')
                                <span class="badge badge-inline badge-primary">{{ translate('State') }}</span>
                            @elseif($reg->franchise_type == 'City Franchise')
                                <span class="badge badge-inline badge-info">{{ translate('City') }}</span>
                            @else
                                <span class="badge badge-inline badge-secondary">{{ translate('Sub') }}</span>
                            @endif
                        </td>
                        <td>
                            <small>
                                <b>{{ translate('State') }}:</b> {{ $reg->state->name ?? translate('N/A') }}<br>
                                @if($reg->franchise_type != 'State Franchise')
                                    <b>{{ translate('City') }}:</b> {{ $reg->city->name ?? translate('N/A') }}<br>
                                @endif
                                @if($reg->franchise_type == 'Sub-Franchise')
                                    <b>{{ translate('Area') }}:</b> {{ $reg->area->name ?? translate('N/A') }}
                                @endif
                            </small>
                        </td>
                        <td>
                            @if($reg->franchise_package)
                                {{ $reg->franchise_package->getTranslation('name') }}
                                <br><b>{{ single_price($reg->franchise_package->price) }}</b>
                            @else
                                <span class="text-muted">{{ translate('No Package') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($reg->franchise_type == 'Sub-Franchise')
                                <small>
                                    <b>{{ translate('City') }}:</b> {{ $reg->franchise->franchise_name ?? translate('None') }}<br>
                                    <b>{{ translate('State') }}:</b> {{ $reg->state_franchise->franchise_name ?? translate('None') }}
                                </small>
                            @elseif($reg->franchise_type == 'City Franchise')
                                <small>
                                    <b>{{ translate('State') }}:</b> {{ $reg->state_franchise->franchise_name ?? translate('None') }}
                                </small>
                            @else
                                <span class="badge badge-inline badge-dark">{{ translate('Platform/Admin') }}</span>
                            @endif

                            @if($reg->user && $reg->user->referred_by_user)
                                <div class="mt-1">
                                    <small class="text-success">
                                        <b>{{ translate('Referred By') }}:</b> {{ $reg->user->referred_by_user->name }}
                                    </small>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if ($reg->status == 'pending')
                                <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                            @elseif ($reg->status == 'approved')
                                <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                            @endif
                            @if(!empty($reg->additional_doc_request) && $reg->additional_doc_request)
                                <br><span class="badge badge-inline badge-info mt-1" title="{{ $reg->additional_doc_request_note }}"><i class="la la-file-upload"></i> {{translate('Docs Requested')}}</span>
                            @endif
                        </td>
                        <td>
                            @if($reg->package_payment_status == 'paid')
                                <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                            @elseif($reg->package_payment_status == 'pending')
                                <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                                @if($reg->offline_package_payment_proof)
                                    <a href="{{ asset('storage/'.$reg->offline_package_payment_proof) }}" target="_blank" class="btn btn-xs btn-outline-info ml-1">{{ translate('Proof') }}</a>
                                @endif
                            @else
                                <span class="badge badge-inline badge-danger">{{translate('Unpaid')}}</span>
                            @endif
                        </td>
                        <td>{{ $reg->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="text-right">
                             <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown">
                                    <i class="las la-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @php
                                        $type_param = match($reg->franchise_type) {
                                            'State Franchise' => 'state',
                                            'City Franchise' => 'franchise',
                                            'Sub-Franchise' => 'sub_franchise',
                                            default => 'franchise'
                                        };
                                        $edit_route = match($reg->franchise_type) {
                                            'State Franchise' => route('admin.state_franchises.edit', $reg->id),
                                            'City Franchise' => route('admin.franchises.edit', $reg->id),
                                            'Sub-Franchise' => route('admin.sub_franchises.edit', $reg->id),
                                            default => '#'
                                        };
                                        $destroy_route = match($reg->franchise_type) {
                                            'State Franchise' => route('admin.state_franchises.destroy', $reg->id),
                                            'City Franchise' => route('admin.franchises.destroy', $reg->id),
                                            'Sub-Franchise' => route('admin.sub_franchises.destroy', $reg->id),
                                            default => '#'
                                        };
                                    @endphp

                                    @if($reg->status == 'pending')
                                        <a href="{{ route('admin.franchise.approve', ['id'=>$reg->id, 'type'=>$type_param]) }}" class="dropdown-item">
                                            {{translate('Approve')}}
                                        </a>
                                        <a href="{{ route('admin.franchise.reject', ['id'=>$reg->id, 'type'=>$type_param]) }}" class="dropdown-item">
                                            {{translate('Reject')}}
                                        </a>
                                    @endif

                                    @if($reg->package_payment_status == 'pending')
                                        <a href="{{ route('admin.franchise.payment_approve', ['id'=>$reg->id, 'type'=>$type_param]) }}" class="dropdown-item">
                                            {{translate('Approve Payment')}}
                                        </a>
                                    @endif

                                    <a href="{{ $edit_route }}" class="dropdown-item">
                                        {{translate('Edit')}}
                                    </a>
                                    <a href="javascript:void(0);" class="dropdown-item confirm-delete" data-href="{{ $destroy_route }}" >
                                        {{translate('Delete')}}
                                    </a>
                                    <a href="javascript:void(0);" class="dropdown-item text-warning"
                                       data-toggle="modal" data-target="#requestDocModal_{{ $type_param }}_{{ $reg->id }}">
                                        <i class="la la-file-upload"></i> {{translate('Request Docs')}}
                                    </a>
                                </div>
                            </div>

                            {{-- Request Additional Docs Modal for this franchise --}}
                            <div class="modal fade" id="requestDocModal_{{ $type_param }}_{{ $reg->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.franchise.request_additional_docs', ['id' => $reg->id, 'type' => $type_param]) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ translate('Request Additional Documents') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-muted">{{ translate('Specify what additional documents you need.') }}</p>
                                                @if($reg->additional_doc_request_note)
                                                    <div class="alert alert-info">
                                                        <strong>{{ translate('Previous request') }}:</strong> {{ $reg->additional_doc_request_note }}
                                                    </div>
                                                @endif
                                                <div class="form-group">
                                                    <label>{{ translate('Document Request Note') }} <span class="text-danger">*</span></label>
                                                    <textarea name="doc_request_note" class="form-control" rows="4" required
                                                        placeholder="{{ translate('e.g. Please submit a clearer copy of your ID proof and PAN card.') }}">{{ $reg->additional_doc_request_note }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                                                <button type="submit" class="btn btn-warning">{{ translate('Send Request') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $registrations->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
