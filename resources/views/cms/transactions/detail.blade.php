@extends('cms.layouts.app', [
    'title' => 'Detail Pesanan',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Pesanan
        </h2>
        <a href="{{ route('order.index') }}" class="btn btn-primary w-24 mr-1">Kembali</a>
    </div>

    <div class="intro-y grid grid-cols-11 gap-5 mt-5">
        <div class="col-span-12">
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 lg:col-span-6">
                    <div class="box p-5 rounded-md">
                        <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                            <div class="font-medium text-base truncate">Detail Transaksi</div>
                        </div>
                        <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i>
                            Nomor
                            Pesanan: <span class="underline decoration-dotted ml-1">{{ $order->order_number }}</span> </div>
                        <div class="flex items-center mt-3"> <i data-lucide="calendar"
                                class="w-4 h-4 text-slate-500 mr-2"></i>
                            Waktu Pesanan: {{ $order->order_date }} </div>
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
                        <div class="flex items-center mt-3"> <i data-lucide="file-text"
                                class="w-4 h-4 text-slate-500 mr-2"></i>
                            Keterangan:
                            @if ($order->status === 'reject')
                                <p class="ml-1">{{ $order->description }}</p>
                            @else
                                <p class="ml-1">-</p>
                            @endif
                        </div>
                    </div>
                </div>
                @hasrole('admin|super_admin|finance_admin')
                    <div class="col-span-12 lg:col-span-6">
                        <div class="box p-5 rounded-md">
                            <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                                <div class="font-medium text-base truncate">Detail Agen</div>
                                <a href="{{ route('user.show', $order->agent) }}"
                                    class="flex items-center ml-auto text-primary"> <i data-lucide="eye"
                                        class="w-4 h-4 mr-2"></i> Lihat Profil </a>
                            </div>
                            <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i>
                                Nama:
                                <span class="underline decoration-dotted ml-1">{{ $order->agent->agentProfile->name }}</span>
                            </div>
                            <div class="flex items-center mt-3"> <i data-lucide="calendar"
                                    class="w-4 h-4 text-slate-500 mr-2"></i>
                                Nomor Telepon: {{ $order->agent->agentProfile->phone_number }} </div>
                            <div class="flex items-center mt-3"> <i data-lucide="map-pin"
                                    class="w-4 h-4 text-slate-500 mr-2"></i>
                                Alamat: {{ $order->agent->agentProfile->address }} </div>
                        </div>
                    </div>
                @endhasrole
            </div>
        </div>
        <div class="col-span-12">
            <div class="box p-5 rounded-md">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate mr-2">Detail Produk Pesanan</div>
                    <div class="w-auto relative text-slate-500 border rounded">
                        <select id="records_select"
                            onchange="window.location.href = '{{ $order->id }}' + (this.value === 'all' ? '' : '?select=' + this.value)"
                            class="form-control box">
                            <option value="all">Semua</option>
                            @foreach ($selects as $select)
                                <option value="{{ $select }}"
                                    {{ request()->get('select') === $select || (request()->get('select') == 'agent' && trim($select) === trim($order->agent->agentProfile->name)) ? 'selected' : '' }}>
                                    {{ $select }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @hasrole('agent')
                        @php
                            $detail = optional($order->detail->first());
                            $product = optional($detail->product);
                            $package = optional($product->package);
                            $period = optional($package->period);
                        @endphp
                        @if ($period->access_date && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($period->access_date)))
                            <!-- Konten jika kondisi true -->
                        @else
                            <a class="flex items-center ml-auto text-primary" href="javascript:;" data-tw-toggle="modal"
                                data-tw-target="#add-product-modal"><i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah</a>
                            @include('cms.transactions.modal.add-product')
                        @endif
                    @endhasrole
                </div>
                <div class="overflow-auto lg:overflow-visible -mt-3">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap text-center">Nama</th>
                                <th class="whitespace-nowrap text-center !py-5">Produk</th>
                                <th class="whitespace-nowrap text-center">Harga per Item</th>
                                <th class="whitespace-nowrap text-center">Qty</th>
                                <th class="whitespace-nowrap text-center">Total</th>
                                @hasrole('agent')
                                    <th class="whitespace-nowrap text-center">Aksi</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_qty = 0;
                                $total_price = 0;
                            @endphp
                            @if (request()->has('select'))
                                @foreach ($order->detail()->where(function ($query) use ($order) {
                if (request()->get('select') == $order->agent->agentProfile->name) {
                    $query->whereNull('sub_agent_id');
                } else {
                    $query->whereHas('subAgent', function ($query) {
                        $query->where('name', 'like', '%' . request()->get('select') . '%');
                    });
                }
            })->get() as $item)
                                    <tr>
                                        <td>{{ $item->subAgent->name ?? $order->agent->agentProfile->name }}</td>
                                        <td class="!py-4">
                                            <div class="flex items-center">
                                                @hasrole('admin|super_admin|finance_admin')
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
                                                    <a href="{{ route('product.show', $item->product_id) }}"
                                                        class="font-medium whitespace-nowrap ml-4">{{ $item->product->name }}
                                                        {{ $item->product->is_safe_point == 1 ? '(Titik Aman)' : '' }}</a>
                                                @endhasrole
                                                @hasrole('agent')
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
                                                    <span
                                                        class="font-medium whitespace-nowrap ml-4">{{ $item->product->name }}
                                                        {{ $item->product->is_safe_point == 1 ? '(Titik Aman)' : '' }}</span>
                                                @endhasrole
                                            </div>
                                        </td>
                                        <td class="text-center">Rp.
                                            {{ number_format($item->product->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">{{ $item->qty }}</td>
                                        <td class="text-center">Rp. {{ number_format($item->sub_price, 0, ',', '.') }}
                                        </td>
                                        @hasrole('agent')
                                            @if (\Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($item->product->package->package->period->access_date)))
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-sm" disabled><i data-lucide="edit"
                                                            class="w-4 h-4 mr-2"></i> Ubah</button>
                                                </td>
                                            @else
                                                <!-- Tanggal akses belum terlewat, tombol bisa diklik -->
                                                <td class="text-center">
                                                    <a href="javascript:;" class="btn btn-primary btn-sm"
                                                        data-tw-toggle="modal"
                                                        data-tw-target="#detail-confirmation-modal{{ $item->id }}"><i
                                                            data-lucide="edit" class="w-4 h-4 mr-2"></i> Ubah</a>
                                                    @if ($item->qty == 0)
                                                        <a href="javascript:;" class="btn btn-danger btn-sm"
                                                            data-tw-toggle="modal"
                                                            data-tw-target="#delete-confirmation-modal{{ $item->id }}"><i
                                                                data-lucide="trash" class="w-4 h-4 mr-2"></i> Hapus</a>
                                                        <!-- BEGIN: Delete Confirmation Modal -->
                                                        <div id="delete-confirmation-modal{{ $item->id }}" class="modal"
                                                            tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-body p-0">
                                                                        <div class="p-5 text-center">
                                                                            <i data-lucide="x-circle"
                                                                                class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                                                            <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                                                            <div class="text-slate-500 mt-2">
                                                                                Apakah anda yakin untuk menghapus data ini?
                                                                                <br>
                                                                                Proses tidak akan bisa diulangi.
                                                                            </div>
                                                                        </div>
                                                                        <div class="px-5 pb-8 text-center">
                                                                            <form action="#" method="post">
                                                                                @csrf
                                                                                @method('delete')
                                                                                <input type="hidden" name="page"
                                                                                    value="{{ $item->id }}">
                                                                                <button type="submit"
                                                                                    class="btn btn-danger w-24">Hapus</button>
                                                                                <button type="button" data-tw-dismiss="modal"
                                                                                    class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- END: Delete Confirmation Modal -->
                                                    @endif
                                                </td>
                                            @endif

                                            @include('cms.transactions.modal.detail-modal')
                                        @endhasrole
                                    </tr>
                                    @php
                                        $total_qty += $item->qty;
                                        $total_price += $item->sub_price;
                                    @endphp
                                @endforeach
                            @else
                                @foreach ($order->detail as $item)
                                    <tr>
                                        <td>{{ $item->subAgent->name ?? $order->agent->agentProfile->name }}</td>
                                        <td class="!py-4">
                                            <div class="flex items-center">
                                                @hasrole('admin|super_admin|finance_admin')
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
                                                    <a href="{{ route('product.show', $item->product_id) }}"
                                                        class="font-medium whitespace-nowrap ml-4">{{ $item->product->name }}
                                                        {{ $item->product->is_safe_point == 1 ? '(Titik Aman)' : '' }}</a>
                                                @endhasrole
                                                @hasrole('agent')
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
                                                    <span
                                                        class="font-medium whitespace-nowrap ml-4">{{ $item->product->name }}
                                                        {{ $item->product->is_safe_point == 1 ? '(Titik Aman)' : '' }}</span>
                                                @endhasrole
                                            </div>
                                        </td>
                                        <td class="text-center">Rp.
                                            {{ number_format($item->product->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">{{ $item->qty }}</td>
                                        <td class="text-center">Rp. {{ number_format($item->sub_price, 0, ',', '.') }}
                                        </td>
                                        @hasrole('agent')
                                            @php
                                                $product = optional($item->product);
                                                $package = optional($product->package);
                                                $period = optional($package->period);
                                            @endphp

                                            @if ($period->access_date && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($period->access_date)))
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-sm" disabled><i data-lucide="edit"
                                                            class="w-4 h-4 mr-2"></i> Ubah</button>
                                                </td>
                                            @else
                                                <!-- Tanggal akses belum terlewat, tombol bisa diklik -->
                                                <td class="text-center">
                                                    <a href="javascript:;" class="btn btn-primary btn-sm"
                                                        data-tw-toggle="modal"
                                                        data-tw-target="#detail-confirmation-modal{{ $item->id }}"><i
                                                            data-lucide="edit" class="w-4 h-4 mr-2"></i> Ubah</a>
                                                    @if ($item->qty == 0)
                                                        <a href="javascript:;" class="btn btn-danger btn-sm"
                                                            data-tw-toggle="modal"
                                                            data-tw-target="#delete-confirmation-modal{{ $item->id }}"><i
                                                                data-lucide="trash" class="w-4 h-4 mr-2"></i> Hapus</a>
                                                        <!-- BEGIN: Delete Confirmation Modal -->
                                                        <div id="delete-confirmation-modal{{ $item->id }}" class="modal"
                                                            tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-body p-0">
                                                                        <div class="p-5 text-center">
                                                                            <i data-lucide="x-circle"
                                                                                class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                                                            <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                                                            <div class="text-slate-500 mt-2">
                                                                                Apakah anda yakin untuk menghapus data ini?
                                                                                <br>
                                                                                Proses tidak akan bisa diulangi.
                                                                            </div>
                                                                        </div>
                                                                        <div class="px-5 pb-8 text-center">
                                                                            <form action="#" method="post">
                                                                                @csrf
                                                                                @method('delete')
                                                                                <input type="hidden" name="page"
                                                                                    value="{{ $item->id }}">
                                                                                <button type="submit"
                                                                                    class="btn btn-danger w-24">Hapus</button>
                                                                                <button type="button" data-tw-dismiss="modal"
                                                                                    class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- END: Delete Confirmation Modal -->
                                                    @endif
                                                </td>
                                            @endif

                                            @include('cms.transactions.modal.detail-modal')
                                        @endhasrole
                                    </tr>
                                    @php
                                        $total_qty += $item->qty;
                                        $total_price += $item->sub_price;
                                    @endphp
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="text-center">
                                <th colspan="3">TOTAL PESANAN</th>
                                <th>{{ $total_qty }}</th>
                                <th>Rp. {{ number_format($total_price, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            {{-- <div class="box p-5 rounded-md mt-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Detail Pembayaran</div>
                    @hasrole('admin|super_admin')
                        @if ($order->payment_status !== 'paid')
                            <a class="btn btn-primary shadow-md flex items-center ml-auto" href="javascript:;"
                                data-tw-toggle="modal" data-tw-target="#payment-confirmation-modal">
                                Setor </a>
                        @endif
                    @endhasrole
                </div>
                <div class="overflow-auto lg:overflow-visible -mt-3">
                    @include('cms.transactions.table.table')
                </div>
            </div> --}}
        </div>
    </div>

    <!-- BEGIN: Payment Confirmation Modal -->
    {{-- <div id="payment-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5">
                        <form action="{{ route('storePayment', $order) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mt-3 text-center">
                                <label class="form-label">Total Pembayaran</label>
                                <span class="font-bold"> Rp.
                                    {{ number_format($order->payment->sortByDesc('created_at')->first() ? $order->payment->sortByDesc('created_at')->first()->remaining_payment : $order->total_price, 0, ',', '.') }}</span>
                            </div>

                            <div class="mt-3">
                                <label for="pay" class="form-label">Jumlah Pembayaran <span
                                        class="text-danger">*</span></label>
                                <input id="pay" name="pay" type="number"
                                    value="{{ number_format($order->payment->sortByDesc('created_at')->first() ? $order->payment->sortByDesc('created_at')->first()->remaining_payment : $order->total_price, 0, ',', '') }}"
                                    class="form-control w-full" placeholder="Masukkan Jumlah Pembayaran" required>
                                @error('pay')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="method" class="form-label">Metode Pembayaran <span
                                        class="text-danger">*</span></label>
                                <select class="form-select mt-2 sm:mr-2" id="method" name="method" required>
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                                @error('method')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3" id="bank-field" style="display: none">
                                <label for="bank" class="form-label">Bank <span class="text-danger">*</span></label>
                                <select class="form-select mt-2 sm:mr-2" id="bank" name="bank">
                                    <option value="">Pilih...</option>
                                    <option value="BRI">BRI</option>
                                    <option value="BCA">BCA</option>
                                    <option value="Mandiri">Mandiri</option>
                                </select>
                                @error('bank')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="date" class="form-label">Tanggal <span
                                        class="text-danger">*</span></label>
                                <input id="date" name="date" type="date" class="form-control w-full"
                                    required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <label for="note" class="form-label">Keterangan <span
                                        class="text-danger">*</span></label>
                                <textarea id="note" name="note" class="editor"> </textarea>
                                @error('note')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="px-5 mt-3 pb-8 text-center">
                                <button type="submit" class="btn btn-primary w-24">Setor</button>
                                <button type="button" data-tw-dismiss="modal"
                                    class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- END: Payment Confirmation Modal -->
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>

    <script>
        const methodSelect = document.getElementById('method');
        const bankField = document.getElementById('bank-field');

        methodSelect.addEventListener('change', (event) => {
            if (event.target.value === 'Transfer') {
                bankField.style.display = 'block';
            } else {
                bankField.style.display = 'none';
            }
        });

        const currentDate = '{{ now()->format('Y-m-d') }}'; // Blade templating to get current date
        const dateInput = document.getElementById('date');

        dateInput.value = currentDate;


        function previewFile(input) {
            const file = input.files[0];
            const preview = document.getElementById('image-preview');

            if (file) {
                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    alert("Ukuran gambar lebih dari 2MB. Silahkan pilih gambar yang lebih kecil");
                    preview.innerHTML = ''; // Clear any existing preview
                    preview.classList.add('hidden'); // Hide the preview container
                    input.value = ''; // Clear the file input value
                    return;
                }

                // Check file type (images only)
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const extension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(extension)) {
                    alert("Hanya file dengan tipe (jpg, jpeg, png) yang diperbolehkan!!");
                    preview.innerHTML = ''; // Clear any existing preview
                    preview.classList.add('hidden'); // Hide the preview container
                    input.value = ''; // Clear the file input value
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('w-auto', 'h-40', 'object-cover', 'rounded'); // Adjust size and styles as needed
                    preview.innerHTML = ''; // Clear previous previews
                    preview.classList.remove('hidden'); // Show the preview container
                    preview.appendChild(img);
                };

                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = ''; // Clear any existing preview
                preview.classList.add('hidden'); // Hide the preview container
            }
        }
    </script>
@endpush
