@extends('backend.layouts.app')

@section('content')

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Delivery Boy Information')}}</h5>
        </div>
        <form class="form-horizontal" action="{{ route('delivery-boys.update', $delivery_boy->id) }}" method="POST" enctype="multipart/form-data">
        	<input name="_method" type="hidden" value="PATCH">
        	@csrf
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" value="{{ $delivery_boy->user->name }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Email')}}" id="email" name="email" value="{{ $delivery_boy->user->email }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Phone')}}" id="phone" name="phone" value="{{ $delivery_boy->user->phone }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Location')}}</label>
                    <div class="col-sm-9">
                        <select id="location" name="location" class="form-control aiz-selectpicker" data-live-search="true" required>
                             @foreach($areas as $area)
                                 <option value="{{ $area->name }}" @if($delivery_boy->location == $area->name) selected @endif>{{ $area->getTranslation('name') }}</option>
                             @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
