<li id="{{ $child_category->id }}" @if(isset($product) && $product->categories->contains($child_category->id)) data-checked="true" @endif @if(isset($product) && $product->category_id == $child_category->id) data-selected="true" @endif>{{ $child_category->getTranslation('name') }}
@if(count($child_category->childrenCategories) > 0)
    <ul>
        @foreach($child_category->childrenCategories as $child)
            @include('vendors.product.child_category', ['child_category' => $child, 'product' => $product ?? null])
        @endforeach
    </ul>
@endif
</li>
