@extends('vendors.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Add Your Product') }}</h1>
            </div>
        </div>
    </div>

    <form class="" action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-8">
                @csrf
                <input type="hidden" name="added_by" value="seller">
            
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Product Name') }} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" placeholder="{{ translate('Product Name') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Unit') }} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="unit" placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Minimum Purchase Qty') }} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" lang="en" class="form-control" name="min_qty" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Tags')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control aiz-tag-input" name="tags[]" placeholder="{{ translate('Type and hit enter to add a tag') }}">
                                <small class="text-muted">{{translate('This is used for search. Input those words by which cutomer can find this product.')}}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Images') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Thumbnail Image') }}</label>
                            <div class="col-md-8">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="thumbnail_img_file" id="thumbnail_img_file" accept="image/*">
                                    <label class="custom-file-label" for="thumbnail_img_file">{{ translate('Choose file') }}</label>
                                </div>
                                <div class="thumbnail_img_file-preview-container mt-3" style="display: none">
                                    <img class="img-fluid rounded shadow-sm thumbnail_img_file-preview-img" 
                                         src="" 
                                         style="max-height: 200px; border: 1px solid #ddd; background: #f8f9fa; padding: 5px;">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Gallery Images') }}</label>
                            <div class="col-md-8">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="photos_file[]" id="photos_file" accept="image/*" multiple>
                                    <label class="custom-file-label" for="photos_file">{{ translate('Choose files') }}</label>
                                </div>
                                <div class="photos_file-preview-container mt-3 d-flex flex-wrap">
                                </div>
                            </div>
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
                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Unit price') }}" name="unit_price" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Quantity') }} <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="1" placeholder="{{ translate('Quantity') }}" name="current_stock" class="form-control" required>
                            </div>
                        </div>
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
                                <textarea class="aiz-text-editor" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Category') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="h-300px overflow-auto c-scrollbar-light">
                            <ul class="hummingbird-treeview-converter list-unstyled" data-checkbox-name="category_ids[]" data-radio-name="category_id">
                                @foreach ($categories as $category)
                                <li id="{{ $category->id }}">{{ $category->getTranslation('name') }}</li>
                                    @foreach ($category->childrenCategories as $childCategory)
                                        @include('vendors.product.child_category', ['child_category' => $childCategory])
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Publish Status') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{ translate('Published') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="published" value="1" checked>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group" role="group" aria-label="Second group">
                        <button type="submit" name="button" value="publish" class="btn btn-success action-btn">{{ translate('Save & Publish') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            AIZ.plugins.tagify();
        });

        $(document).on('change', '.custom-file-input', function() {
            var input = $(this);
            var files = this.files;
            var label = input.siblings('.custom-file-label');
            
            if (files.length > 0) {
                if(input.attr('multiple')) {
                    label.html(files.length + ' {{ translate("files selected") }}');
                } else {
                    label.html(files[0].name);
                }
                
                // For thumbnail (single)
                if (input.attr('id') == 'thumbnail_img_file' && files[0].type.match('image.*')) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var container = input.closest('.col-md-8').find('.thumbnail_img_file-preview-container');
                        container.find('.thumbnail_img_file-preview-img').attr('src', e.target.result);
                        container.show();
                    }
                    reader.readAsDataURL(files[0]);
                }
                
                // For gallery (multiple)
                if (input.attr('id') == 'photos_file') {
                    var container = input.closest('.col-md-8').find('.photos_file-preview-container');
                    container.html(''); // Clear previous previews
                    $.each(files, function(i, file) {
                        if (file.type.match('image.*')) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                container.append('<div class="mr-2 mb-2"><img class="img-fluid rounded shadow-sm" src="' + e.target.result + '" style="max-height: 100px; border: 1px solid #ddd; background: #f8f9fa; padding: 5px;"></div>');
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                }
            }
        });
    </script>
@endsection
