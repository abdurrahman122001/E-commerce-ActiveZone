@extends($layout)

@section('panel_content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Add New Vendor')}}</h5>
            </div>
            <div class="card-body">
                @if(Session::has('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error') }}
                    </div>
                @endif

                <form action="{{ route('vendors.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Name')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="{{translate('Name')}}" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Email')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{translate('Email')}}" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Password')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{translate('Password')}}" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <input type="number" min="0" max="100" step="0.01" class="form-control @error('commission_percentage') is-invalid @enderror" name="commission_percentage" placeholder="{{translate('Commission Percentage')}}" value="{{ old('commission_percentage') }}" required>
                            @error('commission_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
