@extends($layout)

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Edit Vendor') }}</h1>
        </div>
      </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Vendor Information') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ $update_route }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Name') }}</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" value="{{ $vendor->user->name }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Email') }}</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" value="{{ $vendor->user->email }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Shop Name') }}</label>
                            <div class="col-sm-9">
                                <input type="text" name="shop_name" value="{{ $vendor->shop_name }}" class="form-control" placeholder="{{ translate('Shop Name') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Address') }}</label>
                            <div class="col-sm-9">
                                <textarea name="address" class="form-control" rows="3" required>{{ $vendor->address }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Password') }}</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" class="form-control">
                                <small>{{ translate('Leave blank to keep current password') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Confirm Password') }}</label>
                            <div class="col-sm-9">
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Commission Percentage (%)') }}</label>
                            <div class="col-sm-9">
                                <input type="number" step="0.01" min="0" max="100" name="commission_percentage" value="{{ $vendor->commission_percentage }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
