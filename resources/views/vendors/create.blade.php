@extends($layout)

@section('panel_content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Add New Vendor')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('vendors.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Name')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="name" placeholder="{{translate('Name')}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Email')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="email" class="form-control" name="email" placeholder="{{translate('Email')}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Password')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="password" placeholder="{{translate('Password')}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Confirm Password')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="password_confirmation" placeholder="{{translate('Confirm Password')}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Commission Percentage')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="number" min="0" max="100" step="0.01" class="form-control" name="commission_percentage" placeholder="{{translate('Commission Percentage')}}" required>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
