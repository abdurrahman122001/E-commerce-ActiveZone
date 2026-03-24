<div class="modal-body">
    <h6 class="mb-4 font-weight-bold">{{ translate('Verification Info') }}</h6>
    @if($shop->additional_doc_request && $shop->additional_doc_request_note)
        <div class="alert alert-warning">
            <strong><i class="la la-exclamation-triangle"></i> {{translate('Additional Documents Requested')}}:</strong><br>
            {{ $shop->additional_doc_request_note }}
        </div>
    @endif
    @if ($shop->verification_info != null)
    <table class="table inv-table-2" cellspacing="0" width="100%">
        <tbody>
            @foreach (json_decode($shop->verification_info) as $key => $info)
            <tr>
                <th class="text-muted">{{ $info->label }}</th>
                @if ($info->type == 'text' || $info->type == 'select' || $info->type == 'radio')
                <td>{{ $info->value }}</td>
                @elseif ($info->type == 'multi_select')
                <td>
                    {{ implode(', ', json_decode($info->value)) }}
                </td>
                @elseif ($info->type == 'file')
                <td>
                    <a href="{{ my_asset($info->value) }}" target="_blank" >{{translate('Click here')}}</a>
                </td>
                @elseif ($info->type == 'aadhaar_front' || $info->type == 'aadhaar_back')
                <td>
                    <a href="{{ my_asset($info->value) }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="la la-id-card"></i> {{translate('View')}}
                    </a>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @if ($shop->verification_status != 1 && $shop->verification_info != null)
    <div class="text-center">
        <a href="javascript:void(0);" data-href="{{ route('sellers.reject', $shop->id) }}" class="btn btn-sm btn-danger d-innline-block confirm-reject">{{translate('Reject')}}</a>
        <a href="{{ route('sellers.approve', $shop->id) }}" class="btn btn-sm btn-success d-innline-block">{{translate('Accept')}}</a>
    </div>
    @endif
</div>