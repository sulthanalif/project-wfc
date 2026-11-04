@props([
    'collection' => null,
    'param' => 'perPage',
    'searchPlaceholder' => 'Cari di halaman ini...',
    'searchTitle' => 'Pencarian data di server',
])

<div {{ $attributes->merge(['class' => 'intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2']) }}>
    @if(isset($actions))
        {{ $actions }}
    @endif

    @include('cms.layouts.per-page', ['param' => $param])

    @if(isset($filters))
        {{ $filters }}
    @endif

    @if(isset($customMiddle))
        {{ $customMiddle }}
    @else
        @if($collection instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="hidden md:block mx-auto text-slate-500">
                Menampilkan {{ $collection->firstItem() ?? 0 }} hingga {{ $collection->lastItem() ?? 0 }} dari {{ $collection->total() }} data
            </div>
        @elseif($collection)
            <div class="hidden md:block mx-auto text-slate-500">
                Menampilkan semua {{ is_countable($collection) ? count($collection) : 0 }} data
            </div>
        @endif
    @endif

    <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
        <div class="w-56 relative text-slate-500">
            <input type="text" class="form-control w-56 box pr-10" placeholder="{{ $searchPlaceholder }}" title="{{ $searchTitle }}" id="filter">
            <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 cursor-pointer" data-lucide="search" title="Cari di database (Tekan Enter atau tunggu 500ms)"></i>
        </div>
        @if(isset($rightActions))
            {{ $rightActions }}
        @endif
    </div>
</div>
