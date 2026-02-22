@extends('franchise.layouts.app')

@section('panel_content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="h3">{{translate('Edit Employee')}}</h1>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Employee Information')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('franchise.employees.update', $employee->id) }}" method="POST">
                    @csrf
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('Name')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $employee->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('Email')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $employee->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('Mobile')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile', $employee->mobile) }}" required>
                            @error('mobile')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('New Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                            <small class="text-muted">{{translate('Leave blank to keep current password')}}</small>
                            @error('password')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('Confirm Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password_confirmation">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('Role')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('role') is-invalid @enderror" name="role" value="{{ old('role', $employee->role) }}" required>
                            @error('role')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if(auth()->user()->user_type == 'franchise')
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('Franchise Level')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control aiz-selectpicker @error('franchise_level') is-invalid @enderror" name="franchise_level" id="franchise_level" required>
                                <option value="CITY" {{ old('franchise_level', $employee->franchise_level) == 'CITY' ? 'selected' : '' }}>{{translate('City Level')}}</option>
                                <option value="SUB" {{ old('franchise_level', $employee->franchise_level) == 'SUB' ? 'selected' : '' }}>{{translate('Sub Franchise Level')}}</option>
                            </select>
                            @error('franchise_level')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row" id="sub_franchise_container" style="{{ old('franchise_level', $employee->franchise_level) == 'SUB' ? '' : 'display: none;' }}">
                        <label class="col-sm-3 col-from-label">{{translate('Assign to Sub Franchise')}}</label>
                        <div class="col-sm-9">
                            <select class="form-control aiz-selectpicker @error('sub_franchise_id') is-invalid @enderror" name="sub_franchise_id">
                                <option value="">{{translate('Select Sub Franchise')}}</option>
                                @foreach($subFranchises as $subFranchise)
                                    <option value="{{ $subFranchise->id }}" {{ old('sub_franchise_id', $employee->sub_franchise_id) == $subFranchise->id ? 'selected' : '' }}>
                                        {{ $subFranchise->user->name ?? '' }} ({{ $subFranchise->referral_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('sub_franchise_id')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @else
                        <input type="hidden" name="franchise_level" value="SUB">
                    @endif

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('City')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control aiz-selectpicker @error('city_id') is-invalid @enderror" name="city_id" required data-live-search="true">
                                <option value="">{{translate('Select City')}}</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id', $employee->city_id) == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('Commission Percentage (%)')}}</label>
                        <div class="col-sm-9">
                            <input type="number" step="0.01" min="0" max="100" class="form-control @error('commission_percentage') is-invalid @enderror" name="commission_percentage" value="{{ old('commission_percentage', $employee->commission_percentage) }}">
                            @error('commission_percentage')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('Active')}}</label>
                        <div class="col-sm-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
                        <a href="{{ route('franchise.employees.index') }}" class="btn btn-soft-danger">{{translate('Cancel')}}</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#franchise_level').on('change', function() {
            if ($(this).val() == 'SUB') {
                $('#sub_franchise_container').show();
            } else {
                $('#sub_franchise_container').hide();
                $('select[name="sub_franchise_id"]').val('');
            }
        });
    });
</script>
@endsection
