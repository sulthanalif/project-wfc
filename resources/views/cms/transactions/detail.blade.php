@extends('cms.layouts.app', [
    'title' => 'Detail Order',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Order
        </h2>
    </div>

    @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
    <div class="intro-y box px-5 pt-5 mt-5">

        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            {{-- <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <img alt="PAKET SMART WFC" class=" img-fluid rounded-md"
                    src="{{ asset('storage/images/package/' . $package->image) }}">
            </div> --}}

            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="text-slate-600 dark:text-slate-500">
                    <div class="flex flex-col items-center justify-center border-b pb-2">
                        <h1 class="font-bold text-xl">{{ $order->order_number }}</h1>
                        <span class="text-muted">{{$order->order_date}}</span>
                        <span class="text-muted">{{$order->status}}</span>
                        <span class="text-muted">{{$order->payment_status}}</span>
                    </div>

                    <div class="flex items-center mt-2">
                        @foreach ($order->detail as $item)
                        {{ $loop->iteration }} . {{$item->name}}
                            <br>
                        @endforeach
                    </div>
                    @include('cms.transactions.table.table')

                    {{-- <div class="mt-3">
                        @if (!$order->payment->image)
                        <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                            <img alt="PAKET SMART WFC" class=" img-fluid rounded-md" --}}
                                {{-- src="{{ asset('storage/images/payment/'. $order->agent_id . '/' . $order->payment->image) }}"> --}}
                                {{-- src="{{ route('getImage', ['path' => 'payment/'.$order->agent_id, 'imageName' => $order->payment->image]) }}">
                        </div>
                        @else
                            Belum Ada
                        @endif
                    </div> --}}
                    @hasrole('super_admin')


                    @if ($order->payment->sortByDesc('created_at')->first())
                        @if ($order->payment->sortByDesc('created_at')->first()->status == 'unpaid')
                            <div class="mt-3">
                                <a class="btn btn-primary" href="{{ route('payment.detail', ['payment' => $order->payment->sortByDesc('created_at')->first()]) }}">
                                                        Setor </a>
                                                        <a class="btn btn-primary" href="{{ route('order.index') }}">
                                                            Kembali </a>
                            </div>
                        @else
                            <div class="mt-3">
                                <a class="btn btn-primary" href="javascript:;" data-tw-toggle="modal"
                                                    data-tw-target="#payment-confirmation-modal">
                                                    Setor </a>
                                                    <a class="btn btn-primary" href="{{ route('order.index') }}">
                                                        Kembali </a>
                            </div>
                        @endif
                    @else
                        <div class="mt-3">
                            <a class="btn btn-primary" href="javascript:;" data-tw-toggle="modal"
                                                    data-tw-target="#payment-confirmation-modal">
                                                    Setor </a>
                                                    <a class="btn btn-primary" href="{{ route('order.index') }}">
                                                        Kembali </a>
                        </div>
                    @endif

                    @endhasrole

                    {{-- @hasrole('super_admin')
                    <div class="mt-3">
                        <a class="btn btn-success" href="javascript:;" data-tw-toggle="modal"
                                                data-tw-target="#acc-confirmation-modal">
                                                 <i data-lucide="edit" class="w-4 h-4 mr-1"></i> {{$order->payment_status == 'unpaid' ? 'Acc' : 'Tolak'}} pembayaran </a>
                    </div>
                    @endhasrole --}}

                </div>
            </div>
        </div>
    </div>


    <!-- BEGIN: Delete Confirmation Modal -->
    <div id="payment-confirmation-modal" class="modal" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        {{-- <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> --}}
                    <form action="{{ route('storePayment', $order) }}" method="post" >
                        @csrf
                        <div class="mt-3">
                            <label class="form-label">Total Pembayaran</label>
                            <span> Rp. {{ number_format($order->payment->sortByDesc('created_at')->first() ? $order->payment->sortByDesc('created_at')->first()->remaining_payment : $order->total_price, 0, ',', '.') }}</span>
                        </div>

                            <div class="mt-3">
                                <label for="pay" class="form-label">Jumlah Pembayaran <span class="text-danger">*</span></label>
                                <input id="pay" name="pay" type="number" value="{{ number_format($order->payment->sortByDesc('created_at')->first() ? $order->payment->sortByDesc('created_at')->first()->remaining_payment : $order->total_price, 0, ',', '') }}" class="form-control w-full"
                                    placeholder="Masukkan Jumlah Pembayaran" required>
                                @error('pay')
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
    <!-- END: Delete Confirmation Modal -->

    <!-- BEGIN: Delete Confirmation Modal -->
    <div id="acc-confirmation-modal" class="modal" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        {{-- <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> --}}
                        <div class="text-3xl mt-5">Apakah anda yakin?</div>
                        <div class="text-slate-500 mt-2">
                            Apakah anda yakin untuk {{$order->payment_status == 'unpaid' ? 'Acc' : 'Tolak'}} data ini?
                            <br>
                            Proses tidak akan bisa diulangi.
                        </div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <form action="{{ route('changePaymentStatus', $order) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-primary w-24">{{$order->payment_status == 'unpaid' ? 'Acc' : 'Tolak'}}</button>
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Delete Confirmation Modal -->
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
