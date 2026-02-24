@extends('franchise.layouts.app')

@section('panel_content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Add New City Franchise')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('franchise.city_franchises.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label class="col-md-3 col-from-label">{{translate('Phone')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="phone" placeholder="{{translate('Phone')}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Password')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="password" placeholder="{{translate('Password')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('State')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-control aiz-selectpicker" name="state_id" id="state_id" data-live-search="true" disabled>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" selected>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('City')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-control aiz-selectpicker" name="city_id" id="city_id" data-live-search="true" required>
                                <option value="">{{ translate('Select City') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Package')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-control aiz-selectpicker" name="franchise_package_id" data-live-search="true" required>
                                <option value="">{{ translate('Select Package') }}</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Business Experience')}}</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="business_experience" placeholder="{{translate('Business Experience')}}"></textarea>
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

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        var state_id = $('#state_id').val();
        if(state_id){
            get_cities(state_id);
        }

        function get_cities(state_id){
            $.post('{{ route('get-city') }}', { _token: '{{ csrf_token() }}', state_id: state_id }, function(data){
                $('#city_id').html(null);
                $('#city_id').append($('<option>', {
                    value: '',
                    text: '{{ translate("Select City") }}'
                }));
                var obj = JSON.parse(data);
                $('#city_id').append(obj);
                $('.aiz-selectpicker').selectpicker('refresh');
            });
        }
    });

    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@endsection
