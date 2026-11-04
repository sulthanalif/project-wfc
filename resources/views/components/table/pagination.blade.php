@props([
    'collection' => null,
])

@if ($collection instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div {{ $attributes->merge(['class' => 'intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center']) }}>
        {{ $collection->links('cms.layouts.paginate') }}
    </div>
@endif
