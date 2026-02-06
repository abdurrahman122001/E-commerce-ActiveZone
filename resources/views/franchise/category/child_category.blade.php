<option value="{{ $child_category->id }}">
    @for ($i = 0; $i < $child_category->level; $i++)
        --
    @endfor
    {{ $child_category->getTranslation('name') }}
</option>
@if ($child_category->categories)
    @foreach ($child_category->categories as $childCategory)
        @include('franchise.category.child_category', ['child_category' => $childCategory])
    @endforeach
@endif
