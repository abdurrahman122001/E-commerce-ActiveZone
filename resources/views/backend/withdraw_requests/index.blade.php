@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Withdrawal Requests')}}</h1>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Withdrawal Requests')}}</h5>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                {{ $status ? ucfirst($status) : translate('All Status') }}
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-menu-item" href="{{ route('admin.withdraw_requests.index') }}">{{ translate('All') }}</a>
                <a class="dropdown-menu-item" href="{{ route('admin.withdraw_requests.index', ['status' => 'pending']) }}">{{ translate('Pending') }}</a>
                <a class="dropdown-menu-item" href="{{ route('admin.withdraw_requests.index', ['status' => 'approved']) }}">{{ translate('Approved') }}</a>
                <a class="dropdown-menu-item" href="{{ route('admin.withdraw_requests.index', ['status' => 'rejected']) }}">{{ translate('Rejected') }}</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Requester')}}</th>
                    <th>{{translate('Type')}}</th>
                    <th>{{translate('Amount')}}</th>
                    <th data-breakpoints="lg">{{translate('Status')}}</th>
                    <th data-breakpoints="lg">{{translate('Request Date')}}</th>
                    <th data-breakpoints="lg">{{translate('Processed Date')}}</th>
                    <th data-breakpoints="lg">{{translate('Message')}}</th>
                    <th data-breakpoints="lg">{{translate('Bank Details')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $key => $withdraw_request)
                    @php
                        $isDeliveryBoy = ($withdraw_request->request_source ?? null) === 'delivery_boy';

                        // Normalize status to string
                        if ($isDeliveryBoy) {
                            $statusStr = $withdraw_request->status_string; // pending/approved/rejected via accessor
                        } else {
                            $statusStr = $withdraw_request->status; // already a string
                        }

                        // Get requester name
                        if ($isDeliveryBoy) {
                            $requesterName = $withdraw_request->user->name ?? translate('Deleted User');
                            $requesterExists = (bool)$withdraw_request->user;
                        } else {
                            $requester = $withdraw_request->requester;
                            $requesterExists = (bool)$requester;
                            if ($requesterExists) {
                                if (in_array($withdraw_request->user_type, ['franchise', 'state_franchise'])) {
                                    $requesterName = $requester->franchise_name ?? $requester->name;
                                } elseif (in_array($withdraw_request->user_type, ['sub_franchise', 'employee'])) {
                                    $requesterName = $requester->name;
                                } elseif ($withdraw_request->user_type == 'vendor') {
                                    $requesterName = ($requester->user->name ?? translate('N/A')) . ' (' . $requester->shop_name . ')';
                                } else {
                                    $requesterName = $requester->name ?? translate('N/A');
                                }
                            } else {
                                $requesterName = translate('Deleted User');
                            }
                        }

                        // Bank info
                        if ($isDeliveryBoy) {
                            $db = $withdraw_request->deliveryBoy;
                            $bank_name = $db->bank_name ?? '-';
                            $bank_acc_name = $db->holder_name ?? '-';
                            $bank_acc_no = $db->bank_account_no ?? '-';
                            $bank_routing_no = $db->bank_routing_no ?? '-';
                        } else {
                            $bank_name = $withdraw_request->bank_name;
                            $bank_acc_name = $withdraw_request->bank_acc_name;
                            $bank_acc_no = $withdraw_request->bank_acc_no;
                            $bank_routing_no = $withdraw_request->bank_routing_no;
                        }
                    @endphp
                    <tr>
                        <td>{{ ($key+1) + ($requests->currentPage() - 1)*$requests->perPage() }}</td>
                        <td>
                            @if($requesterExists)
                                <div class="fw-600">{{ $requesterName }}</div>
                            @else
                                <span class="text-muted">{{ translate('Deleted User') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($isDeliveryBoy)
                                <span class="badge badge-inline badge-info">{{ translate('Delivery Boy') }}</span>
                            @else
                                {{ ucfirst(str_replace('_', ' ', $withdraw_request->user_type)) }}
                            @endif
                        </td>
                        <td>{{ single_price($withdraw_request->amount) }}</td>
                        <td>
                            @if($statusStr == 'pending')
                                <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                            @elseif($statusStr == 'approved')
                                <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                            @endif
                        </td>
                        <td>{{ $withdraw_request->created_at->format('d-m-Y H:i A') }}</td>
                        <td>
                            @if($statusStr != 'pending')
                                {{ $withdraw_request->updated_at->format('d-m-Y H:i A') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $withdraw_request->message }}</td>
                        <td>
                            <div class="small">
                                <b>{{ translate('Bank') }}:</b> {{ $bank_name }}<br>
                                <b>{{ translate('Acc') }}:</b> {{ $bank_acc_name }}<br>
                                <b>{{ translate('No') }}:</b> {{ $bank_acc_no }}<br>
                                <b>{{ translate('Routing') }}:</b> {{ $bank_routing_no }}
                            </div>
                        </td>
                        <td class="text-right">
                            @if($statusStr == 'pending')
                                @if($isDeliveryBoy)
                                    <a href="javascript:void(0)" onclick="approve_modal_db('{{ $withdraw_request->id }}')" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Approve') }}">
                                        <i class="las la-check"></i>
                                    </a>
                                    <a href="javascript:void(0)" onclick="reject_modal_db('{{ $withdraw_request->id }}')" class="btn btn-soft-danger btn-icon btn-circle btn-sm" title="{{ translate('Reject') }}">
                                        <i class="las la-times"></i>
                                    </a>
                                @else
                                    <a href="javascript:void(0)" onclick="approve_modal('{{ $withdraw_request->id }}')" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Approve') }}">
                                        <i class="las la-check"></i>
                                    </a>
                                    <a href="javascript:void(0)" onclick="reject_modal('{{ $withdraw_request->id }}')" class="btn btn-soft-danger btn-icon btn-circle btn-sm" title="{{ translate('Reject') }}">
                                        <i class="las la-times"></i>
                                    </a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $requests->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    {{-- Commission / Franchise / Vendor reject modal --}}
    <div class="modal fade" id="reject-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Reject Withdrawal Request')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <form id="reject-form" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{translate('Reason for Rejection')}}</label>
                            <textarea name="admin_note" rows="3" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('Reject')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="approve-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Approve Withdrawal Request')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <form id="approve-form" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{translate('Admin Remarks/Note')}}</label>
                            <textarea name="admin_note" rows="3" class="form-control" placeholder="{{translate('Enter remarks (optional)')}}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('Approve')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delivery Boy reject modal --}}
    <div class="modal fade" id="reject-modal-db">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Reject Delivery Boy Withdrawal')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <form id="reject-form-db" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{translate('Reason for Rejection')}}</label>
                            <textarea name="admin_note" rows="3" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('Reject')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="approve-modal-db">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Approve Delivery Boy Withdrawal')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <form id="approve-form-db" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>{{ translate('Are you sure you want to approve this withdrawal request? The amount will be deducted from the delivery boy\'s earnings.') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('Approve')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        // Commission / Franchise / Vendor modals
        function approve_modal(id) {
            $('#approve-form').attr('action', '{{ url('admin/withdraw-requests/approve') }}/'+id);
            $('#approve-modal').modal('show');
        }

        function reject_modal(id) {
            $('#reject-form').attr('action', '{{ url('admin/withdraw-requests/reject') }}/'+id);
            $('#reject-modal').modal('show');
        }

        // Delivery Boy modals
        function approve_modal_db(id) {
            $('#approve-form-db').attr('action', '{{ url('admin/withdraw-requests/delivery-boy/approve') }}/'+id);
            $('#approve-modal-db').modal('show');
        }

        function reject_modal_db(id) {
            $('#reject-form-db').attr('action', '{{ url('admin/withdraw-requests/delivery-boy/reject') }}/'+id);
            $('#reject-modal-db').modal('show');
        }
    </script>
@endsection
