@extends('cms.layouts.app', [
    'title' => 'Arsip Pesanan Paket Titik Aman',
])

@section('content')
    <div class="grid grid-cols-12 mt-12">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap">
            <h2 class="w-auto relative text-lg font-medium">
                Arsip Pesanan Paket Titik Aman
            </h2>
            <div class="w-full xl:w-auto flex items-center mt-1 lg:mt-0 ml-auto gap-2">
                <a href="{{ route('order.aman.index') }}" class="btn btn-sm btn-secondary mr-1">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="arrow-left"></i>
                    </span>
                </a>
                @hasrole('admin|super_admin')
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'true']) }}" class="btn btn-sm btn-primary"><i class="w-4 h-4"
                            data-lucide="download"></i> Export</a>
                @endhasrole
                @hasrole('admin|super_admin')
                    <a href="{{ route('countAll') }}" class="btn btn-sm btn-primary">Hitung Ulang Pesanan</a>
                @endhasrole
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @hasrole('super_admin|admin|agent')
                <a href="{{ route('order.aman.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Pesanan</a>
            @endhasrole
            @include('cms.layouts.per-page')

            @if ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $orders->firstItem() }} hingga
                    {{ $orders->lastItem() }} dari {{ $orders->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ $orders->count() }} data
                </div>
            @endif
            <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Cari di halaman ini..." title="Hanya mencari data pada halaman yang sedang aktif" id="filter">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>

            </div>
        </div>
        <x-table.filter :filters="$filters" />
        <!-- BEGIN: Data List -->
        <x-table.wrapper>
            <x-slot:header>
                <tr>
                    <th class="text-center whitespace-nowrap">#</th>
                    <th class="text-center whitespace-nowrap">NOMOR PESANAN</th>
                    @hasrole('super_admin|admin')
                        <th class="text-center whitespace-nowrap">DARI AGEN</th>
                    @endhasrole
                    <th class="text-center whitespace-nowrap">TOTAL HARGA</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">PEMBAYARAN</th>
                    <th class="text-center whitespace-nowrap">PENGIRIMAN</th>
                </tr>
            </x-slot:header>

            @if ($orders->isEmpty())
                <x-table.empty colspan="8" message="Belum Ada Data Arsip Pesanan Aman" />
            @else
                @foreach ($orders as $order)
                    @php
                        $period = $order->detail->first()?->product?->package?->package?->period;
                        $accessDate = $period?->access_date
                            ? now()->diffInDays(\Carbon\Carbon::parse($period->access_date), false)
                            : null;
                    @endphp
                    <tr class="intro-x">
                        <td>
                            <p class="font-medium whitespace-nowrap text-center">{{ $orders instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($orders->firstItem() + $loop->index) : $loop->iteration }}</p>
                        </td>
                        <td>
                            <a class="text-slate-500 flex items-center mr-3 hover:text-primary transition-colors"
                                href="{{ route('order.aman.archive.show', $order) }}"> <i data-lucide="external-link"
                                    class="w-4 h-4 mr-2"></i> {{ $order->order_number }} </a>
                        </td>
                        @hasrole('super_admin|admin')
                            <td class="text-center capitalize">
                                {{ $order->agent?->agentProfile?->name ?? $order->agent?->name ?? 'N/A' }}
                            </td>
                        @endhasrole
                        <td class="text-center font-medium">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <x-badge.status :status="$order->status" />
                        </td>
                        <td class="text-center">
                            <x-badge.status type="payment" :status="$order->payment_status" />
                            @if ($order->payment_status !== 'paid' && $accessDate !== null)
                                <div class="mt-1">
                                    @if ($accessDate < 0)
                                        <span class="text-danger text-xs font-medium">lewat {{ abs($accessDate) }} hari</span>
                                    @elseif ($accessDate <= 30)
                                        <span class="text-warning text-xs font-medium">{{ $accessDate }} hari lagi</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <x-badge.status :status="$order->isAllItemDistributed() ? 'delivered' : ($order->distributions->isNotEmpty() ? 'shipping' : 'undelivered')" />
                        </td>
                    </tr>
                @endforeach
            @endif
        </x-table.wrapper>
        <!-- END: Data List -->

        <!-- BEGIN: Pagination -->
        <x-table.pagination :collection="$orders" />
        <!-- END: Pagination -->
    </div>
@endsection

@push('custom-scripts')
    <script>
        const modals = document.querySelectorAll('.modal');

        modals.forEach(modal => {
            const statSelect = modal.querySelector('#status');

            if (statSelect) {
                statSelect.addEventListener('change', (event) => {
                    const descField = modal.querySelector('#desc-fields');
                    if (event.target.value === 'reject') {
                        descField.style.display = 'block';
                    } else {
                        descField.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endpush