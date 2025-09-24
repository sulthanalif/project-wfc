@extends('cms.layouts.app', [
    'title' => 'Detail Pengembalian',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Pengembalian Produk
        </h2>
        <a href="{{ route('return.index') }}" class="btn btn-primary w-24 mr-1">Kembali</a>
    </div>

    <div class="intro-y grid grid-cols-11 gap-5 mt-5">
        <div class="col-span-12">
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 lg:col-span-6">
                    <div class="box p-5 rounded-md">
                        <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                            <div class="font-medium text-base truncate">Detail Pengembalian</div>
                        </div>
                        <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i>
                            Nomor
                            Pengembalian: <span class="underline decoration-dotted ml-1">{{ $return->return_number }}</span>
                        </div>
                        <div class="flex items-center mt-3"> <i data-lucide="clock" class="w-4 h-4 text-slate-500 mr-2"></i>
                            Status
                            Pengembalian:
                            @if ($return->status === 'finished')
                                <span class="bg-success/20 text-success rounded px-2 ml-1">Selesai</span>
                            @elseif ($return->status === 'processed')
                                <span class="bg-success/20 text-success rounded px-2 ml-1">Diproses</span>
                            @elseif ($return->status === 'rejected')
                                <span class="bg-danger text-white rounded px-2 ml-1">Ditolak</span>
                            @else
                                <span class="bg-warning/20 text-warning rounded px-2 ml-1">Pending</span>
                            @endif
                        </div>
                        <div class="flex items-center mt-3"> <i data-lucide="calendar"
                                class="w-4 h-4 text-slate-500 mr-2"></i>
                            Tanggal Pengajuan: {{ $return->date_in }} </div>
                        <div class="flex items-center mt-3"> <i data-lucide="calendar"
                                class="w-4 h-4 text-slate-500 mr-2"></i>
                            Tanggal Pengembalian: {{ $return->date_out ?? 'N/A' }} </div>
                        <div class="flex items-center mt-3"> <i data-lucide="file-text"
                                class="w-4 h-4 text-slate-500 mr-2"></i>
                            Keterangan:
                            <p class="ml-1">{!! $return->notes ?? 'N/A' !!}</p>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <div class="box p-5 rounded-md">
                        <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                            <div class="font-medium text-base truncate">Detail Pesanan</div>
                        </div>
                        <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i>
                            Nomor
                            Pesanan: <span class="underline decoration-dotted ml-1">{{ $order->order_number }}</span> </div>
                        <div class="flex items-center mt-3"> <i data-lucide="user" class="w-4 h-4 text-slate-500 mr-2"></i>
                            Nama Agen: {{ $order->agent->agentProfile->name }} </div>
                        <div class="flex items-center mt-3"> <i data-lucide="calendar"
                                class="w-4 h-4 text-slate-500 mr-2"></i>
                            Tanggal Pesanan: {{ $order->order_date }} </div>
                        <div class="flex items-center mt-3"> <i data-lucide="clock" class="w-4 h-4 text-slate-500 mr-2"></i>
                            Status
                            Pesanan:
                            @if ($order->status === 'accepted')
                                <span class="bg-success/20 text-success rounded px-2 ml-1">Diterima</span>
                            @elseif ($order->status === 'stop')
                                <span class="bg-success/20 text-success rounded px-2 ml-1">Mundur</span>
                            @elseif ($order->status === 'reject')
                                <span class="bg-danger text-white rounded px-2 ml-1">Ditolak</span>
                            @elseif ($order->status === 'canceled')
                                <span class="bg-danger text-white rounded px-2 ml-1">Dibatalkan</span>
                            @else
                                <span class="bg-warning/20 text-warning rounded px-2 ml-1">Pending</span>
                            @endif
                        </div>
                        <div class="flex items-center mt-3"> <i data-lucide="dollar-sign"
                                class="w-4 h-4 text-slate-500 mr-2"></i>
                            Status
                            Pembayaran:
                            @if ($order->payment_status === 'paid')
                                <span class="bg-success/20 text-success rounded px-2 ml-1">Lunas</span>
                            @elseif ($order->payment_status === 'pending')
                                <span class="bg-warning/20 text-warning rounded px-2 ml-1">Dicicil</span>
                            @else
                                <span class="bg-danger text-white rounded px-2 ml-1">Belum Dibayar</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12">
            <div class="box p-5 rounded-md">
                <div class="flex items-center border-b border-bottom pb-3 mb-3">
                    <div class="font-medium text-base truncate">Detail Produk Pengembalian</div>
                    @hasrole('agent')
                        @if ($return->status == 'pending')
                            <a class="flex ml-auto items-center text-primary" href="javascript:;" data-tw-toggle="modal"
                                data-tw-target="#add-item-modal">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Item
                            </a>
                            @include('cms.return.modal.add-item')
                        @endif
                    @endhasrole
                </div>
                <div class="overflow-auto lg:overflow-visible -mt-3">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap text-center">Produk</th>
                                <th class="whitespace-nowrap text-center !py-5">Item</th>
                                <th class="whitespace-nowrap text-center">Qty</th>
                                <th class="whitespace-nowrap text-center">Status Item</th>
                                @hasrole('agent')
                                    <th class="whitespace-nowrap text-center">Aksi</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_qty = 0;
                            @endphp

                            @foreach ($detail as $item)
                                <tr>
                                    <td class="!py-4">
                                        <div class="flex items-center">
                                            @if ($item->product->detail->image == null)
                                                <div class="w-10 h-10 image-fit zoom-in">
                                                    <img alt="PAKET SMART WFC"
                                                        class="rounded-lg border-2 border-white shadow-md"
                                                        src="{{ asset('assets/logo2.png') }}">
                                                </div>
                                            @else
                                                @if ($item->product->detail->image == 'image.jpg')
                                                    <div class="w-10 h-10 image-fit zoom-in">
                                                        <img alt="PAKET SMART WFC"
                                                            class="rounded-lg border-2 border-white shadow-md"
                                                            src="{{ asset('assets/logo2.png') }}">
                                                    </div>
                                                @else
                                                    <div class="w-10 h-10 image-fit zoom-in">
                                                        <img alt="PAKET SMART WFC"
                                                            class="rounded-lg border-2 border-white shadow-md"
                                                            src="{{ route('getImage', ['path' => 'product', 'imageName' => $item->product->detail->image]) }}">
                                                    </div>
                                                @endif
                                            @endif
                                            <span class="font-medium whitespace-nowrap ml-4">{{ $item->product->name }}
                                                {{ $item->product->is_safe_point == 1 ? '(Titik Aman)' : '' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{ $item->subProduct->name ?? '-' }}
                                    </td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td class="text-center">
                                        @if ($item->status_product == 'expired')
                                            Kadaluarsa
                                        @elseif ($item->status_product == 'damaged')
                                            Rusak
                                        @elseif ($item->status_product == 'overstock')
                                            Kelebihan Stok
                                        @else
                                            Lainnya
                                        @endif
                                    </td>
                                    <td>
                                        @hasrole('agent')
                                            @if ($return->status == 'pending')
                                                <div class="flex justify-center items-center">
                                                    <a class="flex items-center text-primary mr-3" href="javascript:;"
                                                        data-tw-toggle="modal"
                                                        data-tw-target="#edit-item-modal-{{ $item->id }}">
                                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah
                                                    </a>

                                                    {{-- Modal Edit Item --}}
                                                    <div id="edit-item-modal-{{ $item->id }}" class="modal"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header flex items-center justify-between">
                                                                    <h2 class="font-medium text-base mr-auto">Ubah Item
                                                                        Pengembalian</h2>
                                                                    <button type="button"
                                                                        class="btn btn-outline-secondary btn-sm btn-icon btn-circle"
                                                                        data-tw-dismiss="modal" aria-label="Close"> <i
                                                                            data-lucide="x" class="w-4 h-4"></i> </button>
                                                                </div>
                                                                <form action="{{ route('return.item.update', ['return' => $return, 'item' => $item]) }}" method="post">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="modal-body p-0 pt-5">
                                                                        <div class="px-5 pb-8">
                                                                            <div class="grid grid-cols-12 gap-4">
                                                                                <div class="col-span-12">
                                                                                    <label for="qty"
                                                                                        class="form-label">Jumlah
                                                                                        Item</label>
                                                                                    <input type="number" name="qty"
                                                                                        class="form-control"
                                                                                        placeholder="Masukkan jumlah item"
                                                                                        value="{{ old('qty', $item->qty) }}"
                                                                                        min="1" required>
                                                                                </div>
                                                                                <div class="col-span-12">
                                                                                    <label for="status_product"
                                                                                        class="form-label">Status
                                                                                        Item</label>
                                                                                    <select name="status_product"
                                                                                        class="form-select" required>
                                                                                        <option value="expired"
                                                                                            {{ old('status_product', $item->status_product) == 'expired' ? 'selected' : '' }}>
                                                                                            Kadaluarsa
                                                                                        </option>
                                                                                        <option value="damaged"
                                                                                            {{ old('status_product', $item->status_product) == 'damaged' ? 'selected' : '' }}>
                                                                                            Rusak
                                                                                        </option>
                                                                                        <option value="overstock"
                                                                                            {{ old('status_product', $item->status_product) == 'overstock' ? 'selected' : '' }}>
                                                                                            Kelebihan Stok
                                                                                        </option>
                                                                                        <option value="other"
                                                                                            {{ old('status_product', $item->status_product) == 'other' ? 'selected' : '' }}>
                                                                                            Lainnya
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="px-5 pb-8 text-right">
                                                                            <button type="button"
                                                                                class="btn btn-outline-secondary w-24 mr-1"
                                                                                data-tw-dismiss="modal">Batal</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary w-24">Simpan</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- End Modal Edit Item --}}

                                                    <a class="flex items-center text-danger" href="javascript:;"
                                                        data-tw-toggle="modal"
                                                        data-tw-target="#delete-item-modal-{{ $item->id }}">
                                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus
                                                    </a>

                                                    {{-- Modal Hapus Item --}}
                                                    <div id="delete-item-modal-{{ $item->id }}" class="modal"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-body p-0">
                                                                    <div class="p-5 text-center">
                                                                        <i data-lucide="x-circle"
                                                                            class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                                                        <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                                                        <div class="text-slate-500 mt-2">
                                                                            Apakah anda yakin untuk menghapus data ini? <br>
                                                                            Proses tidak akan bisa diulangi.
                                                                        </div>
                                                                    </div>
                                                                    <div class="px-5 pb-8 text-center">
                                                                        <form
                                                                            action="{{ route('return.item.destroy', ['return' => $return, 'item' => $item]) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('delete')
                                                                            <button type="button" data-tw-dismiss="modal"
                                                                                class="btn btn-outline-secondary w-24 mr-1">Batal</button>
                                                                            <button type="submit"
                                                                                class="btn btn-danger w-24">Hapus</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- End Modal Hapus Item --}}
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center text-success"> <i
                                                        data-lucide="check-square" class="w-4 h-4 mr-2"></i> </div>
                                            @endif
                                        @endhasrole
                                    </td>
                                </tr>
                                @php
                                    $total_qty += $item->qty;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="text-center">
                                <th colspan="2">TOTAL PENGEMBALIAN</th>
                                <th>{{ $total_qty }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
