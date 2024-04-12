@extends('cms.layouts.app', [
    'title' => 'Edit Paket',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Edit Paket
        </h2>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
            <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> {{ session('error') }}
            <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x"
                    class="w-4 h-4"></i> </button>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('paket.update', $paket) }}" method="post" enctype="multipart/form-data">
                    @method('put')
                    @csrf
                    <div>
                        <label for="name" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                        <input id="name" name="name" type="text" class="form-control w-full"
                            placeholder="Masukkan Nama Paket" required value="{{ $paket->name }}"
                            @error('name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                            </div>

                        <div class="mt-3">
                            <label>Deskripsi <span class="text-danger">*</span></label>
                            <div class="mt-2">
                                <textarea id="description" name="description" class="editor">
                      {!! $paket->description !!}
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
                                <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                <input id="image" name="image" type="file"
                                    class="w-full h-full top-0 left-0 absolute opacity-0" onchange="previewFile(this)"
                                    accept="image/*">
                            </div>
                            <div id="image-preview" class="hidden mt-2"></div>
                            @if (isset($paket->image))
                                <div class="mt-2" id="existing-image-preview">
                                    <img src="{{ asset('storage/images/paket/' . $paket->image) }}"
                                        class="w-auto h-40 object-fit-cover rounded">
                                </div>
                            @endif
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 mr-1">Kembali</a>
                    </div>
                </form>
            </div>
            <!-- END: Form Layout -->
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
                    document.getElementById('existing-image-preview').innerHTML = '';
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
