@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Sub-Franchise')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <form class="p-4" action="{{ route('admin.sub_franchises.store') }}" method="POST" enctype="multipart/form-data">
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
                    <label class="col-sm-3 col-from-label" for="city_id">{{translate('City')}}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" name="city_id" id="city_id" data-live-search="true" required>
                            <option value="">{{ translate('Select City') }}</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->getTranslation('name') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="area_id">{{translate('Area')}}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" name="area_id" id="area_id" data-live-search="true" required>
                            <option value="">{{ translate('Select Area') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="investment_capacity">{{translate('Investment Capacity')}}</label>
                    <div class="col-sm-9">
                         <input type="number" step="0.01" placeholder="{{translate('Investment Capacity')}}" id="investment_capacity" name="investment_capacity" class="form-control" required>
                    </div>
                </div>
                 <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="business_experience">{{translate('Business Experience')}}</label>
                    <div class="col-sm-9">
                         <textarea class="form-control" name="business_experience" rows="3" placeholder="{{ translate('Briefly describe business experience') }}"></textarea>
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

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        $('#city_id').change(function(){
            var city_id = $(this).val();
            get_areas(city_id);
        });

        function get_areas(city_id) {
            $.post('{{ route('get-area') }}', { _token: '{{ csrf_token() }}', city_id: city_id }, function(data){
                $('#area_id').html(null);
                $('#area_id').append($('<option>', {
                    value: '',
                    text: '{{ translate("Select Area") }}'
                }));
                for (var i = 0; i < data.length; i++) {
                    $('#area_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $('.aiz-selectpicker').selectpicker('refresh');
            });
        }
    });
</script>
@endsection
