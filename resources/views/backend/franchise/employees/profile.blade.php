@extends('backend.franchise.employees.layout')

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
            <form action="{{ route('franchise.employee.profile.update') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Your Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Your Name') }}" name="name" value="{{ $employee->name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Your Email') }}</label>
                    <div class="col-md-10">
                        <input type="email" class="form-control" placeholder="{{ translate('Your Email') }}" value="{{ $employee->email }}" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Your Phone') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Your Phone') }}" value="{{ $employee->mobile }}" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('New Password') }}</label>
                    <div class="col-md-10">
                        <input type="password" class="form-control" placeholder="{{ translate('New Password') }}" name="password">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Confirm Password') }}</label>
                    <div class="col-md-10">
                        <input type="password" class="form-control" placeholder="{{ translate('Confirm Password') }}" name="password_confirmation">
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
            <form action="{{ route('franchise.employee.profile.update') }}" method="POST">
                @csrf
                <input type="hidden" name="name" value="{{ $employee->name }}">
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Bank Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Bank Name') }}" name="bank_name" value="{{ $employee->bank_name }}" {{ !empty($employee->bank_name) ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Bank Account Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Bank Account Name') }}" name="bank_acc_name" value="{{ $employee->bank_acc_name }}" {{ !empty($employee->bank_acc_name) ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Bank Account Number') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Bank Account Number') }}" name="bank_acc_no" value="{{ $employee->bank_acc_no }}" {{ !empty($employee->bank_acc_no) ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Bank Routing Number') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Bank Routing Number') }}" name="bank_routing_no" value="{{ $employee->bank_routing_no }}" {{ !empty($employee->bank_routing_no) ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Update Bank Details') }}</button>
                </div>
            </form>
        </div>
    </div>
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
                        var container = input.closest('.col-md-9').find('.profile-preview-container');
                        if (container.length == 0) {
                            input.closest('.col-md-9').append('<div class="mt-3 profile-preview-container"><img class="img-fluid rounded shadow-sm profile-preview-img" src="" style="max-height: 120px; border: 1px solid #ddd; background: #f8f9fa; padding: 5px;"></div>');
                            container = input.closest('.col-md-9').find('.profile-preview-container');
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
