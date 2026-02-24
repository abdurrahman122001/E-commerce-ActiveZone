@extends('franchise.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Sub-Franchises') }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('franchise.sub_franchises.create') }}" class="btn btn-primary">
                <span>{{ translate('Add New Sub-Franchise') }}</span>
            </a>
        </div>
      </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('All Sub-Franchises') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('Email') }}</th>
                        <th>{{ translate('Phone') }}</th>
                        <th>{{ translate('State') }}</th>
                        <th>{{ translate('City') }}</th>
                        <th data-breakpoints="lg">{{ translate('Area') }}</th>
                        <th data-breakpoints="lg">{{ translate('Package') }}</th>
                        <th data-breakpoints="lg">{{ translate('Referral Code') }}</th>
                        <th data-breakpoints="lg">{{ translate('Status') }}</th>
                        <th>{{ translate('Commission (%)') }}</th>
                        <th class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subFranchises as $key => $sub)
                        <tr>
                            <td>{{ ($key+1) + ($subFranchises->currentPage() - 1)*$subFranchises->perPage() }}</td>
                            <td>{{ $sub->user->name ?? '' }}</td>
                            <td>{{ $sub->user->email ?? '' }}</td>
                            <td>{{ $sub->user->phone ?? '' }}</td>
                            <td>{{ $sub->state->name ?? '' }}</td>
                            <td>{{ $sub->city->name ?? '' }}</td>
                            <td>{{ $sub->area->name ?? '' }}</td>
                            <td>{{ $sub->franchise_package ? $sub->franchise_package->getTranslation('name') : '' }}</td>
                            <td>{{ $sub->referral_code }}</td>
                            <td>
                                @if($sub->status == 'approved')
                                    <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
                                @elseif($sub->status == 'pending')
                                    <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ translate('Rejected') }}</span>
                                @endif
                            </td>
                            <td>{{ $sub->commission_percentage }}%</td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                        <i class="las la-ellipsis-v seller-list-icon"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                        @if($sub->status == 'pending')
                                            <a href="{{ route('franchise.sub_franchises.approve', $sub->id) }}" class="dropdown-item">
                                                {{translate('Approve')}}
                                            </a>
                                            <a href="{{ route('franchise.sub_franchises.reject', $sub->id) }}" class="dropdown-item">
                                                {{translate('Reject')}}
                                            </a>
                                        @endif
                                        <a href="javascript:void(0);" onclick="show_commission_modal('{{$sub->id}}', '{{$sub->commission_percentage}}');" class="dropdown-item">
                                            {{translate('Set Commission')}}
                                        </a>
                                        <a href="{{route('franchise.sub_franchises.login', encrypt($sub->id))}}" class="dropdown-item">
                                            {{translate('Login')}}
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
    <!-- Commission Modal -->
    <div class="modal fade" id="commission_modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('franchise.sub_franchises.set_commission') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="sub_franchise_id">
                    <div class="modal-header">
                        <h5 class="modal-title h6">{{translate('Set Sub-Franchise Commission')}}</h5>
                        <button type="button" class="close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Commission')}}</label>
                            <div class="col-md-9">
                                <input type="number" step="0.01" min="0" max="100" name="commission_percentage" id="commission_percentage_input" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    function show_commission_modal(id, commission){
        $('#sub_franchise_id').val(id);
        $('#commission_percentage_input').val(commission);
        $('#commission_modal').modal('show');
    }
</script>
@endsection
