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
                    <a href="{{ route('payment.index', ['export' => 'true']) }}" class="btn btn-sm btn-primary"><i class="w-4 h-4" data-lucide="download"></i> Export</a>
                @endhasrole
            </div>
        </div>
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="w-auto relative text-slate-500">
                <select id="records_per_page" class="form-control box">
                    <option value="10" {{ request()->get('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->get('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request()->get('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="all" {{ request()->get('perPage') == 'all' ? 'selected' : '' }}>All</option>
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
                        <th class="text-center whitespace-nowrap">#</th>
                        <th class="text-center whitespace-nowrap">NAMA AGEN</th>
                        <th class="text-center whitespace-nowrap" width="30%">TOTAL HARGA</th>
                        <th class="text-center whitespace-nowrap">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($orders->isEmpty())
                        <tr>
                            <td colspan="4" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($orders as $agentId => $order)
                            <tr class="intro-x">
                                @php
                                    $totalPrice = $order->sum('total_price');

                                    $allPaid = $order->every(function ($o) {
                                        return $o->payment_status === 'paid';
                                    });
                                    $hasPending = $order->contains(function ($o) {
                                        return $o->payment_status === 'pending';
                                    });
                                @endphp
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <a class="text-slate-500 flex items-center mr-3" href="{{ route('payment.show', ['user' => $order->first()->agent_id])}}"> <i
                                            data-lucide="external-link" class="w-4 h-4 mr-2"></i>
                                        {{ $order->first()->agent->agentProfile->name }} </a>
                                </td>
                                <td class="text-center">
                                    <p>
                                        Rp. {{ number_format($totalPrice, 0, ',', '.') }}
                                    </p>
                                </td>
                                <td>
                                    @if ($allPaid)
                                        <div class="flex items-center justify-center text-success"> <i
                                                data-lucide="check-square" class="w-4 h-4 mr-2"></i> Lunas </div>
                                    @elseif ($hasPending)
                                        <div class="flex items-center justify-center text-warning"> <i data-lucide="clock"
                                                class="w-4 h-4 mr-2"></i> Dicicil </div>
                                    @else
                                        <div class="flex items-center justify-center text-danger"> <i data-lucide="x-square"
                                                class="w-4 h-4 mr-2"></i> Belum Dibayar</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->

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
            document.getElementById('records_per_page').addEventListener('change', function() {
                const perPage = this.value;
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('perPage', perPage);
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
