@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Edit City Franchise')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <form class="p-4" action="{{ route('admin.franchises.update', $franchise->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" value="{{ $franchise->user->name }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="email" placeholder="{{translate('Email')}}" id="email" name="email" value="{{ $franchise->user->email }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="phone">{{translate('Phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Phone')}}" id="phone" name="phone" value="{{ $franchise->user->phone }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control">
                        <small class="text-muted">{{ translate('Leave blank to keep current password') }}</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="state_id">{{translate('State')}}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" name="state_id" id="state_id" data-live-search="true" required>
                            <option value="">{{ translate('Select State') }}</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" @if($franchise->state_id == $state->id) selected @endif>{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="city_id">{{translate('City')}}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" name="city_id" id="city_id" data-live-search="true" required>
                            <option value="">{{ translate('Select City') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="franchise_package_id">{{translate('Package')}}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" name="franchise_package_id" id="franchise_package_id" data-live-search="true" required>
                            <option value="">{{ translate('Select Package') }}</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" @if($franchise->franchise_package_id == $package->id) selected @endif>{{ $package->getTranslation('name') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="invalid_at">{{translate('Package Expiry Date')}}</label>
                    <div class="col-sm-9">
                        <input type="date" id="invalid_at" name="invalid_at" value="{{ $franchise->invalid_at }}" class="form-control">
                    </div>
                </div>
                 <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="business_experience">{{translate('Business Experience')}}</label>
                    <div class="col-sm-9">
                         <textarea class="form-control" name="business_experience" rows="3" placeholder="{{ translate('Briefly describe business experience') }}">{{ $franchise->business_experience }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="id_proof">{{translate('ID Proof')}}</label>
                    <div class="col-sm-9">
                        <div class="custom-file">
                            <label class="custom-file-label">
                                <input type="file" name="id_proof" class="custom-file-input">
                                <span class="custom-file-name">{{ translate('Choose File') }}</span>
                            </label>
                        </div>
                        @if($franchise->id_proof)
                            <div class="mt-2">
                                <a href="{{ asset('storage/'.$franchise->id_proof) }}" target="_blank" class="text-info">{{ translate('View Current ID Proof') }}</a>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="bank_name">{{translate('Bank Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Bank Name')}}" id="bank_name" name="bank_name" value="{{ $franchise->bank_name }}" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="bank_acc_name">{{translate('Bank Account Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Bank Account Name')}}" id="bank_acc_name" name="bank_acc_name" value="{{ $franchise->bank_acc_name }}" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="bank_acc_no">{{translate('Bank Account No')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Bank Account No')}}" id="bank_acc_no" name="bank_acc_no" value="{{ $franchise->bank_acc_no }}" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="bank_routing_no">{{translate('Bank Routing No')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Bank Routing No')}}" id="bank_routing_no" name="bank_routing_no" value="{{ $franchise->bank_routing_no }}" class="form-control">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="commission_percentage">{{translate('Commission Percentage (%)')}}</label>
                    <div class="col-sm-9">
                        <input type="number" step="0.01" min="0" max="100" placeholder="{{translate('Commission Percentage')}}" id="commission_percentage" name="commission_percentage" value="{{ $franchise->commission_percentage }}" class="form-control">
                    </div>
                </div>
                
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        get_cities('{{ $franchise->state_id }}');
        
        $('#state_id').change(function(){
            var state_id = $(this).val();
            get_cities(state_id);
        });

        function get_cities(state_id){
            $.post('{{ route('get-city') }}', { 
                _token: '{{ csrf_token() }}', 
                state_id: state_id, 
                franchise_type: 'city_franchise',
                exclude_franchise_id: '{{ $franchise->id }}'
            }, function(data){
                var obj = JSON.parse(data);
                if(obj.indexOf('disabled') == -1){
                    var html = '<option value="">{{ translate("Select City") }}</option>';
                    $('#city_id').html(html + obj);
                } else {
                    $('#city_id').html(obj);
                }
                $('#city_id').val('{{ $franchise->city_id }}');
                $('.aiz-selectpicker').selectpicker('refresh');
            });
        }
    });
</script>
@endsection
