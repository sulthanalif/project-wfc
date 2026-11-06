@extends('cms.layouts.app', [
    'title' => 'Daftar Agen Rincian Perpaket',
])

@section('content')
    <h2 class="grid grid-cols-12 mt-12">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap">
            <h2 class="w-auto relative text-lg font-medium">
                Daftar Agen Rincian Perpaket #{{ $product->first()->name ?? '-' }}
            </h2>
            <div class="w-full xl:w-auto flex items-center mt-1 lg:mt-0 ml-auto gap-2">
                <a href="{{ route('rproductDetail') }}" class="btn px-2 box"><i data-lucide="arrow-left"
                        class="w-4 h-4"></i></a>
            </div>
        </div>
    </h2>
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
        @include('cms.layouts.per-page')
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <x-table.filter :filters="$filters" />
        <!-- BEGIN: Data List -->
        <x-table.wrapper>
            <x-slot:header>
                <tr>
                    <th class="text-center whitespace-nowrap">#</th>
                    <th class="text-center whitespace-nowrap">NAMA AGEN</th>
                    <th class="text-center whitespace-nowrap">TOTAL PESANAN</th>
                </tr>
            </x-slot:header>
            @if ($orders->isEmpty())
                <x-table.empty colspan="3" />
            @else
                @foreach ($orders as $order)
                    <tr class="intro-x">
                        <td>
                            <p class="font-medium whitespace-nowrap text-center">{{ $orders instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($orders->firstItem() + $loop->index) : $loop->iteration }}</p>
                        </td>
                        <td>
                            <p class="text-slate-500 items-center">
                                {{ $order->agent->agentProfile->name ?? '-' }} </p>
                        </td>
                        <td>
                            <p class="text-slate-500 text-center">{{ $order->totalProduct }}</p>
                        </td>
                    </tr>
                @endforeach
            @endif
            <x-slot:footer>
                <tr>
                    <th colspan="2" class="text-center">TOTAL PESANAN PAKET</th>
                    <th class="text-center">
                        {{ $totalProductCount ?? $orders->sum('totalProduct') }}
                    </th>
                </tr>
            </x-slot:footer>
        </x-table.wrapper>
        <!-- END: Data List -->

        <!-- BEGIN: Pagination -->
        <x-table.pagination :collection="$orders" />
        <!-- END: Pagination -->
    </div>
@endsection
