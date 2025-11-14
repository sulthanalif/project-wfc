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
                <div class="flex flex-col lg:flex-row items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5 gap-2">
                    <div class="font-medium text-base truncate mr-2">Detail Produk Pesanan</div>
                    <div class="flex gap-2">
                        <div class="w-auto relative text-slate-500 border rounded">
                            <select id="records_select"
                                onchange="window.location.href = '{{ $order->id }}' + (this.value === 'all' ? '' : (document.getElementById('records_select_product').value !== 'all' ? '?select=' + this.value + '&productId=' + document.getElementById('records_select_product').value : '?select=' + this.value))"
                                class="tom-select box">
                                <option value="all">Semua Agen</option>
                                @foreach ($selects as $select)
                                    <option value="{{ $select }}"
                                        {{ request()->get('select') === $select || (request()->get('select') == 'agent' && trim($select) === trim($order->agent->agentProfile->name)) ? 'selected' : '' }}>
                                        {{ $select }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-auto relative text-slate-500 border rounded">
                            <select id="records_select_product"
                                onchange="window.location.href = '{{ $order->id }}' + (this.value === 'all' ? '' : (document.getElementById('records_select').value !== 'all' ? '?select=' + document.getElementById('records_select').value + '&productId=' + this.value : '?productId=' + this.value))"
                                class="tom-select box">
                                <option value="all">Semua</option>
                                @foreach ($selectProducts as $product)
                                    <option value="{{ $product['id'] }}"
                                        {{ request()->get('productId') == $product['id'] ? 'selected' : '' }}>
                                        {{ $product['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center ml-auto gap-2">
                        @hasrole('admin|super_admin')
                            <a href="{{ route('order.show', ['order' => $order, 'export' => 'true']) }}" class="btn btn-sm btn-primary"><i class="w-4 h-4" data-lucide="download"></i> Export</a>
                        @endhasrole
                        @hasrole('admin|super_admin')
                            <a href="{{ route('countPrice', ['order' => $order]) }}"
                                class="btn btn-sm btn-primary">Hitung Ulang</a>
                        @endhasrole
                    </div>
                    @hasrole('agent')
                        @php
                            // 1. Cek apakah keranjang kosong (Kondisi "Pengecualian Utama")
                            $isEmptyOrder = $order->detail->isEmpty();

                            // 2. Ambil data periode dengan aman (Null Safe)
                            // Hamba gunakan ?-> agar tidak error jika null
                            $firstDetail = $order->detail->first();
                            $period = $firstDetail?->product?->package?->package?->period;

                            // 3. Cek Status Periode
                            $isWithinPeriod = false;
                            if ($period && $period->access_date) {
                                $isWithinPeriod = \Carbon\Carbon::now()->lessThanOrEqualTo(\Carbon\Carbon::parse($period->access_date));
                            }

                            // 4. Cek Hak Akses User
                            $hasOpenAccess = Auth::user()->is_open_access;

                            // 5. LOGIKA PENENTU (THE GOLDEN RULE 👑)
                            $showButton = false;

                            if ($isEmptyOrder) {
                                // HUKUM 1: Kalau kosong, abaikan semua aturan lain, IZINKAN tambah.
                                $showButton = true;
                            } else {
                                // HUKUM 2: Kalau ada isi, user HARUS punya akses DAN masih dalam periode.
                                // Jika Access False -> Tombol Hilang (walau periode valid).
                                // Jika Periode Habis -> Tombol Hilang (walau access true).
                                $showButton = $hasOpenAccess && $isWithinPeriod;
                            }
                        @endphp

                        @if ($showButton)
                            <a class="flex items-center lg:ml-auto text-primary" href="javascript:;" data-tw-toggle="modal"
                                data-tw-target="#add-product-modal">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah
                            </a>
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
                            @if (request()->has('select') || request()->has('productId'))
                                @php
                                    $selectedAgent = request()->get('select');
                                    $selectedProduct = request()->get('productId');

                                    $query = $order
                                        ->detail()
                                        ->where(function ($query) use ($order, $selectedAgent, $selectedProduct) {
                                            if ($selectedAgent && $selectedAgent !== 'all') {
                                                if ($selectedAgent == $order->agent->agentProfile->name) {
                                                    $query->whereNull('sub_agent_id');
                                                } else {
                                                    $query->whereHas('subAgent', function ($query) use (
                                                        $selectedAgent,
                                                    ) {
                                                        $query->where('name', 'like', '%' . $selectedAgent . '%');
                                                    });
                                                }
                                            }

                                            if ($selectedProduct && $selectedProduct !== 'all') {
                                                $query->where('product_id', $selectedProduct);
                                            }

                                            if (
                                                ($selectedAgent == 'all' || !$selectedAgent) &&
                                                ($selectedProduct == 'all' || !$selectedProduct)
                                            ) {
                                                // Ambil semua data
                                            }

                                            if ($selectedAgent && $selectedProduct) {
                                                $query->where(function ($query) use (
                                                    $selectedAgent,
                                                    $selectedProduct,
                                                    $order,
                                                ) {
                                                    $query->where('product_id', $selectedProduct);
                                                    if ($selectedAgent == $order->agent->agentProfile->name) {
                                                        $query->whereNull('sub_agent_id');
                                                    } elseif ($selectedAgent !== 'all') {
                                                        $query->whereHas('subAgent', function ($query) use (
                                                            $selectedAgent,
                                                        ) {
                                                            $query->where('name', 'like', '%' . $selectedAgent . '%');
                                                        });
                                                    }
                                                });
                                            }
                                        });

                                    $details = $query->get();
                                @endphp
                                @foreach ($details as $item)
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
                                            @if (
                                                $item->product->package->package->period->access_date &&
                                                    \Carbon\Carbon::now()->lessThanOrEqualTo(
                                                        \Carbon\Carbon::parse($item->product->package->package->period->access_date)))
                                                <a href="javascript:;" class="btn btn-primary btn-sm" data-tw-toggle="modal"
                                                    data-tw-target="#detail-confirmation-modal{{ $item->id }}">
                                                    <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Ubah
                                                </a>

                                                @if ($item->qty == 0)
                                                    <a href="javascript:;" class="btn btn-danger btn-sm"
                                                        data-tw-toggle="modal"
                                                        data-tw-target="#delete-confirmation-modal{{ $item->id }}">
                                                        <i data-lucide="trash" class="w-4 h-4 mr-2"></i> Hapus
                                                    </a>
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
                                                                            Apakah anda yakin untuk menghapus data ini? <br>
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
                                        <td>
                                            @hasrole('agent')
                                                @php
                                                    // Ambil period dengan aman di dalam loop
                                                    $itemPeriod = $item->product?->package?->package?->period;
                                                @endphp

                                                @if (
                                                    $itemPeriod && 
                                                    $itemPeriod->access_date &&
                                                    \Carbon\Carbon::now()->lessThanOrEqualTo(\Carbon\Carbon::parse($itemPeriod->access_date))
                                                )
                                                    <a href="javascript:;" class="btn btn-primary btn-sm"
                                                        data-tw-toggle="modal"
                                                        data-tw-target="#detail-confirmation-modal{{ $item->id }}">
                                                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Ubah
                                                    </a>

                                                    @if ($item->qty == 0)
                                                        <a href="javascript:;" class="btn btn-danger btn-sm"
                                                            data-tw-toggle="modal"
                                                            data-tw-target="#delete-confirmation-modal{{ $item->id }}">
                                                            <i data-lucide="trash" class="w-4 h-4 mr-2"></i> Hapus
                                                        </a>
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
                                                                                Apakah anda yakin untuk menghapus data ini? <br>
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
                                                @endif

                                                @include('cms.transactions.modal.detail-modal')
                                            @endhasrole
                                        </td>
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
        </div>
    </div>
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
