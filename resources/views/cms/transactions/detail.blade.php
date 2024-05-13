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

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="intro-y grid grid-cols-11 gap-5 mt-5">
        <div class="col-span-12 lg:col-span-4 2xl:col-span-3">
            <div class="box p-5 rounded-md">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Detail Transaksi</div>
                    {{-- @hasrole('admin|super_admin') --}}
                    {{-- <a href="javascript:;" class="flex items-center ml-auto text-primary" data-tw-toggle="modal"
                            data-tw-target="#change-confirmation-modal{{ $order->id }}"> <i data-lucide="edit"
                                class="w-4 h-4 mr-2"></i> Ubah Status </a> --}}
                    <!-- BEGIN: Change Confirmation Modal -->
                    {{-- <div id="change-confirmation-modal{{ $order->id }}" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body p-0">
                                        <div class="p-5 text-center">
                                            <i data-lucide="alert-circle" class="w-16 h-16 text-warning mx-auto mt-3"></i>
                                            <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                            <div class="text-slate-500 mt-2">
                                                Apakah anda yakin untuk mengubah status pesanan ini?
                                                <br>
                                                Proses tidak akan bisa diulangi.
                                            </div>
                                        </div>
                                        <div class="px-5 pb-8 text-center">
                                            <form action="{{ route('order.changeOrderStatus', $order) }}" method="post">
                                                @csrf
                                                <div class="mb-3">
                                                    <select class="form-select mt-2 sm:mr-2" id="status" name="status"
                                                        required>
                                                        <option value="accepted"
                                                            {{ $order->status == 'accepted' ? 'selected' : '' }}>Diterima
                                                        </option>
                                                        <option value="stop" {{ $order->status == 'stop' ? 'selected' : '' }}>
                                                            Mundur</option>
                                                        <option value="reject"
                                                            {{ $order->status == 'reject' ? 'selected' : '' }}>Ditolak</option>
                                                        <option value="canceled"
                                                            {{ $order->status == 'canceled' ? 'selected' : '' }}>Dibatalkan
                                                        </option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-warning w-24">Ubah</button>
                                                <button type="button" data-tw-dismiss="modal"
                                                    class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    <!-- END: Change Confirmation Modal -->
                    {{-- @endhasrole --}}
                </div>
                <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i> Nomor
                    Pesanan: <span class="underline decoration-dotted ml-1">{{ $order->order_number }}</span> </div>
                <div class="flex items-center mt-3"> <i data-lucide="calendar" class="w-4 h-4 text-slate-500 mr-2"></i>
                    Waktu Pesanan: {{ $order->order_date }} </div>
                <div class="flex items-center mt-3"> <i data-lucide="clock" class="w-4 h-4 text-slate-500 mr-2"></i> Status
                    Pesanan:
                    @if ($order->status === 'accepted')
                        <span class="bg-success/20 text-success rounded px-2 ml-1">Diterima</span>
                    @elseif ($order->status === 'stop')
                        <span class="bg-success/20 text-success rounded px-2 ml-1">Mundur</span>
                    @elseif ($order->status === 'reject')
                        <span class="bg-danger/20 text-danger rounded px-2 ml-1">Ditolak</span>
                    @elseif ($order->status === 'canceled')
                        <span class="bg-danger/20 text-danger rounded px-2 ml-1">Ditolak</span>
                    @else
                        <span class="bg-warning/20 text-warning rounded px-2 ml-1">Pending</span>
                    @endif
                </div>
            </div>
            @hasrole('admin|super_admin|finance_admin')
                <div class="box p-5 rounded-md mt-5">
                    <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                        <div class="font-medium text-base truncate">Detail Agen</div>
                        <a href="{{ route('user.show', $order->agent) }}" class="flex items-center ml-auto text-primary"> <i
                                data-lucide="eye" class="w-4 h-4 mr-2"></i> Lihat Profil </a>
                    </div>
                    <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i> Nama:
                        <span class="underline decoration-dotted ml-1">{{ $order->agent->agentProfile->name }}</span>
                    </div>
                    <div class="flex items-center mt-3"> <i data-lucide="calendar" class="w-4 h-4 text-slate-500 mr-2"></i>
                        Nomor Telepon: {{ $order->agent->agentProfile->phone_number }} </div>
                    <div class="flex items-center mt-3"> <i data-lucide="map-pin" class="w-4 h-4 text-slate-500 mr-2"></i>
                        Alamat: {{ $order->agent->agentProfile->rt }} </div>
                </div>
            @endhasrole
            {{-- <div class="box p-5 rounded-md mt-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Payment Details</div>
                </div>
                <div class="flex items-center">
                    <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i> Payment Method:
                    <div class="ml-auto">Direct bank transfer</div>
                </div>
                <div class="flex items-center mt-3">
                    <i data-lucide="credit-card" class="w-4 h-4 text-slate-500 mr-2"></i> Total Price (2 items):
                    <div class="ml-auto">$12,500.00</div>
                </div>
                <div class="flex items-center mt-3">
                    <i data-lucide="credit-card" class="w-4 h-4 text-slate-500 mr-2"></i> Total Shipping Cost (800 gr):
                    <div class="ml-auto">$1,500.00</div>
                </div>
                <div class="flex items-center mt-3">
                    <i data-lucide="credit-card" class="w-4 h-4 text-slate-500 mr-2"></i> Shipping Insurance:
                    <div class="ml-auto">$600.00</div>
                </div>
                <div class="flex items-center border-t border-slate-200/60 dark:border-darkmode-400 pt-5 mt-5 font-medium">
                    <i data-lucide="credit-card" class="w-4 h-4 text-slate-500 mr-2"></i> Grand Total:
                    <div class="ml-auto">$15,000.00</div>
                </div>
            </div>
            <div class="box p-5 rounded-md mt-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Shipping Information</div>
                    <a href="" class="flex items-center ml-auto text-primary"> <i data-lucide="map-pin"
                            class="w-4 h-4 mr-2"></i> Tracking Info </a>
                </div>
                <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i> Courier:
                    Left4code Express </div>
                <div class="flex items-center mt-3"> <i data-lucide="calendar" class="w-4 h-4 text-slate-500 mr-2"></i>
                    Tracking Number: 003005580322 <i data-lucide="copy" class="w-4 h-4 text-slate-500 ml-2"></i> </div>
                <div class="flex items-center mt-3"> <i data-lucide="map-pin" class="w-4 h-4 text-slate-500 mr-2"></i>
                    Address: 260 W. Storm Street New York, NY 10025. </div>
            </div> --}}
        </div>
        <div class="col-span-12 lg:col-span-7 2xl:col-span-8">
            <div class="box p-5 rounded-md">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Detail Produk Pesanan</div>
                </div>
                <div class="overflow-auto lg:overflow-visible -mt-3">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap text-center !py-5">Produk</th>
                                <th class="whitespace-nowrap text-center">Harga per Item</th>
                                <th class="whitespace-nowrap text-center">Qty</th>
                                <th class="whitespace-nowrap text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_qty = 0;
                            @endphp
                            @foreach ($order->detail as $item)
                                <tr>
                                    <td class="!py-4">
                                        <div class="flex items-center">
                                            @hasrole('admin|super_admin|finance_admin')
                                                @if ($item->product->detail->image == null)
                                                    -
                                                @else
                                                    @if ($item->product->detail->image == 'image.jpg')
                                                        <div class="w-10 h-10 image-fit zoom-in">
                                                            <img alt="PAKET SMART WFC"
                                                                class="rounded-lg border-2 border-white shadow-md"
                                                                src="{{ asset('assets/logo2.PNG') }}">
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
                                                    class="font-medium whitespace-nowrap ml-4">{{ $item->product->name }}</a>
                                            @endhasrole
                                            @hasrole('agent')
                                                @if ($item->product->detail->image == null)
                                                    -
                                                @else
                                                    <div class="w-10 h-10 image-fit zoom-in">
                                                        <img alt="PAKET SMART WFC"
                                                            class="rounded-lg border-2 border-white shadow-md tooltip"
                                                            src="{{ route('getImage', ['path' => 'product', 'imageName' => $item->product->detail->image]) }}">
                                                    </div>
                                                @endif
                                                <span
                                                    class="font-medium whitespace-nowrap ml-4">{{ $item->product->name }}</span>
                                            @endhasrole
                                        </div>
                                    </td>
                                    <td class="text-center">Rp. {{ number_format($item->product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td class="text-center">Rp. {{ number_format($item->sub_price, 0, ',', '.') }}</td>
                                </tr>
                                @php
                                    $total_qty += $item->qty;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="text-center">
                                <th colspan="2">TOTAL PESANAN</th>
                                <th>{{ $total_qty }}</th>
                                <th>Rp. {{ number_format($order->total_price, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="box p-5 rounded-md mt-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Detail Pembayaran</div>
                    @hasrole('agent')
                        @if ($order->status === 'accepted')
                            @if ($order->payment->sortByDesc('created_at')->first())
                                @if ($order->payment->sortByDesc('created_at')->first()->status == 'unpaid')
                                    <a class="btn btn-primary shadow-md flex items-center ml-auto"
                                        href="{{ route('payment.detail', ['payment' => $order->payment->sortByDesc('created_at')->first()]) }}">
                                        Setor </a>
                                    {{-- @else
                                    <a class="btn btn-primary shadow-md flex items-center ml-auto" href="javascript:;"
                                        data-tw-toggle="modal" data-tw-target="#payment-confirmation-modal">
                                        Setor </a> --}}
                                @endif
                            @else
                                <a class="btn btn-primary shadow-md flex items-center ml-auto" href="javascript:;"
                                    data-tw-toggle="modal" data-tw-target="#payment-confirmation-modal">
                                    Setor </a>
                            @endif
                        @endif
                    @endhasrole
                </div>
                <div class="overflow-auto lg:overflow-visible -mt-3">
                    @include('cms.transactions.table.table')
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: Payment Confirmation Modal -->
    <div id="payment-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        {{-- <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> --}}
                        <form action="{{ route('storePayment', $order) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mt-3">
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
                                <label for="image" class="form-label">Upload Bukti Pembayaran <span
                                        class="text-danger">*</span></label>
                                <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                    <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                    <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                    <input id="image" name="image" type="file"
                                        class="w-full h-full top-0 left-0 absolute opacity-0"
                                        onchange="previewFile(this)">
                                </div>
                                <div id="image-preview" class="hidden mt-2"></div>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="px-5 mt-3 pb-8 text-center">
                                <button type="submit" class="btn btn-success w-24">Setor</button>
                                <button type="button" data-tw-dismiss="modal"
                                    class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Payment Confirmation Modal -->
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>

    <script>
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
