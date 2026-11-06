@extends('cms.layouts.app', [
    'title' => 'Pembayaran Paket',
])

@section('content')
    <h2 class="grid grid-cols-12 mt-12">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap">
            <h2 class="w-auto relative text-lg font-medium">
                Pembayaran Paket
            </h2>
            <div class="w-full xl:w-auto flex items-center mt-1 lg:mt-0 ml-auto gap-2">
                @hasrole('admin|super_admin')
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'true']) }}" class="btn btn-sm btn-primary"><i class="w-4 h-4"
                            data-lucide="download"></i> Export</a>
                @endhasrole
            </div>
        </div>
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
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
                    <th class="text-center whitespace-nowrap">NAMA AGEN</th>
                    <th class="text-center whitespace-nowrap">TOTAL TAGIHAN</th>
                    <th class="text-center whitespace-nowrap">TOTAL PEMBAYARAN</th>
                    <th class="text-center whitespace-nowrap">SISA PEMBAYARAN</th>
                    <th class="text-center whitespace-nowrap">PERSENTASE</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                </tr>
            </x-slot:header>

            @if ($orders->isEmpty())
                <x-table.empty colspan="7" message="Belum Ada Data Pembayaran" />
            @else
                @foreach ($orders as $agent)
                    <tr class="intro-x">
                        @php
                            $order = $agent instanceof \App\Models\User ? $agent->order : $agent;
                            $totalPrice = $order->sum('total_price');
                            $totalPayment = $order->sum(function ($o) {
                                return $o->paid_total ?? $o->payment()->where('status', 'accepted')->sum('pay');
                            });
                            $remainingPayment = $totalPrice - $totalPayment;

                            $allPaid = $order->isNotEmpty() && $order->every(function ($o) {
                                return $o->payment_status === 'paid';
                            });
                            $hasPending = $order->contains(function ($o) {
                                return $o->payment_status === 'pending';
                            });
                            $firstDetail = $order->first()?->detail?->first();
                            $period = $firstDetail?->product?->package?->package?->period;
                            $accessDate = $period?->access_date
                                ? now()->diffInDays(\Carbon\Carbon::parse($period->access_date), false)
                                : null;
                            $agentId = $agent instanceof \App\Models\User ? $agent->id : $order->first()?->agent_id;
                            $agentName = $agent instanceof \App\Models\User ? ($agent->agentProfile->name ?? $agent->name ?? $agent->email) : ($order->first()?->agent->agentProfile->name ?? 'N/A');
                        @endphp
                        <td>
                            <p class="font-medium whitespace-nowrap text-center">{{ $orders instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($orders->firstItem() + $loop->index) : $loop->iteration }}</p>
                        </td>
                        <td>
                            <a class="text-slate-500 flex items-center mr-3 hover:text-primary transition-colors"
                                href="{{ route('payment.show', ['user' => $agentId]) }}"> <i
                                    data-lucide="external-link" class="w-4 h-4 mr-2"></i>
                                {{ $agentName }} </a>
                        </td>
                        <td class="text-center font-medium">
                            Rp {{ number_format($totalPrice, 0, ',', '.') }}
                        </td>
                        <td class="text-center font-medium">
                            Rp {{ number_format($totalPayment, 0, ',', '.') }}
                        </td>
                        <td class="text-center font-medium">
                            Rp {{ number_format($remainingPayment, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700">
                                {{ $totalPrice > 0 ? number_format(($totalPayment / $totalPrice) * 100, 2) : 0 }}%
                            </span>
                        </td>
                        <td class="text-center">
                            <x-badge.status type="payment" :status="$allPaid ? 'paid' : ($hasPending ? 'pending' : 'unpaid')" />
                            @if ($order->contains(function ($item) { return $item->payment_status !== 'paid'; }) && $accessDate !== null)
                                <div class="mt-1">
                                    @if ($accessDate < 0)
                                        <span class="text-danger text-xs font-medium">lewat {{ abs($accessDate) }} hari</span>
                                    @elseif ($accessDate <= 30)
                                        <span class="text-warning text-xs font-medium">{{ $accessDate }} hari lagi</span>
                                    @endif
                                </div>
                            @endif
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
