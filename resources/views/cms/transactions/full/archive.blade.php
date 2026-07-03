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
            <div class="w-auto relative text-slate-500 mr-2">
                <select id="records_per_page" class="form-control box">
                    <option value="10" {{ request()->get('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->get('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request()->get('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="all" {{ request()->get('perPage') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>
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
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="filter">
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
                        <th class="text-center whitespace-nowrap" width="30%">TOTAL HARGA</th>
                        <th class="text-center whitespace-nowrap">STATUS</th>
                        <th class="text-center whitespace-nowrap">PEMBAYARAN</th>
                        <th class="text-center whitespace-nowrap">PENGIRIMAN</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($orders->isEmpty())
                        <tr>
                            <td colspan="8" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($orders as $order)
                            @php
                                $period = $order->detail->first()?->product?->package?->package?->period;
                                $accessDate = $period->access_date
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
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <a class="text-slate-500 flex items-center mr-3"
                                        href="{{ route('order.full.archive.show', $order) }}"> <i data-lucide="external-link"
                                            class="w-4 h-4 mr-2"></i> {{ $order->order_number }} </a>
                                </td>
                                @hasrole('super_admin|admin')
                                    <td class="text-center capitalize">
                                        {{ $order->agent->agentProfile->name }}
                                    </td>
                                @endhasrole
                                <td class="text-center">
                                    <p>
                                        Rp. {{ number_format($order->total_price, 0, ',', '.') }}
                                    </p>
                                </td>
                                <td>
                                    @if ($order->status === 'accepted')
                                        <div class="flex items-center justify-center text-success"> <i
                                                data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Diterima </div>
                                    @elseif ($order->status === 'stop')
                                        <div class="flex items-center justify-center text-success"> <i
                                                data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Mundur </div>
                                    @elseif ($order->status === 'reject')
                                        <div class="flex items-center justify-center text-danger"> <i
                                                data-lucide="alert-circle" class="w-4 h-4 mr-2"></i> Ditolak </div>
                                    @elseif ($order->status === 'canceled')
                                        <div class="flex items-center justify-center text-danger"> <i data-lucide="x-circle"
                                                class="w-4 h-4 mr-2"></i> Dibatalkan </div>
                                    @else
                                        <div class="flex items-center justify-center text-warning"> <i data-lucide="clock"
                                                class="w-4 h-4 mr-2"></i> Pending</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->payment_status === 'paid')
                                        <div class="flex items-center justify-center text-success"> <i
                                                data-lucide="check-square" class="w-4 h-4 mr-2"></i> Lunas </div>
                                    @elseif ($order->payment_status === 'pending')
                                        <div class="flex items-center justify-center text-warning"> <i data-lucide="clock"
                                                class="w-4 h-4 mr-2"></i> Dicicil </div>
                                    @else
                                        <div class="flex items-center justify-center text-danger"> <i data-lucide="x-square"
                                                class="w-4 h-4 mr-2"></i> Belum Dibayar</div>
                                    @endif
                                    @if ($order->payment_status !== 'paid' && $accessDate !== null)
                                        @if ($accessDate < 0)
                                            <span class="text-danger text-sm capitalize">lebih {{ abs($accessDate) }}
                                                hari</span>
                                        @elseif ($accessDate <= 30)
                                            <span class="text-warning text-sm capitalize">{{ $accessDate }} hari
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

            document.getElementById('records_per_page').addEventListener('change', function() {
                const perPage = this.value;
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('perPage', perPage);
                window.location.search = urlParams.toString();
            });
            document.getElementById('records_per_status').addEventListener('change', function() {
                const status = this.value;
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('status', status);
                window.location.search = urlParams.toString();
            });
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
