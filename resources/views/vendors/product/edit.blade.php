@extends('vendors.layouts.app')

@section('panel_content')
    <div class="page-content mx-0">
        <div class="aiz-titlebar mt-2 mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h3">{{ translate('Edit Your Product') }}</h1>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Data type -->
        <input type="hidden" id="data_type" value="physical">

        <form class="" action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="choice_form">
            <div class="row gutters-5">
                <div class="col-lg-8">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="added_by" value="vendor">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Product Name') }} <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $product->getTranslation('name') }}"
                                        placeholder="{{ translate('Product Name') }}" onchange="update_sku()" required>
                                </div>
                            </div>
                            <div class="form-group row" id="brand">
                                <label class="col-md-3 col-from-label">{{ translate('Brand') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                        data-live-search="true">
                                        <option value="">{{ translate('Select Brand') }}</option>
                                        @foreach (\App\Models\Brand::all() as $brand)
                                            <option value="{{ $brand->id }}" @if($product->brand_id == $brand->id) selected @endif>{{ $brand->getTranslation('name') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Unit') }} <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="unit"
                                        value="{{ $product->getTranslation('unit') }}"
                                        placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Weight') }}
                                    <small>({{ translate('In Kg') }})</small></label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="weight" step="0.01" value="{{ $product->weight }}"
                                        placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Minimum Purchase Qty') }} <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" class="form-control" name="min_qty" value="{{ $product->min_qty }}"
                                        min="1" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Tags') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                        value="{{ $product->tags }}"
                                        placeholder="{{ translate('Type and hit enter to add a tag') }}">
                                </div>
                            </div>
                            @if (addon_is_activated('pos_system'))
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Barcode') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="barcode"
                                            value="{{ $product->barcode }}"
                                            placeholder="{{ translate('Barcode') }}">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Images') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Gallery Images') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image"
                                        data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="photos" value="{{ $product->photos }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <small class="text-muted">{{translate('These images are visible in product details page gallery. Minimum dimensions required: 900px width X 900px height.')}}</small>
                                    <div class="mt-2">
                                        <input type="file" name="photos_file[]" multiple class="form-control" accept="image/*">
                                        <small class="text-muted">{{ translate('Or upload from your computer') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Thumbnail Image') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="thumbnail_img" value="{{ $product->thumbnail_img }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <small class="text-muted">{{translate("This image is visible in all product box. Minimum dimensions required: 195px width X 195px height.")}}</small>
                                    <div class="mt-2">
                                        <input type="file" name="thumbnail_img_file" class="form-control" accept="image/*">
                                        <small class="text-muted">{{ translate('Or upload from your computer') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Videos') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ translate('Videos') }}</label>
                                <div class="col-md-9">
                                    <div class="input-group" data-toggle="aizuploader" data-type="video" data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="short_video" value="{{ $product->short_video }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                    <small class="text-muted">{{ translate('Try to upload videos under 30 seconds for better performance.') }}</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ translate('Youtube video / shorts link') }}</label>
                                <div class="video-provider-link col-md-9">
                                    @php
                                        $video_links = json_decode($product->video_link);
                                    @endphp
                                    @if(!empty($video_links))
                                        @foreach($video_links as $link)
                                            <div class="row mb-2">
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="video_link[]" value="{{ $link }}" placeholder="{{ translate('Youtube video / shorts url') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-icon btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row"><i class="las la-times"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="row">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="video_link[]" placeholder="{{ translate('Youtube video / shorts url') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="add-more" data-target=".video-provider-link" data-content='<div class="row mt-2"><div class="col-md-10"><input type="text" class="form-control" name="video_link[]" placeholder="{{ translate('Youtube video / shorts url') }}"></div><div class="col-md-2"><button type="button" class="btn btn-icon btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row"><i class="las la-times"></i></button></div></div>'>{{ translate('Add') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Variation') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" value="{{ translate('Colors') }}" disabled>
                                </div>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" name="colors[]"
                                        data-selected-text-format="count" id="colors" multiple @if(empty(json_decode($product->colors))) disabled @endif>
                                        @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                                            <option value="{{ $color->code }}"
                                                data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"
                                                @if(in_array($color->code, json_decode($product->colors) ?: [])) selected @endif>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" name="colors_active" @if(!empty(json_decode($product->colors))) checked @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" value="{{ translate('Attributes') }}"
                                        disabled>
                                </div>
                                <div class="col-md-8">
                                    <select name="choice_attributes[]" id="choice_attributes"
                                        class="form-control aiz-selectpicker" data-live-search="true"
                                        data-selected-text-format="count" multiple>
                                        @foreach (\App\Models\Attribute::all() as $key => $attribute)
                                            <option value="{{ $attribute->id }}" @if(in_array($attribute->id, json_decode($product->attributes) ?: [])) selected @endif>{{ $attribute->getTranslation('name') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="customer_choice_options" id="customer_choice_options">
                                @foreach (json_decode($product->choice_options) ?: [] as $key => $choice_option)
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <input type="hidden" name="choice_no[]" value="{{ $choice_option->attribute_id }}">
                                            <input type="text" class="form-control" name="choice[]" value="{{ \App\Models\Attribute::find($choice_option->attribute_id)->getTranslation('name') }}" placeholder="{{ translate('Choice Title') }}" readonly>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_{{ $choice_option->attribute_id }}[]" multiple>
                                                @foreach (\App\Models\AttributeValue::where('attribute_id', $choice_option->attribute_id)->get() as $row)
                                                    <option value="{{ $row->value }}" @if(in_array($row->value, $choice_option->values)) selected @endif>{{ $row->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product price + stock') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Unit price') }} <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="{{ $product->unit_price }}" step="0.01"
                                        placeholder="{{ translate('Unit price') }}" name="unit_price" class="form-control"
                                        required>
                                </div>
                            </div>

                            @php
                                $date_range = '';
                                if($product->discount_start_date){
                                    $date_range = date('d-m-Y H:i:s', $product->discount_start_date). ' to ' .date('d-m-Y H:i:s', $product->discount_end_date);
                                }
                            @endphp
                            <div class="form-group row">
                                <label class="col-md-3 control-label">{{ translate('Discount Date Range') }} </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control aiz-date-range" name="date_range"
                                        value="{{ $date_range }}"
                                        placeholder="{{ translate('Select Date') }}" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Discount') }} <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="{{ $product->discount }}" step="0.01"
                                        placeholder="{{ translate('Discount') }}" name="discount" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control aiz-selectpicker" name="discount_type">
                                        <option value="amount" @if($product->discount_type == 'amount') selected @endif>{{ translate('Flat') }}</option>
                                        <option value="percent" @if($product->discount_type == 'percent') selected @endif>{{ translate('Percent') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div id="show-hide-div" @if($product->variant_product) style="display:none" @endif>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Quantity') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="{{ optional($product->stocks->first())->qty }}" step="1"
                                            placeholder="{{ translate('Quantity') }}" name="current_stock"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('SKU') }}</label>
                                    <div class="col-md-6">
                                        <input type="text" placeholder="{{ translate('SKU') }}" name="sku"
                                            value="{{ optional($product->stocks->first())->sku }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="sku_combination" id="sku_combination"></div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Description') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                                <div class="col-md-8">
                                    <textarea class="aiz-text-editor" name="description">{{ $product->getTranslation('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('PDF Specification') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('PDF Specification') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="document">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="pdf" value="{{ $product->pdf }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <div class="mt-2">
                                        <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                                        <small class="text-muted">{{ translate('Or upload from your computer') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="meta_title"
                                        value="{{ $product->meta_title }}"
                                        placeholder="{{ translate('Meta Title') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                                <div class="col-md-8">
                                    <textarea name="meta_description" rows="8" class="form-control">{{ $product->meta_description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Meta Image') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="meta_img" value="{{ $product->meta_img }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <div class="mt-2">
                                        <input type="file" name="meta_img_file" class="form-control" accept="image/*">
                                        <small class="text-muted">{{ translate('Or upload from your computer') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Category') }} <span class="text-danger">*</span></h5>
                        </div>
                        <div class="card-body">
                            <div class="h-max-500px overflow-y-auto border p-3">
                                <ul id="treeview" class="hummingbird-treeview-converter list-unstyled" data-checkbox-name="category_ids[]" data-radio-name="category_id">
                                    @foreach ($categories as $category)
                                        <li id="{{ $category->id }}" @if($product->categories->contains($category->id)) data-checked="true" @endif @if($product->category_id == $category->id) data-selected="true" @endif>{{ $category->getTranslation('name') }}
                                            @if(count($category->childrenCategories) > 0)
                                                <ul>
                                                    @foreach ($category->childrenCategories as $childCategory)
                                                        @include('vendors.product.child_category', ['child_category' => $childCategory, 'product' => $product])
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <p class="text-danger" id="refundable-note"></p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Shipping Configuration') }}</h5>
                        </div>
                        <div class="card-body">
                            @if (get_setting('shipping_type') == 'product_wise_shipping')
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{ translate('Free Shipping') }}</label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="shipping_type" value="free" @if($product->shipping_type == 'free') checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{ translate('Flat Rate') }}</label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="shipping_type" value="flat_rate" @if($product->shipping_type == 'flat_rate') checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="flat_rate_shipping_div" @if($product->shipping_type != 'flat_rate') style="display: none" @endif>
                                    <div class="form-group row">
                                        <label class="col-md-6 col-from-label">{{ translate('Shipping cost') }}</label>
                                        <div class="col-md-6">
                                            <input type="number" lang="en" min="0" value="{{ $product->shipping_cost }}" step="0.01" placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{translate('Is Product Quantity Multiplied')}}</label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="is_quantity_multiplied" value="1" @if($product->is_quantity_multiplied) checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            @elseif (get_setting('shipping_type') == 'flat_rate')
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{ translate('Shipping cost') }}</label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="{{ $product->shipping_cost }}" step="0.01" placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost" class="form-control" required>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Refund') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Refundable') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="refundable" value="1" @if($product->refundable) checked @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Cash On Delivery') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="cash_on_delivery" value="1" @if($product->cash_on_delivery) checked @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Estimate Shipping Time') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-2">
                                <label class="col-from-label">{{ translate('Shipping Days') }}</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="est_shipping_days" value="{{ $product->est_shipping_days }}" min="1" step="1" placeholder="{{ translate('Shipping Days') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">{{ translate('Days') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('VAT & Tax') }}</h5>
                        </div>
                        <div class="card-body">
                            @foreach (\App\Models\Tax::where('tax_status', 1)->get() as $tax)
                                @php
                                    $tax_amount = 0;
                                    $tax_type = 'amount';
                                    foreach($product->taxes as $p_tax){
                                        if($p_tax->tax_id == $tax->id){
                                            $tax_amount = $p_tax->tax;
                                            $tax_type = $p_tax->tax_type;
                                        }
                                    }
                                @endphp
                                <label for="name">
                                    {{ $tax->name }}
                                    <input type="hidden" value="{{ $tax->id }}" name="tax_id[]">
                                </label>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="number" lang="en" min="0" value="{{ $tax_amount }}" step="0.01"
                                            placeholder="{{ translate('Tax') }}" name="tax[]" class="form-control"
                                            required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select class="form-control aiz-selectpicker" name="tax_type[]">
                                            <option value="amount" @if($tax_type == 'amount') selected @endif>{{ translate('Flat') }}</option>
                                            <option value="percent" @if($tax_type == 'percent') selected @endif>{{ translate('Percent') }}</option>
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mar-all text-right mb-2">
                        <button type="submit" name="button" value="publish"
                            class="btn btn-primary">{{ translate('Update Product') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('modal')
	<!-- Frequently Bought Product Select Modal -->
    @include('modals.product_select_modal')

    {{-- Note Modal --}}
    @include('modals.note_modal')
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $("#treeview").hummingbird();

        $('#treeview input:checkbox').on("click", function (){
            let $this = $(this);
            if ($this.prop('checked') && ($('#treeview input:radio:checked').length == 0)) {
                let val = $this.val();
                $('#treeview input:radio[value='+val+']').prop('checked',true);
            }
        });
        
        isRefundable();
        update_sku();
    });

    $("[name=shipping_type]").on("change", function() {
        $(".product_wise_shipping_div").hide();
        $(".flat_rate_shipping_div").hide();
        if ($(this).val() == 'product_wise') {
            $(".product_wise_shipping_div").show();
        }
        if ($(this).val() == 'flat_rate') {
            $(".flat_rate_shipping_div").show();
        }
    });

    function add_more_customer_choice_option(i, name) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '{{ route('vendor.products.add-more-choice-option') }}',
            data: {
                attribute_id: i
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('#customer_choice_options').append('\
                    <div class="form-group row">\
                        <div class="col-md-3">\
                            <input type="hidden" name="choice_no[]" value="' + i + '">\
                            <input type="text" class="form-control" name="choice[]" value="' + name +
                    '" placeholder="{{ translate('Choice Title') }}" readonly>\
                        </div>\
                        <div class="col-md-8">\
                            <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_' + i + '[]" multiple>\
                                ' + obj + '\
                            </select>\
                        </div>\
                    </div>');
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });
    }

    $('input[name="colors_active"]').on('change', function() {
        if (!$('input[name="colors_active"]').is(':checked')) {
            $('#colors').prop('disabled', true);
            AIZ.plugins.bootstrapSelect('refresh');
        } else {
            $('#colors').prop('disabled', false);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        update_sku();
    });

    $(document).on("change", ".attribute_choice", function() {
        update_sku();
    });

    $('#colors').on('change', function() {
        update_sku();
    });

    $('input[name="unit_price"]').on('keyup', function() {
        update_sku();
    });

    function update_sku() {
        $.ajax({
            type: "POST",
            url: '{{ route('vendor.products.sku_combination_edit') }}',
            data: $('#choice_form').serialize() + '&id={{ $product->id }}',
            success: function(data) {
                $('#sku_combination').html(data);
                AIZ.uploader.previewGenerate();
                AIZ.plugins.sectionFooTable('#sku_combination');
                if (data.trim().length > 1) {
                    $('#show-hide-div').hide();
                } else {
                    $('#show-hide-div').show();
                }
            }
        });
    }

    $('#choice_attributes').on('change', function() {
        $('#customer_choice_options').html(null);
        $.each($("#choice_attributes option:selected"), function() {
            add_more_customer_choice_option($(this).val(), $(this).text());
        });
        update_sku();
    });

    // Refundable Check
    function isRefundable() {
        const refundType = "{{ get_setting('refund_type') }}";
        const $refundable = $('input[name="refundable"]');
        const $mainCategoryRadio = $('input[name="category_id"]:checked');
        const $note = $('#refundable-note');

        $refundable.off('change.isRefundableLock');

        if (refundType !== 'category_based_refund') {
            $refundable.prop('disabled', false);
            $note.addClass('d-none');
            return;
        }

        if (!$mainCategoryRadio.length) {
            $refundable.prop('checked', false);
            $refundable.prop('disabled', true);
            $note.text('{{ translate("Your refund type is category based. At first select the main category.") }}')
                .removeClass('d-none');
            return;
        }

        const categoryId = $mainCategoryRadio.val();
        $.ajax({
            type: 'POST',
            url: '{{ route("vendor.products.check_refundable_category") }}',
            data: {
                _token: '{{ csrf_token() }}',
                category_id: categoryId
            },
            success: function (response) {
                if (response.status === 'success' && response.is_refundable) {
                    $refundable.prop('disabled', false);
                    $note.text('{{ translate("This product allows refunds.") }}')
                        .removeClass('d-none');
                } else {
                    $refundable.prop('checked', false);
                    $refundable.prop('disabled', true);
                    $note.text('{{ translate("Selected main category has no refund. Select a refundable category.") }}')
                        .removeClass('d-none');
                }
            }
        });
    }

    $(document).on('change', 'input[name="category_id"]', function () {
        isRefundable();
    });

</script>

@include('partials.product.product_temp_data')

@endsection
