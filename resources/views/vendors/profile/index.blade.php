@extends('vendors.layouts.app')

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
            <form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Your Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Your Name') }}" name="name" value="{{ $user->name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Your Phone') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Your Phone') }}" name="phone" value="{{ $user->phone }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Photo') }} <small>(600x600)</small></label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="avatar" class="selected-files" value="{{ $user->avatar_original }}">
                        </div>
                        <div class="file-preview box sm">
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
            <form action="{{ route('vendor.profile.update') }}" method="POST">
                @csrf
                <input type="hidden" name="name" value="{{ $user->name }}">
                <input type="hidden" name="phone" value="{{ $user->phone }}">
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Bank Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Bank Name') }}" name="bank_name" value="{{ $vendor->bank_name ?? '' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Bank Account Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Bank Account Name') }}" name="bank_acc_name" value="{{ $vendor->bank_acc_name ?? '' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Bank Account Number') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Bank Account Number') }}" name="bank_acc_no" value="{{ $vendor->bank_acc_no ?? '' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Bank Routing Number') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Bank Routing Number') }}" name="bank_routing_no" value="{{ $vendor->bank_routing_no ?? '' }}">
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Update Bank Details') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
