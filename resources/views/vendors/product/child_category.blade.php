<li id="{{ $child_category->id }}">{{ $child_category->getTranslation('name') }}</li>
@if(count($child_category->childrenCategories) > 0)
    <ul>
        @foreach($child_category->childrenCategories as $child)
            @include('vendors.product.child_category', ['child_category' => $child])
        @endforeach
    </ul>
@endif
