@extends('cms.layouts.app', [
    'title' => 'Tambah Barang',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Tambah Barang
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 lg:col-span-6 intro-y">
                            <div>
                                <label for="name" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input id="name" name="name" type="text" class="form-control w-full"
                                    placeholder="Masukkan Nama Barang" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="price" class="form-label">Harga Barang/hari <span class="text-danger">*</span></label>
                                <input id="price" name="price" type="number" class="form-control w-full"
                                    placeholder="Masukkan Harga Barang" required>
                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="days" class="form-label">Jangka Waktu (hari) <span class="text-danger">*</span></label>
                                <input id="days" name="days" type="number" class="form-control w-full"
                                    placeholder="Masukkan Jangka Waktu" required>
                                @error('days')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="unit" class="form-label">Satuan <span class="text-danger">*</span></label>
                                <input id="unit" name="unit" type="text" class="form-control w-full"
                                    placeholder="Masukkan Satuan Barang" required>
                                @error('unit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            {{-- <div class="mt-3">
                                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">(Jangan ubah jika
                                        tidak ada Supplier)</span></label>
                                <select class="form-select mt-2 sm:mr-2" id="supplier_id" name="supplier_id">
                                    <option value="">Pilih...</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="mt-3">
                                <label for="package_id" class="form-label">Paket <span class="text-danger">(Jangan ubah jika
                                        tidak masuk Paket)</span></label>
                                <select class="form-select mt-2 sm:mr-2" id="package_id" name="package_id">
                                    <option value="">Pilih...</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="is_safe_point" class="form-label">Titik Aman <span class="text-danger">*</span></label>
                                <div class="mt-2 flex">
                                    <label class="flex items-center">
                                        <input type="radio" name="is_safe_point" value="1" class="form-check-input mr-2" >
                                        <span class="text-sm">Ya</span>
                                    </label>
                                    <label class="ml-5 flex items-center">
                                        <input type="radio" name="is_safe_point" value="0" class="form-check-input mr-2" checked>
                                        <span class="text-sm">Tidak</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 intro-y mt-3 lg:mt-0">
                            <div>
                                <label>Deskripsi <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <textarea id="description" name="description" class="editor">
                                    Masukkan Deskripsi Barang
                                  </textarea>
                                </div>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="image" class="form-label">Upload Foto <span class="text-danger">*</span></label>
                                <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                    <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                    <span id="fileName">
                                        <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                    </span>
                                    <input id="image" name="image" type="file"
                                        class="w-full h-full top-0 left-0 absolute opacity-0" onchange="previewFile(this); updateFileName(this)">
                                </div>
                                <div id="image-preview" class="hidden mt-2"></div>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                    </div>
                    </form>
                    <!-- END: Form Layout -->
            </div>
        </div>
    </div>
    </div>
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
