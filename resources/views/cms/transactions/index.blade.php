@extends('cms.layouts.app', [
    'title' => 'Pesanan Paket',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Pesanan Paket
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @hasrole('super_admin|agent')
                <a href="{{ route('order.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Pesanan</a>
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $orders->firstItem() }} hingga
                    {{ $orders->lastItem() }} dari {{ $orders->total() }} data</div>
            @endhasrole
            <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search...">
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
                        <th class="whitespace-nowrap">NOMOR PESANAN</th>
                        @hasrole('super_admin|admin')
                            <th class="text-center whitespace-nowrap">DARI AGEN</th>
                        @endhasrole
                        <th class="whitespace-nowrap">TOTAL HARGA</th>
                        <th class="whitespace-nowrap">STATUS</th>
                        <th class="whitespace-nowrap">PEMBAYARAN</th>
                        @hasrole('super_admin|admin')
                            <th class="text-center whitespace-nowrap">AKSI</th>
                        @endhasrole
                    </tr>
                </thead>
                <tbody>
                    @if ($orders->isEmpty())
                        <tr>
                            <td colspan="5" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
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
                                <td>
                                    <p>
                                        Rp. {{ number_format($order->total_price, 0, ',', '.') }}
                                    </p>
                                </td>
                                <td>
                                    {{ $order->status }}
                                </td>
                                <td>
                                    @if ($order->payment_status === 'paid')
                                        <div class="flex items-center justify-center text-success"> <i
                                                data-lucide="check-square" class="w-4 h-4 mr-2"></i> Lunas </div>
                                    @else
                                        <div class="flex items-center justify-center text-danger"> <i data-lucide="x-square"
                                                class="w-4 h-4 mr-2"></i> Belum Dibayar</div>
                                    @endif
                                </td>
                                @hasrole('super_admin|admin')
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#change-confirmation-modal{{ $order->id }}">
                                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah Status </a>
                                            <a class="flex items-center text-success" href="javascript:;" data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal{{ $order->id }}">
                                                 <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Accept </a>
                                        </div>
                                    </td>
                                @endhasrole
                            </tr>



                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="delete-confirmation-modal{{ $order->id }}" class="modal" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body p-0">
                                            <div class="p-5 text-center">
                                                {{-- <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> --}}
                                                <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                                <div class="text-slate-500 mt-2">
                                                    Apakah anda yakin untuk acc order ini?
                                                    <br>
                                                    Proses tidak akan bisa diulangi.
                                                </div>
                                            </div>
                                            <div class="px-5 pb-8 text-center">
                                                <form action="{{ route('order.accOrder', $order) }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="page"
                                                        value="{{ $orders->currentPage() }}">
                                                    <button type="submit" class="btn btn-success w-24">Acc</button>
                                                    <button type="button" data-tw-dismiss="modal"
                                                        class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Delete Confirmation Modal -->

                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="change-confirmation-modal{{ $order->id }}" class="modal" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body p-0">
                                            <div class="p-5 text-center">
                                                {{-- <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> --}}
                                                <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                                <div class="text-slate-500 mt-2">
                                                    Apakah anda yakin untuk Ubah Status order ini?
                                                    <br>
                                                    Proses tidak akan bisa diulangi.
                                                </div>
                                            </div>
                                            <div class="px-5 pb-8 text-center">
                                                <form action="{{ route('order.changeOrderStatus', $order) }}" method="post">
                                                    @csrf
                                                    <div class="mt-3 mb-3">
                                                        <label for="status" class="form-label">Ubah Status <span class="text-danger">*</span></label>
                                                        <select class="form-select mt-2 sm:mr-2" id="status" name="status" required>
                                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                                                            <option value="reject" {{ $order->status == 'reject' ? 'selected' : '' }}>Ditolak</option>
                                                            <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>

                                                        </select>
                                                    </div>
                                                    <input type="hidden" name="page"
                                                        value="{{ $orders->currentPage() }}">
                                                    <button type="submit" class="btn btn-success w-24">Ubah</button>
                                                    <button type="button" data-tw-dismiss="modal"
                                                        class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Delete Confirmation Modal -->
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