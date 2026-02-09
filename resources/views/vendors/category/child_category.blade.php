<option value="{{ $child_category->id }}">{{ str_repeat('--', $child_category->level) }} {{ $child_category->getTranslation('name') }}</option>
@if (count($child_category->childrenCategories) > 0)
    @foreach ($child_category->childrenCategories as $child)
        @include('vendors.category.child_category', ['child_category' => $child])
    @endforeach
@endif
