@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New State Franchise')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <form class="p-4" action="{{ route('admin.state_franchises.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="email" placeholder="{{translate('Email')}}" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="phone">{{translate('Phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Phone')}}" id="phone" name="phone" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="state_id">{{translate('State')}}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" name="state_id" id="state_id" data-live-search="true" required>
                            <option value="">{{ translate('Select State') }}</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="pincode">{{translate('Pin Code')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Pin Code')}}" id="pincode" name="pincode" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="address">{{translate('Address')}}</label>
                    <div class="col-sm-9">
                        <textarea name="address" rows="2" class="form-control" placeholder="{{translate('Address')}}" required></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="franchise_package_id">{{translate('Package')}}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" name="franchise_package_id" id="franchise_package_id" data-live-search="true" required>
                            <option value="">{{ translate('Select Package') }}</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->getTranslation('name') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="bank_name">{{translate('Bank Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Bank Name')}}" id="bank_name" name="bank_name" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="bank_acc_name">{{translate('Bank Account Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Bank Account Name')}}" id="bank_acc_name" name="bank_acc_name" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="bank_acc_no">{{translate('Bank Account No')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Bank Account No')}}" id="bank_acc_no" name="bank_acc_no" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="bank_routing_no">{{translate('Bank Routing No')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Bank Routing No')}}" id="bank_routing_no" name="bank_routing_no" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="ifsc_code">{{translate('IFSC Code')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('IFSC Code')}}" id="ifsc_code" name="ifsc_code" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="commission_percentage">{{translate('Commission')}}</label>
                    <div class="col-sm-5">
                        <input type="number" step="0.01" min="0" placeholder="{{translate('Commission Value')}}" id="commission_percentage" name="commission_percentage" value="0.00" class="form-control" required>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control aiz-selectpicker" name="commission_type" id="commission_type">
                            <option value="percentage">{{translate('Percentage (%)')}}</option>
                            <option value="flat">{{translate('Flat Amount')}}</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
