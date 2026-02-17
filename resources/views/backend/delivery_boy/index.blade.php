@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Delivery Boys')}}</h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="{{ route('delivery-boys.create') }}" class="btn btn-circle btn-info">
				<span>{{translate('Add New Delivery Boy')}}</span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Delivery Boys')}}</h5>
        <div class="pull-right">
            <form class="" id="sort_delivery_boys" action="" method="GET">
                <div class="box-inline pad-rgt no-hints">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name or email & Enter') }}">
                    </div>
                </div>
            </form>
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
                    <th data-breakpoints="lg">{{translate('Created By')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th width="10%" class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($delivery_boys as $key => $delivery_boy)
                    @if ($delivery_boy->user != null)
                        <tr>
                            <td>{{ ($key+1) + ($delivery_boys->currentPage() - 1)*$delivery_boys->perPage() }}</td>
                            <td>{{$delivery_boy->user->name}}</td>
                            <td>{{$delivery_boy->user->email}}</td>
                            <td>{{$delivery_boy->user->phone}}</td>
                            <td>
                                @if($delivery_boy->franchise_id)
                                    @php $f = \App\Models\User::find($delivery_boy->franchise_id); @endphp
                                    <span class="badge badge-inline badge-info">{{ translate('Franchise') }}: {{ $f ? $f->name : 'N/A' }}</span>
                                @elseif($delivery_boy->sub_franchise_id)
                                    @php $sf = \App\Models\User::find($delivery_boy->sub_franchise_id); @endphp
                                    <span class="badge badge-inline badge-primary">{{ translate('Sub-Franchise') }}: {{ $sf ? $sf->name : 'N/A' }}</span>
                                @else
                                    <span class="badge badge-inline badge-secondary">{{ translate('Admin') }}</span>
                                @endif
                            </td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_status(this)" value="{{ $delivery_boy->id }}" type="checkbox" <?php if($delivery_boy->status == 1) echo "checked";?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('delivery-boys.edit', $delivery_boy->id)}}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('delivery-boys.destroy', $delivery_boy->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $delivery_boys->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        function update_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('delivery-boy.update_status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
