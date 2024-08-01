@extends('cms.layouts.app', [
    'title' => 'Pesanan Paket',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Pesanan Paket
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @hasrole('super_admin|admin|agent')
                <a href="{{ route('order.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Pesanan</a>
                @hasrole('admin|super_admin')
                <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#access-date-modal"
                    class="dropdown-toggle btn px-2 box">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="settings"></i>
                    </span>
                </a>
                
                <!-- BEGIN: Access Confirmation Modal -->
                <div id="access-date-modal" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="p-5">
                                    <form action="{{ route('access-date.update') }}" method="post">
                                        @csrf
                                        <div class="mt-3">
                                            <label class="form-label" for="date">Atur Batas Akses Ubah Produk <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" id="date" name="date" class="form-control"
                                                value="{{ old('date', $access_date->date ?? date('Y-m-d')) }}">
                                        </div>

                                        <div class="px-5 mt-3 pb-8 text-center">
                                            <button type="submit" class="btn btn-primary w-24">Simpan</button>
                                            <button type="button" data-tw-dismiss="modal"
                                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Access Confirmation Modal -->
                @endhasrole

                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $orders->firstItem() }} hingga
                    {{ $orders->lastItem() }} dari {{ $orders->total() }} data</div>
            @endhasrole
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
                        <th class="text-center whitespace-nowrap">NOMOR PESANAN</th>
                        @hasrole('super_admin|admin')
                            <th class="text-center whitespace-nowrap">DARI AGEN</th>
                        @endhasrole
                        <th class="text-center whitespace-nowrap" width="30%">TOTAL HARGA</th>
                        <th class="text-center whitespace-nowrap">STATUS</th>
                        <th class="text-center whitespace-nowrap">PEMBAYARAN</th>
                        <th class="text-center whitespace-nowrap">PENGIRIMAN</th>
                        @hasrole('super_admin|admin')
                            <th class="text-center whitespace-nowrap">AKSI</th>
                        @endhasrole
                    </tr>
                </thead>
                <tbody>
                    @if ($orders->isEmpty())
                        <tr>
                            <td colspan="8" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($orders as $order)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <a class="text-slate-500 flex items-center mr-3"
                                        href="{{ route('order.show', $order) }}"> <i data-lucide="external-link"
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
                                </td>
                                <td>
                                    @if ($order->delivery_status === 'success')
                                        <div class="flex items-center justify-center text-success"> <i
                                                data-lucide="check-square" class="w-4 h-4 mr-2"></i> Diantarkan </div>
                                    @else
                                        <div class="flex items-center justify-center text-warning"> <i data-lucide="clock"
                                                class="w-4 h-4 mr-2"></i> Belum Diantarkan </div>
                                    @endif
                                </td>
                                @hasrole('super_admin|admin')
                                    @if ($order->payment_status == 'paid')
                                        <td class="table-report__action w-56">
                                            <div class="flex items-center justify-center text-success"> <i
                                                    data-lucide="check-square" class="w-4 h-4 mr-2"></i> </div>
                                        </td>
                                    @else
                                        <td class="table-report__action w-50">
                                            <div class="flex justify-center items-center">
                                                <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal"
                                                    data-tw-target="#change-confirmation-modal{{ $order->id }}">
                                                    <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah Status </a>
                                            </div>
                                        </td>
                                    @endif
                                @endhasrole
                            </tr>

                            <!-- BEGIN: Change Confirmation Modal -->
                            <div id="change-confirmation-modal{{ $order->id }}" class="modal" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body p-0">
                                            <div class="p-5 text-center">
                                                <i data-lucide="alert-circle"
                                                    class="w-16 h-16 text-warning mx-auto mt-3"></i>
                                                <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                                <div class="text-slate-500 mt-2">
                                                    Apakah anda yakin untuk mengubah status pesanan ini?
                                                    <br>
                                                    Proses tidak akan bisa diulangi.
                                                </div>
                                            </div>
                                            <div class="px-5 pb-8">
                                                <form action="{{ route('order.changeOrderStatus', $order) }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <select class="form-select mt-2 sm:mr-2" id="status"
                                                            name="status" required>
                                                            <option value="accepted"
                                                                {{ $order->status == 'accepted' ? 'selected' : '' }}>
                                                                Diterima</option>
                                                            <option value="stop"
                                                                {{ $order->status == 'stop' ? 'selected' : '' }}>Mundur
                                                            </option>
                                                            <option value="reject"
                                                                {{ $order->status == 'reject' ? 'selected' : '' }}>Ditolak
                                                            </option>
                                                            <option value="canceled"
                                                                {{ $order->status == 'canceled' ? 'selected' : '' }}>
                                                                Dibatalkan</option>

                                                        </select>
                                                    </div>

                                                    <div class="mb-3" id="desc-fields" style="display: none">
                                                        <textarea id="description" name="description" class="form-control w-full" placeholder="Masukkan Description "></textarea>
                                                    </div>
                                                    <input type="hidden" name="page"
                                                        value="{{ $orders->currentPage() }}">
                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-warning w-24">Ubah</button>
                                                        <button type="button" data-tw-dismiss="modal"
                                                            class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Change Confirmation Modal -->
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->

        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            {{ $orders->links('cms.layouts.paginate') }}
        </div>
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
