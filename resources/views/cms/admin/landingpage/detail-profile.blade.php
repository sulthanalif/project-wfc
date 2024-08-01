@extends('cms.layouts.app', [
    'title' => 'Landing Page Detail Profile',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Landing Page Detail Profile
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('landingpage.detailProfile.update', $detailProfile) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 lg:col-span-6 intro-y">
                            <div>
                                <label for="titleHistory" class="form-label">Judul Sejarah <span
                                        class="text-danger">*</span></label>
                                <input id="titleHistory" name="titleHistory" type="text"
                                    value="{{ $detailProfile->titleHistory }}" class="form-control w-full"
                                    placeholder="Masukkan Nama Barang" required>
                                @error('titleHistory')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label>Isi Sejarah <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <textarea id="bodyHistory" name="bodyHistory" class="editor" required>
                                    {{ $detailProfile->bodyHistory }}
                                  </textarea>
                                </div>
                                @error('bodyHistory')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="image" class="form-label">Upload Foto <span
                                        class="text-danger">*</span></label>
                                <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                    <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                    <span id="fileName">
                                        <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                    </span>
                                    <input id="image" name="image" type="file"
                                        class="w-full h-full top-0 left-0 absolute opacity-0"
                                        onchange="previewFile(this); updateFileName(this)">
                                </div>
                                <div class="mt-2">
                                    <img src="{{ empty($detailProfile->image) ? asset('assets/logo.png') : route('getImage', ['path' => 'landingpage', 'imageName' => $detailProfile->image]) }}"
                                        alt="" id="image-preview" class="w-auto h-40 object-cover rounded">
                                </div>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="text-left mt-5">
                                <button type="submit" class="btn btn-primary w-24">Simpan</button>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 intro-y mt-3 lg:mt-0">
                            <div>
                                <label for="titleVM" class="form-label">Judul Visi & Misi <span
                                        class="text-danger">*</span></label>
                                <input id="titleVM" name="titleVM" type="text" value="{{ $detailProfile->titleVM }}"
                                    class="form-control w-full" placeholder="Masukkan Jangka Waktu" required>
                                @error('titleVM')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label>Visi <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <textarea id="vision" name="vision" class="editor" required>
                                    {{ $detailProfile->vision }}
                                  </textarea>
                                </div>
                                @error('vision')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                </form>
                <div class="mt-3">
                    <label>Misi </label>
                    <table class="table ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Poin</th>
                                <th scope="col">
                                    <a href="javascript:;" class="flex items-center ml-auto text-primary"
                                        data-tw-toggle="modal" data-tw-target="#create-confirmation-modal"> <i
                                            data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailProfile->mission as $mission)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $mission->content }}</td>
                                    <td>
                                        <a class="flex items-center mr-3 text-danger" href="javascript:;"
                                            data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal{{ $mission->id }}">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                    </td>
                                </tr>
                                <!-- BEGIN: Delete Confirmation Modal -->
                                <div id="delete-confirmation-modal{{ $mission->id }}" class="modal" tabindex="-1"
                                    aria-hidden="true">
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
                                                    <form
                                                        action="{{ route('landingpage.detailProfile.deleteMission', $mission) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger w-24">Hapus</button>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Form Layout -->
    </div>
    </div>
    <!-- BEGIN: Create Confirmation Modal -->
    <div id="create-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5">
                        <form action="{{ route('landingpage.detailProfile.addMission', $detailProfile) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mt-3">
                                <label for="content" class="form-label">Poin <span class="text-danger">*</span></label>
                                <textarea id="content" name="content" class="form-control" placeholder="Masukkan Poin..."> </textarea>
                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="px-5 mt-3 pb-8 text-center">
                                <button type="submit" class="btn btn-primary w-24">Simpan</button>
                                <button type="button" data-tw-dismiss="modal"
                                    class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Create Confirmation Modal -->
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
                    alert("Ukuran gambar lebih dari 2MB. Silakan pilih gambar yang lebih kecil.");
                    preview.src = ''; // Clear any existing preview
                    preview.classList.add('hidden'); // Hide the preview container
                    input.value = ''; // Clear the file input value
                    return;
                }

                // Check file type (images only)
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const extension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(extension)) {
                    alert("Hanya file dengan tipe (jpg, jpeg, png) yang diperbolehkan!");
                    preview.src = ''; // Clear any existing preview
                    preview.classList.add('hidden'); // Hide the preview container
                    input.value = ''; // Clear the file input value
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden'); // Show the preview container
                };

                reader.readAsDataURL(file);
            } else {
                preview.src = ''; // Clear any existing preview
                preview.classList.add('hidden'); // Hide the preview container
            }
        }
    </script>
@endpush
