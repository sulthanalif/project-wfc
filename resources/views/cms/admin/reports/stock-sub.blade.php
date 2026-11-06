@extends('cms.layouts.app', [
    'title' => 'Laporan Total Stok Sub Produk',
])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Laporan Total Deposit -->
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Laporan Total Stok Sub Produk
                        </h2>
                    </div>
                </div>
                <!-- END: Laporan Total Deposit -->
            </div>
        </div>

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
            @include('cms.layouts.per-page')
            <a href="{{ route('stockSubProduct', array_merge(request()->except('page'), ['export' => 1])) }}"
                class="btn btn-primary shadow-md mr-2"> <i data-lucide="file"
                    class="w-4 h-4 mr-3"></i> Export </a>
            <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0 ml-auto">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Cari di halaman ini..." title="Hanya mencari data pada halaman yang sedang aktif" id="filter" value="{{ request('search') }}">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>
        <x-table.filter :filters="$filters" />
        <!-- BEGIN: Data List -->
        <x-table.wrapper>
            <x-slot:thead>
                <tr>
                    <th class="text-center whitespace-nowrap">#</th>
                    <th class="text-center whitespace-nowrap">ITEM</th>
                    <th class="text-center whitespace-nowrap">SATUAN</th>
                    <th class="text-center whitespace-nowrap">KEBUTUHAN</th>
                    <th class="text-center whitespace-nowrap">PENGADAAN</th>
                    <th class="text-center whitespace-nowrap">TERDISTRIBUSI</th>
                    <th class="text-center whitespace-nowrap">JUMLAH STOK</th>
                </tr>
            </x-slot:thead>
            @if (!isset($products) || (is_array($products) && empty($products)) || (is_object($products) && $products->isEmpty()))
                <x-table.empty colspan="7" />
            @else
                @foreach ($products as $data)
                    <tr class="intro-x">
                        <td>
                            <p class="font-medium whitespace-nowrap text-center">{{ $products instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($products->firstItem() + $loop->index) : $loop->iteration }}</p>
                        </td>
                        <td>
                            <span class="text-slate-500">{{ $data['name'] }}</span>
                        </td>
                        <td align='center'>
                            <span class="text-slate-500">{{ $data['unit'] }}</span>
                        </td>
                        <td align='center'>
                            <span class="text-slate-500">{{ $data['needed'] ?? 0 }}</span>
                        </td>
                        <td align='center'>
                            <span class="text-slate-500">{{ $data['procurement'] ?? 0 }}</span>
                        </td>
                        <td align='center'>
                            <span class="text-slate-500">{{ $data['distributed'] ?? 0 }}</span>
                        </td>
                        <td align='center'>
                            <span class="text-slate-500">{{ $data['stock'] ?? 0 }}</span>
                        </td>
                    </tr>
                @endforeach
            @endif
        </x-table.wrapper>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <x-table.pagination :collection="$products" />
        <!-- END: Pagination -->
    </div>
@endsection
