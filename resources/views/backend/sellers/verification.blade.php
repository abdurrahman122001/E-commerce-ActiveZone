@extends('backend.layouts.app')

@section('content')

<div class="card">
  <div class="card-header">
      <h5 class="mb-0 h6">{{ translate('Seller Verification') }}</h5>
      @if ($shop->verification_status != 1 && $shop->verification_info != null)
        <div class="pull-right clearfix">
            <a href="javascript:void(0);" data-href="{{ route('sellers.reject', $shop->id) }}" class="btn btn-circle btn-danger d-innline-block confirm-reject">{{translate('Reject')}}</a>
            <a href="{{ route('sellers.approve', $shop->id) }}" class="btn btn-circle btn-success d-innline-block">{{translate('Accept')}}</a>
            <button type="button" class="btn btn-circle btn-warning d-innline-block" data-toggle="modal" data-target="#requestDocsModal">
                <i class="la la-file-upload"></i> {{translate('Request Docs')}}
            </button>
        </div>
      @elseif($shop->verification_info == null)
        <div class="pull-right clearfix">
            <button type="button" class="btn btn-circle btn-warning d-innline-block" data-toggle="modal" data-target="#requestDocsModal">
                <i class="la la-file-upload"></i> {{translate('Request Documents')}}
            </button>
        </div>
      @endif
  </div>
  <div class="card-body row">
      <div class="col-md-5">
          <h6 class="mb-4">{{ translate('User Info') }}</h6>
          <p class="text-muted">
              <strong>{{ translate('Name') }} :</strong>
              <span class="ml-2">{{ $shop->user->name }}</span>
          </p>
          <p class="text-muted">
              <strong>{{translate('Email')}}</strong>
              <span class="ml-2">{{ $shop->user->email }}</span>
          </p>
          <p class="text-muted">
              <strong>{{translate('Address')}}</strong>
              <span class="ml-2">{{ $shop->user->address }}</span>
          </p>
          <p class="text-muted">
              <strong>{{translate('Phone')}}</strong>
              <span class="ml-2">{{ $shop->user->phone }}</span>
          </p>
          <br>

          <h6 class="mb-4">{{ translate('Shop Info') }}</h6>
          <p class="text-muted">
              <strong>{{translate('Shop Name')}}</strong>
              <span class="ml-2">{{ $shop->user->shop->name }}</span>
          </p>
          <p class="text-muted">
              <strong>{{translate('Address')}}</strong>
              <span class="ml-2">{{ $shop->address }}</span>
          </p>

          {{-- Show additional doc request note if present --}}
          @if($shop->additional_doc_request && $shop->additional_doc_request_note)
          <div class="alert alert-warning mt-3">
              <strong><i class="la la-exclamation-triangle"></i> {{translate('Additional Documents Requested')}}:</strong><br>
              {{ $shop->additional_doc_request_note }}
          </div>
          @endif
      </div>
      <div class="col-md-7">
        <h6 class="mb-4">{{ translate('Verification Info') }}</h6>
        @if ($shop->verification_info != null)
          <table class="table table-striped table-bordered" cellspacing="0" width="100%">
              <tbody>
                  @foreach (json_decode($shop->verification_info) as $key => $info)
                      <tr>
                          <th class="text-muted" style="width:35%">{{ $info->label }}</th>
                          @if ($info->type == 'text' || $info->type == 'select' || $info->type == 'radio')
                              <td>{{ $info->value }}</td>
                          @elseif ($info->type == 'multi_select')
                              <td>
                                  {{ implode(', ', json_decode($info->value)) }}
                              </td>
                          @elseif ($info->type == 'file')
                              <td>
                                  <a href="{{ my_asset($info->value) }}" target="_blank" class="btn btn-sm btn-info">
                                      <i class="la la-eye"></i> {{translate('View File')}}
                                  </a>
                              </td>
                          @elseif ($info->type == 'aadhaar_front')
                              <td>
                                  <a href="{{ my_asset($info->value) }}" target="_blank" class="btn btn-sm btn-primary">
                                      <i class="la la-id-card"></i> {{translate('View Aadhaar Front')}}
                                  </a>
                              </td>
                          @elseif ($info->type == 'aadhaar_back')
                              <td>
                                  <a href="{{ my_asset($info->value) }}" target="_blank" class="btn btn-sm btn-secondary">
                                      <i class="la la-id-card"></i> {{translate('View Aadhaar Back')}}
                                  </a>
                              </td>
                          @endif
                      </tr>
                  @endforeach
              </tbody>
          </table>
        @else
          <div class="alert alert-info">{{ translate('No verification info submitted yet.') }}</div>
        @endif

        @if ($shop->verification_status != 1 && $shop->verification_info != null)
          <div class="text-center mt-3">
              <a href="javascript:void(0);" data-href="{{ route('sellers.reject', $shop->id) }}" class="btn btn-sm btn-danger confirm-reject">{{translate('Reject')}}</a>
              <a href="{{ route('sellers.approve', $shop->id) }}" class="btn btn-sm btn-success">{{translate('Accept')}}</a>
              <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#requestDocsModal">
                  {{translate('Request Additional Docs')}}
              </button>
          </div>
        @endif
      </div>
  </div>
</div>

{{-- Request Additional Documents Modal --}}
<div class="modal fade" id="requestDocsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('sellers.request_additional_docs', $shop->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Request Additional Documents') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">{{ translate('Specify what additional documents you need from the seller. The seller will be notified and asked to resubmit.') }}</p>
                    <div class="form-group">
                        <label>{{ translate('Document Request Note') }} <span class="text-danger">*</span></label>
                        <textarea name="doc_request_note" class="form-control" rows="4" required
                            placeholder="{{ translate('e.g. Please provide a clearer photo of your Aadhaar card, both front and back.') }}">{{ $shop->additional_doc_request_note }}</textarea>
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

@endsection
