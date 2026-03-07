@extends('backend.layouts.app')

@section('content')

    <div class="col-lg-6  mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Profile')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PATCH">
                	@csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="{{translate('Name')}}" name="name" value="{{ Auth::user()->name }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Email')}}</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" placeholder="{{translate('Email')}}" name="email" value="{{ Auth::user()->email }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="new_password">{{translate('New Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" placeholder="{{translate('New Password')}}" name="new_password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="confirm_password">{{translate('Confirm Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" placeholder="{{translate('Confirm Password')}}" name="confirm_password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Avatar')}} <small>(90x90)</small></label>
                        <div class="col-md-9">
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
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
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
