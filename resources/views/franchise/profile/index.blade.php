@extends('franchise.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Manage Profile') }}</h1>
        </div>
      </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Basic Info') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('franchise.profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Your Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Your Name') }}" name="name" value="{{ Auth::user()->name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Your Phone') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Your Phone') }}" name="phone" value="{{ Auth::user()->phone }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Photo') }} <small>(600x600)</small></label>
                    <div class="col-md-10">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="avatar" id="avatar" accept="image/*">
                            <label class="custom-file-label" for="avatar">{{ translate('Choose file') }}</label>
                        </div>
                        <div class="profile-preview-container mt-3" style="display:{{ Auth::user()->avatar_original ? 'block' : 'none' }}">
                            <img class="img-fluid rounded shadow-sm profile-preview-img" 
                                 src="{{ uploaded_asset(Auth::user()->avatar_original) }}" 
                                 style="max-height: 120px; border: 1px solid #ddd; background: #f8f9fa; padding: 5px;">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Your Password') }}</label>
                    <div class="col-md-10">
                        <input type="password" class="form-control" placeholder="{{ translate('New Password') }}" name="new_password">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Confirm Password') }}</label>
                    <div class="col-md-10">
                        <input type="password" class="form-control" placeholder="{{ translate('Confirm Password') }}" name="confirm_password">
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Update Profile') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Bank Account Details') }}</h5>
        </div>
        <div class="card-body">
            @php
                if (Auth::user()->user_type == 'franchise') {
                    $role_data = Auth::user()->franchise;
                } elseif (Auth::user()->user_type == 'sub_franchise') {
                    $role_data = Auth::user()->sub_franchise;
                } elseif (Auth::user()->user_type == 'state_franchise') {
                    $role_data = Auth::user()->state_franchise;
                } else {
                    $role_data = null;
                }
            @endphp
            <form action="{{ route('franchise.profile.update', Auth::user()->id) }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Bank Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Bank Name') }}" name="bank_name" value="{{ $role_data->bank_name ?? '' }}" {{ !empty($role_data->bank_name) ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Bank Account Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Bank Account Name') }}" name="bank_acc_name" value="{{ $role_data->bank_acc_name ?? '' }}" {{ !empty($role_data->bank_acc_name) ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Bank Account Number') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Bank Account Number') }}" name="bank_acc_no" value="{{ $role_data->bank_acc_no ?? '' }}" {{ !empty($role_data->bank_acc_no) ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Branch Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Branch Name') }}" name="bank_routing_no" value="{{ $role_data->bank_routing_no ?? '' }}" {{ !empty($role_data->bank_routing_no) ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('IFSC Code') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('IFSC Code') }}" name="ifsc_code" value="{{ $role_data->ifsc_code ?? '' }}" {{ !empty($role_data->ifsc_code) ? 'readonly' : '' }}>
                    </div>
                </div>
                <!-- Hidden fields to preserve existing user data if updated from this form -->
                <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                <input type="hidden" name="phone" value="{{ Auth::user()->phone }}">
                
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Update Bank Details') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).on('change', '.custom-file-input', function() {
            var input = $(this);
            var file = this.files[0];
            var label = input.siblings('.custom-file-label');
            
            if (file) {
                var fileName = file.name;
                label.addClass("selected").html(fileName);
                
                // Show local preview if it's an image
                if (file.type.match('image.*')) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        // Find or create preview container
                        var container = input.closest('.col-md-10').find('.profile-preview-container');
                        if (container.length == 0) {
                            input.closest('.col-md-10').append('<div class="mt-3 profile-preview-container"><img class="img-fluid rounded shadow-sm profile-preview-img" src="" style="max-height: 120px; border: 1px solid #ddd; background: #f8f9fa; padding: 5px;"></div>');
                            container = input.closest('.col-md-10').find('.profile-preview-container');
                        }
                        container.find('.profile-preview-img').attr('src', e.target.result);
                        container.show();
                    }
                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
@endsection
