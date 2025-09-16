@extends('cms.layouts.app', [
    'title' => 'Edit Reward',
])

@section('content')

    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Edit Reward
        </h2>
    </div>

    @if ($errors->any())
        <div class="intro-y mt-5">
            <div class="alert alert-danger show mb-2" role="alert">
                <div class="flex items-center">
                    <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i>
                    <div>
                        <h4 class="font-medium">Terjadi Kesalahan!</h4>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('rewards.update', $reward->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 lg:col-span-6 intro-y">
                            <div>
                                <label for="title" class="form-label">Judul Reward <span class="text-danger">*</span></label>
                                <input id="title" name="title" type="text" class="form-control w-full"
                                    placeholder="Masukkan Judul Reward" value="{{ old('title', $reward->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="period_id" class="form-label">Periode <span class="text-danger">*</span></label>
                                <select class="tom-select mt-2 sm:mr-2" id="period_id" name="period_id">
                                    <option disabled>Pilih Periode...</option>
                                    @foreach ($periods as $period)
                                        <option value="{{ $period->id }}" {{ $period->id == $reward->period_id ? 'selected' : '' }}>
                                            {{ $period->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="target_qty" class="form-label">Target <span class="text-danger">*</span></label>
                                <input id="target_qty" name="target_qty" type="number" class="form-control w-full"
                                    placeholder="Masukkan Target" value="{{ old('target_qty', $reward->target_qty) }}" required>
                                @error('target_qty')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 intro-y mt-3 lg:mt-0">
                            <div>
                                <label>Deskripsi <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <textarea id="description" name="description" class="editor">
                                        {{ old('description', $reward->description) }}
                                    </textarea>
                                </div>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="image" class="form-label">Upload Foto</label>
                                <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                    <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                    <span id="fileName">
                                        <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                    </span>
                                    <input id="image" name="image" type="file" accept="image/*"
                                        class="w-full h-full top-0 left-0 absolute opacity-0" onchange="previewFile(this); updateFileName(this)">
                                </div>
                                <div id="image-preview" class="mt-2 {{ $reward->image ? '' : 'hidden' }}">
                                    @if($reward->image)
                                        @php
                                        $imageUrl = ($reward->image == 'image.jpg' || $reward->image == null)
                                                  ? asset('assets/logo2.png')
                                                  : route('getImage', ['path' => 'reward', 'imageName' => $reward->image]);
                                        @endphp
                                        <img alt="{{ $reward->title }}" class="rounded-md" src="{{ $imageUrl }}">
                                    @endif
                                </div>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Update</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                    </div>
                </form>
                <!-- END: Form Layout -->
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
