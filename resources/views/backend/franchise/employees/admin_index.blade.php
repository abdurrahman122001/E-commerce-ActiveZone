@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Franchise Employees')}}</h1>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Employee List')}}</h5>
        <div class="pull-right clearfix d-flex">
            <form class="mr-2" id="sort_franchises" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="input-group">
                        <select class="form-control aiz-selectpicker" name="franchise_id" id="franchise_id" onchange="this.form.submit()">
                            <option value="">{{ translate('Filter by Franchise') }}</option>
                            @foreach($franchises as $franchise)
                                <option value="{{ $franchise->id }}" @if($franchise_id == $franchise->id) selected @endif>{{ $franchise->franchise_name }}</option>
                            @endforeach
                        </select>
                        <input type="text" class="form-control" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name or email & Enter') }}">
                    </div>
                </div>
            </form>
            <a href="{{ route('admin.franchise_employees.vendor_registrations') }}" class="btn btn-info">{{ translate('Vendor Registration Report') }}</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Email')}}</th>
                    <th data-breakpoints="lg">{{translate('Phone')}}</th>
                    <th data-breakpoints="lg">{{translate('Franchise/Sub')}}</th>
                    <th data-breakpoints="lg">{{translate('Role')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th width="15%" class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $key => $employee)
                    <tr>
                        <td>{{ ($key+1) + ($employees->currentPage() - 1)*$employees->perPage() }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>
                            @if($employee->franchise)
                                {{ $employee->franchise->franchise_name }}
                            @else
                                <span class="text-muted">{{ translate('N/A') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($employee->status == 'pending')
                                <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                            @elseif($employee->status == 'approved')
                                <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                            @elseif($employee->status == 'rejected')
                                <span class="badge badge-inline badge-danger">{{translate('Rejected')}}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($employee->status == 'pending')
                                <a href="{{route('admin.franchise_employees.approve', $employee->id)}}" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Approve') }}">
                                    <i class="las la-check"></i>
                                </a>
                                <a href="{{route('admin.franchise_employees.reject', $employee->id)}}" class="btn btn-soft-danger btn-icon btn-circle btn-sm" title="{{ translate('Reject') }}">
                                    <i class="las la-times"></i>
                                </a>
                            @endif
                            <a href="javascript:void(0);" onclick="show_payout_modal('{{$employee->id}}');" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Pay Salary/Bonus') }}">
                                <i class="las la-money-bill-wave"></i>
                            </a>
                            <a href="{{route('admin.franchise_employees.payout_history', encrypt($employee->id))}}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Payout History') }}">
                                <i class="las la-history"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $employees->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    <div class="modal fade" id="payout_modal">
        <div class="modal-dialog">
            <div class="modal-content" id="payout-modal-content">

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function show_payout_modal(id){
            $.post('{{ route('admin.franchise_employees.payout_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#payout-modal-content').html(data);
                $('#payout_modal').modal('show', {backdrop: 'static'});
            });
        }
    </script>
@endsection
