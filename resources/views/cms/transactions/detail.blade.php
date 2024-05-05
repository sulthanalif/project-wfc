@extends('cms.layouts.app', [
    'title' => 'Detail Order',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Order
        </h2>
    </div>

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
                    </div>

                    <div class="flex items-center mt-2">
                        @foreach ($order->detail as $item)
                        {{ $loop->iteration }} . {{$item->name}}
                            <br>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        @if ($order->payment)
                        <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                            <img alt="PAKET SMART WFC" class=" img-fluid rounded-md"
                                src="{{ asset('storage/images/payment/'. $order->agent_id . '/' . $order->payment->image) }}">
                        </div>
                        @else
                            Belum Ada
                        @endif
                    </div>

                    <div class="mt-3">
                        <a class="flex items-center text-success" href="javascript:;" data-tw-toggle="modal"
                                                data-tw-target="#upload-confirmation-modal">
                                                 <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Upload bukti pembayaran </a>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: Delete Confirmation Modal -->
    <div id="upload-confirmation-modal" class="modal" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        {{-- <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> --}}
                    <form action="{{ route('storePayment', $order) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mt-3">
                            <label for="image" class="form-label">Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                            <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                <input id="image" name="image" type="file"
                                    class="w-full h-full top-0 left-0 absolute opacity-0" onchange="previewFile(this)">
                            </div>
                            <div id="image-preview" class="hidden mt-2"></div>
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="px-5 pb-8 text-center">


                                <button type="submit" class="btn btn-success w-24">Simpan</button>
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

