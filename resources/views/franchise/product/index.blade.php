@extends('franchise.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Products') }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('franchise.products.create') }}" class="btn btn-primary">
                <span>{{ translate('Add New Product') }}</span>
            </a>
        </div>
      </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('All Products') }}</h5>
            <div class="pull-right">
                <form action="" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="{{ translate('Search Product') }}" value="{{ $search }}">
                        <div class="input-group-append">
                            <button class="btn btn-light" type="submit">{{ translate('Search') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>{{ translate('Name') }}</th>
                        <th data-breakpoints="lg">{{ translate('Category') }}</th>
                        <th data-breakpoints="lg">{{ translate('Base Price') }}</th>
                        <th data-breakpoints="lg">{{ translate('Published') }}</th>
                        <th data-breakpoints="lg" class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $key => $product)
                        <tr>
                            <td>{{ ($key+1) + ($products->currentPage() - 1)*$products->perPage() }}</td>
                            <td>
                                <div class="row gutters-5 w-200px w-md-300px aiz-ellipsis">
                                    <div class="col-auto">
                                        <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="Image" class="size-50px img-fit">
                                    </div>
                                    <div class="col">
                                        <span class="text-muted text-truncate-2">{{ $product->getTranslation('name') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $product->category ? $product->category->getTranslation('name') : '' }}
                            </td>
                            <td>{{ $product->unit_price }}</td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" onchange="update_published(this)" value="{{ $product->id }}" <?php if($product->published == 1) echo "checked";?>>
                                    <span></span>
                                </label>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('franchise.products.edit', $product->id) }}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('franchise.products.destroy', $product->id) }}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
