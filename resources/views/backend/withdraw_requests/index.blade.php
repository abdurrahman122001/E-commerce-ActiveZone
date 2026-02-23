@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Commission Withdraw Requests')}}</h1>
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
                    <th>{{translate('Status')}}</th>
                    <th data-breakpoints="lg">{{translate('Message')}}</th>
                    <th data-breakpoints="lg">{{translate('Bank Details')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $key => $withdraw_request)
                    <tr>
                        <td>{{ ($key+1) + ($requests->currentPage() - 1)*$requests->perPage() }}</td>
                        <td>
                            @php $requester = $withdraw_request->requester; @endphp
                            @if($requester)
                                <div class="d-flex align-items-center">
                                    <div class="ml-2">
                                        <div class="fw-600">
                                            @if($withdraw_request->user_type == 'franchise')
                                                {{ $requester->franchise_name }}
                                            @elseif($withdraw_request->user_type == 'sub_franchise' || $withdraw_request->user_type == 'employee')
                                                {{ $requester->name }}
                                            @elseif($withdraw_request->user_type == 'vendor')
                                                {{ $requester->user->name ?? translate('N/A') }} ({{ $requester->shop_name }})
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">{{ translate('Deleted User') }}</span>
                            @endif
                        </td>
                        <td>{{ ucfirst(str_replace('_', ' ', $withdraw_request->user_type)) }}</td>
                        <td>{{ single_price($withdraw_request->amount) }}</td>
                        <td>
                            @if($withdraw_request->status == 'pending')
                                <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                            @elseif($withdraw_request->status == 'approved')
                                <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                            @endif
                        </td>
                        <td>{{ $withdraw_request->message }}</td>
                        <td>
                            <div class="small">
                                <b>{{ translate('Bank') }}:</b> {{ $withdraw_request->bank_name }}<br>
                                <b>{{ translate('Acc') }}:</b> {{ $withdraw_request->bank_acc_name }}<br>
                                <b>{{ translate('No') }}:</b> {{ $withdraw_request->bank_acc_no }}<br>
                                <b>{{ translate('Routing') }}:</b> {{ $withdraw_request->bank_routing_no }}
                            </div>
                        </td>
                        <td class="text-right">
                            @if($withdraw_request->status == 'pending')
                                <a href="javascript:void(0)" onclick="confirm_modal('{{ route('admin.withdraw_requests.approve', $withdraw_request->id) }}')" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Approve') }}">
                                    <i class="las la-check"></i>
                                </a>
                                <a href="javascript:void(0)" onclick="reject_modal('{{ $withdraw_request->id }}')" class="btn btn-soft-danger btn-icon btn-circle btn-sm" title="{{ translate('Reject') }}">
                                    <i class="las la-times"></i>
                                </a>
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
@endsection

@section('script')
    <script type="text/javascript">
        function reject_modal(id){
            $('#reject-form').attr('action', '{{ url('admin/withdraw-requests/reject') }}/'+id);
            $('#reject-modal').modal('show');
        }
    </script>
@endsection
