@extends(Auth::guard('franchise_employee')->check() ? 'franchise.layouts.app' : (Auth::user()->user_type == 'vendor' ? 'vendor.layouts.app' : 'franchise.layouts.app'))

@section('panel_content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-none bg-primary text-white">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="las la-wallet la-3x"></i>
                    </div>
                    <div class="h4 fw-700 m-0">{{ single_price($balance) }}</div>
                    <div class="opacity-60">{{ translate('Total Balance') }}</div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Send Withdrawal Request') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ Auth::guard('franchise_employee')->check() ? route('franchise.employee.withdraw_requests.store') : (Auth::user()->user_type == 'vendor' ? route('vendors.withdraw_requests.store') : route('franchise.withdraw_requests.store')) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>{{ translate('Amount') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount" min="1" step="0.01" max="{{ $balance }}" placeholder="{{ translate('Enter Amount') }}" required>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Message') }}</label>
                            <textarea class="form-control" name="message" rows="3" placeholder="{{ translate('Any notes for admin') }}"></textarea>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Submit Request') }}</button>
                        </div>
                    </form>
                    <div class="mt-3 text-muted small">
                        <strong>{{ translate('Note:') }}</strong> {{ translate('After requesting a withdrawal, the admin will review your request and bank details. Upon approval, your earning balance will be set to zero and your commission records will be marked as paid.') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Withdrawal History') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Date') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th data-breakpoints="lg">{{ translate('Message') }}</th>
                                <th data-breakpoints="lg">{{ translate('Admin Note') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $key => $withdraw_request)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $withdraw_request->created_at->format('d-m-Y H:i A') }}</td>
                                    <td>{{ single_price($withdraw_request->amount) }}</td>
                                    <td>
                                        @if ($withdraw_request->status == 'pending')
                                            <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
                                        @elseif($withdraw_request->status == 'approved')
                                            <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
                                        @else
                                            <span class="badge badge-inline badge-danger">{{ translate('Rejected') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $withdraw_request->message }}</td>
                                    <td>{{ $withdraw_request->admin_note }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Your Banking Details') }}</h5>
                </div>
                <div class="card-body">
                    @php
                        $user = Auth::guard('franchise_employee')->check() ? Auth::guard('franchise_employee')->user() : Auth::user();
                        $data = null;
                        if(Auth::guard('franchise_employee')->check()){
                            $data = $user;
                        } else {
                            if($user->user_type == 'franchise') $data = $user->franchise;
                            elseif($user->user_type == 'sub_franchise') $data = $user->sub_franchise;
                            elseif($user->user_type == 'vendor') $data = \App\Models\Vendor::where('user_id', $user->id)->first();
                        }
                    @endphp
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="opacity-60">{{ translate('Bank Name') }}</label>
                            <div class="fw-600 text-primary">{{ $data->bank_name ?? translate('Not Set') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="opacity-60">{{ translate('Account Name') }}</label>
                            <div class="fw-600 text-primary">{{ $data->bank_acc_name ?? translate('Not Set') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="opacity-60">{{ translate('Account Number') }}</label>
                            <div class="fw-600 text-primary">{{ $data->bank_acc_no ?? translate('Not Set') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="opacity-60">{{ translate('Routing Number') }}</label>
                            <div class="fw-600 text-primary">{{ $data->bank_routing_no ?? translate('Not Set') }}</div>
                        </div>
                    </div>
                    <div class="alert alert-info py-2">
                        {{ translate('To update your banking details, please go to your profile settings.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
