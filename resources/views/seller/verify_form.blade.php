@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Shop Verification')}}
                <a href="{{ route('shop.visit', $shop->slug) }}" class="btn btn-link btn-sm" target="_blank">({{ translate('Visit Shop')}})<i class="la la-external-link"></i>)</a>
            </h1>
        </div>
      </div>
    </div>

    {{-- Additional doc request alert from admin --}}
    @if($shop->additional_doc_request && $shop->additional_doc_request_note)
    <div class="alert alert-warning">
        <h6><i class="la la-exclamation-triangle"></i> {{ translate('Admin has requested additional documents') }}</h6>
        <p class="mb-0">{{ $shop->additional_doc_request_note }}</p>
    </div>
    @endif

    <form class="" action="{{ route('seller.shop.verify.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 h6">{{ translate('Verification info')}}</h4>
            </div>
            @php
                $verification_form = get_setting('verification_form');
            @endphp
            <div class="card-body">
                @foreach (json_decode($verification_form) as $key => $element)
                    @if ($element->type == 'text')
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>{{ $element->label }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input type="{{ $element->type }}" class="form-control" placeholder="{{ $element->label }}" name="element_{{ $key }}" required>
                            </div>
                        </div>
                    @elseif($element->type == 'file')
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>{{ $element->label }}</label>
                            </div>
                            <div class="col-md-9">
                                <div class="custom-file">
                                    <label class="custom-file-label">
                                        <input type="{{ $element->type }}" name="element_{{ $key }}" id="file-{{ $key }}" accept=".jpg,.jpeg,.png,.bmp,application/pdf" class="custom-file-input" required>
                                        <span class="custom-file-name">{{ translate('Choose file') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @elseif ($element->type == 'select' && is_array(json_decode($element->options)))
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>{{ $element->label }}</label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="element_{{ $key }}" required>
                                    @foreach (json_decode($element->options) as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @elseif ($element->type == 'multi_select' && is_array(json_decode($element->options)))
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>{{ $element->label }}</label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="element_{{ $key }}[]" multiple required>
                                    @foreach (json_decode($element->options) as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @elseif ($element->type == 'radio')
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>{{ $element->label }}</label>
                            </div>
                            <div class="col-md-9">
                                @foreach (json_decode($element->options) as $value)
                                    <div class="radio radio-inline">
                                        <input type="radio" name="element_{{ $key }}" value="{{ $value }}" id="{{ $value }}" required>
                                        <label for="{{ $value }}">{{ $value }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- Aadhaar Card Section (Always shown, separate front and back) --}}
                <hr>
                <h6 class="mb-3 text-primary"><i class="la la-id-card"></i> {{ translate('Aadhaar Card') }}
                    <small class="text-muted">({{ translate('Please upload both front and back sides separately') }})</small>
                </h6>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>{{ translate('Aadhaar Card (Front)') }} <span class="text-danger">*</span></label>
                        <small class="text-muted d-block">{{ translate('Front side showing your name and Aadhaar number') }}</small>
                    </div>
                    <div class="col-md-9">
                        <div class="custom-file">
                            <input type="file" name="aadhaar_front" id="aadhaar_front" accept=".jpg,.jpeg,.png,.bmp,application/pdf" class="custom-file-input" required onchange="previewFile(this, 'preview_aadhaar_front')">
                            <label class="custom-file-label" for="aadhaar_front">{{ translate('Choose file') }}</label>
                        </div>
                        <img id="preview_aadhaar_front" src="#" alt="" class="mt-2 d-none" style="max-height:120px; border:1px solid #ddd; border-radius:4px;">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>{{ translate('Aadhaar Card (Back)') }} <span class="text-danger">*</span></label>
                        <small class="text-muted d-block">{{ translate('Back side showing your address') }}</small>
                    </div>
                    <div class="col-md-9">
                        <div class="custom-file">
                            <input type="file" name="aadhaar_back" id="aadhaar_back" accept=".jpg,.jpeg,.png,.bmp,application/pdf" class="custom-file-input" required onchange="previewFile(this, 'preview_aadhaar_back')">
                            <label class="custom-file-label" for="aadhaar_back">{{ translate('Choose file') }}</label>
                        </div>
                        <img id="preview_aadhaar_back" src="#" alt="" class="mt-2 d-none" style="max-height:120px; border:1px solid #ddd; border-radius:4px;">
                    </div>
                </div>

                <div class="text-right mt-4">
                    <button type="submit" class="btn btn-primary">{{ translate('Apply')}}</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
<script>
function previewFile(input, previewId) {
    var preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        var file = input.files[0];
        if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('d-none');
        }
        // Update label
        var label = input.nextElementSibling;
        if (label) label.textContent = file.name;
    }
}
</script>
@endsection
