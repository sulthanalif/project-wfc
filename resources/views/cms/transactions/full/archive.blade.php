@extends('cms.layouts.app', [
    'title' => 'Arsip Pesanan Paket Full',
])

@section('content')
    <div class="grid grid-cols-12 mt-12">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap">
            <h2 class="w-auto relative text-lg font-medium">
                Arsip Pesanan Paket Full
            </h2>
            <div class="w-full xl:w-auto flex items-center mt-1 lg:mt-0 ml-auto gap-2">
                <a href="{{ route('order.full.index') }}" class="btn btn-sm btn-secondary mr-1">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="arrow-left"></i>
                    </span>
                </a>
                @hasrole('admin|super_admin')
                    <a href="{{ route('order.full.index', ['export' => 'true']) }}" class="btn btn-sm btn-primary"><i
                            class="w-4 h-4" data-lucide="download"></i> Export</a>
                @endhasrole
                @hasrole('admin|super_admin')
                    <a href="{{ route('countAll') }}" class="btn btn-sm btn-primary">Hitung Ulang Pesanan</a>
                @endhasrole
                @hasrole('admin|super_admin')
                    <button type="button" id="bulkConvertBtn" class="btn btn-sm btn-warning hidden">Ubah ke Titik Aman</button>
                @endhasrole
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @include('cms.layouts.per-page')
            <div class="w-auto relative text-slate-500 mt-2 lg:mt-0">
                <select id="records_per_status" class="form-control box">
                    <option value="all" {{ request()->get('status') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="accepted" {{ request()->get('status') == 'accepted' ? 'selected' : '' }}>Diterima
                    </option>
                    <option value="stop" {{ request()->get('status') == 'stop' ? 'selected' : '' }}>Mundur</option>
                    <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reject" {{ request()->get('status') == 'reject' ? 'selected' : '' }}>Ditolak</option>
                    <option value="canceled" {{ request()->get('status') == 'canceled' ? 'selected' : '' }}>Dibatalkan
                    </option>
                </select>
            </div>

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
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        @hasrole('admin|super_admin')
                            <th class="text-center whitespace-nowrap"><input type="checkbox" id="selectAllOrders"></th>
                        @endhasrole
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
                </thead>
                <tbody>
                    @if ($orders->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="inbox" class="w-10 h-10 text-slate-300 mb-2"></i>
                                    <span class="font-medium text-base">Belum Ada Data Arsip Pesanan</span>
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach ($orders as $order)
                            @php
                                $period = $order->detail->first()?->product?->package?->package?->period;
                                $accessDate = $period?->access_date
                                    ? now()->diffInDays(\Carbon\Carbon::parse($period->access_date), false)
                                    : null;
                            @endphp
                            <tr class="intro-x">
                                @hasrole('admin|super_admin')
                                    <td class="text-center">
                                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                    </td>
                                @endhasrole
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $orders instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($orders->firstItem() + $loop->index) : $loop->iteration }}</p>
                                </td>
                                <td>
                                    <a class="text-slate-500 flex items-center mr-3 hover:text-primary transition-colors"
                                        href="{{ route('order.full.archive.show', $order) }}"> <i data-lucide="external-link"
                                            class="w-4 h-4 mr-2"></i> {{ $order->order_number }} </a>
                                </td>
                                @hasrole('super_admin|admin')
                                    <td class="text-center capitalize">
                                        {{ $order->agent->agentProfile->name }}
                                    </td>
                                @endhasrole
                                <td class="text-center font-medium">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if ($order->status === 'accepted')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-success/10 text-success border border-success/20">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5 mr-1.5"></i> Diterima
                                        </span>
                                    @elseif ($order->status === 'stop')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                                            <i data-lucide="pause-circle" class="w-3.5 h-3.5 mr-1.5"></i> Mundur
                                        </span>
                                    @elseif ($order->status === 'reject')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-danger/10 text-danger border border-danger/20">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 mr-1.5"></i> Ditolak
                                        </span>
                                    @elseif ($order->status === 'canceled')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                            <i data-lucide="x-circle" class="w-3.5 h-3.5 mr-1.5"></i> Dibatalkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-warning/10 text-warning border border-warning/20">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 mr-1.5"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($order->payment_status === 'paid')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-success/10 text-success border border-success/20">
                                            <i data-lucide="check-square" class="w-3.5 h-3.5 mr-1.5"></i> Lunas
                                        </span>
                                    @elseif ($order->payment_status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-warning/10 text-warning border border-warning/20">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 mr-1.5"></i> Dicicil
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-danger/10 text-danger border border-danger/20">
                                            <i data-lucide="x-square" class="w-3.5 h-3.5 mr-1.5"></i> Belum Dibayar
                                        </span>
                                    @endif
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
                                    @if ($order->isAllItemDistributed())
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-success/10 text-success border border-success/20">
                                            <i data-lucide="truck" class="w-3.5 h-3.5 mr-1.5"></i> Sukses
                                        </span>
                                    @elseif ($order->distributions->isNotEmpty())
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-warning/10 text-warning border border-warning/20">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 mr-1.5"></i> Sedang Proses
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                            <i data-lucide="package" class="w-3.5 h-3.5 mr-1.5"></i> Belum Dikirim
                                        </span>
                                    @endif
                                </td>
                                                lagi</span>
                                        @endif
                                    @endif
                                </td>
                                <td>

                                    @if ($order->isAllItemDistributed())
                                        <div class="flex items-center justify-center text-success"> <i
                                                data-lucide="check-square" class="w-4 h-4 mr-2"></i> Sukses </div>
                                    @else
                                        @if ($order->distributions->isNotEmpty())
                                            <div class="flex items-center justify-center text-warning"> <i
                                                    data-lucide="clock" class="w-4 h-4 mr-2"></i> Sedang Proses </div>
                                        @else
                                            <div class="flex items-center justify-center text-primary"> <i
                                                    data-lucide="clock" class="w-4 h-4 mr-2"></i> Belum Dikirim </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->

        @hasrole('admin|super_admin')
            <form id="bulkConvertForm" action="{{ route('order.bulkConvertToSafePoint') }}" method="post" class="hidden">
                @csrf
                <input type="hidden" name="order_ids" id="orderIdsInput">
            </form>
        @endhasrole

        <!-- BEGIN: Pagination -->
        @if ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                {{ $orders->links('cms.layouts.paginate') }}
            </div>
        @endif
        <!-- END: Pagination -->
    </div>
@endsection

@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAllOrders');
            const bulkBtn = document.getElementById('bulkConvertBtn');
            const checkboxes = document.querySelectorAll('.order-checkbox');
            const form = document.getElementById('bulkConvertForm');
            const orderIdsInput = document.getElementById('orderIdsInput');

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    toggleBulkButton();
                });
            }

            checkboxes.forEach(cb => cb.addEventListener('change', toggleBulkButton));

            function toggleBulkButton() {
                const selected = Array.from(checkboxes).some(cb => cb.checked);
                bulkBtn.classList.toggle('hidden', !selected);
            }

            if (bulkBtn) {
                bulkBtn.addEventListener('click', function() {
                    const selectedIds = Array.from(checkboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);

                    if (!selectedIds.length) {
                        alert('Pilih minimal satu pesanan terlebih dahulu.');
                        return;
                    }

                    orderIdsInput.value = selectedIds.join(',');
                    form.submit();
                });
            }

            });

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
