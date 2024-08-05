@extends('cms.layouts.app', [
    'title' => 'Landing Page Review',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Landing Page Review
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('landingpage.review.update', $review) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 lg:col-span-6 intro-y">
                            <div>
                                <label for="title" class="form-label">Judul Review <span
                                        class="text-danger">*</span></label>
                                <input id="title" name="title" type="text" value="{{ $review->title }}"
                                    class="form-control w-full" placeholder="Masukkan Judul Review" required>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 intro-y mt-3 lg:mt-0">
                            <div>
                                <label for="subTitle" class="form-label">Sub Judul <span
                                        class="text-danger">*</span></label>
                                <input id="subTitle" name="subTitle" type="text" value="{{ $review->subTitle }}"
                                    class="form-control w-full" placeholder="Masukkan Sub Judul Review" required>
                                @error('subTitle')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="flex text-left">
                            <button type="submit" class="btn btn-primary w-24">Simpan</button>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 mt-5">
        <div class="intro-y col-span-12">
            <div class="intro-y box p-5">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6">
                        <label>Daftar Review</label>
                    </div>
                </div>
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center justify-between mt-3">
                    <a href="javascript:;" class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal"
                        data-tw-target="#create-confirmation-modal">Tambah Ulasan</a>

                        <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $review->reviews->count() }} data</div>
                    
                    <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                        <div class="w-56 relative text-slate-500">
                            <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="filter">
                            <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                        </div>
                    </div>
                </div>                
                <div class="overflow-auto lg:overflow-visible">
                    <table class="table mt-2">
                        <thead>
                            <tr>
                                <th class="text-center whitespace-nowrap">#</th>
                                <th class="text-center whitespace-nowrap">NAMA USER</th>
                                <th class="text-center whitespace-nowrap">RATING</th>
                                <th class="text-center whitespace-nowrap">KOMENTAR</th>
                                <th class="text-center whitespace-nowrap">STATUS</th>
                                <th class="text-center whitespace-nowrap">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($review->reviews->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada review</td>
                                </tr>
                            @else
                                @foreach ($review->reviews as $dataReview)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="!py-3.5">
                                            <div class="flex items-center">
                                                <div class="w-9 h-9 image-fit zoom-in">
                                                    <img alt="PAKET SMART WFC"
                                                        class="rounded-lg border-white shadow-md"
                                                        src="{{ empty($dataReview->image) ? asset('assets/cms/images/profile.svg') : route('getImage', ['path' => 'landingpage', 'imageName' => $dataReview->image]) }}"
                                                        title="{{ $dataReview->as }}">
                                                </div>
                                                <div class="ml-4">
                                                    <p
                                                        class="font-medium whitespace-nowrap text-slate-500 flex items-center mr-3">
                                                        {{ $dataReview->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-slate-500 flex items-center justify-center">
                                                {{ $dataReview->rating }}</p>
                                        </td>
                                        <td>
                                            <p class="text-slate-500 flex items-center">{{ $dataReview->body }}</p>
                                        </td>
                                        <td>
                                            @if ($dataReview->isPublish())
                                                <a class="flex items-center justify-center text-success" href="javascript:;"
                                                    data-tw-toggle="modal"
                                                    data-tw-target="#active-confirmation-modal{{ $dataReview->id }}"><i
                                                        data-lucide="check-square" class="w-4 h-4 mr-2"></i>
                                                    Publish </a>
                                            @else
                                                <a class="flex items-center justify-center text-danger" href="javascript:;"
                                                    data-tw-toggle="modal"
                                                    data-tw-target="#active-confirmation-modal{{ $dataReview->id }}"><i
                                                        data-lucide="x-square" class="w-4 h-4 mr-2"></i>
                                                    Unpublish </a>
                                            @endif
                                            <div id="active-confirmation-modal{{ $dataReview->id }}" class="modal"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body p-0">
                                                            <div class="p-5 text-center">
                                                                <i data-lucide="alert-circle"
                                                                    class="w-16 h-16 text-warning mx-auto mt-3"></i>
                                                                <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                                                <div class="text-slate-500 mt-2">
                                                                    Apakah anda yakin untuk
                                                                    {{ $dataReview->publish ? 'Unpublish' : 'Publish' }}
                                                                    review ini?
                                                                    <br>
                                                                    Proses tidak akan bisa diulangi.
                                                                </div>
                                                            </div>
                                                            <div class="px-5 pb-8 text-center">
                                                                <form action="{{ route('publishReview') }}" method="post">
                                                                    @csrf
                                                                    @method('post')
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $dataReview->id }}">
                                                                    <button type="submit"
                                                                        class="btn btn-{{ $dataReview->publish ? 'danger' : 'primary' }} w-24">{{ $dataReview->publish ? 'Unpublish' : 'Publish' }}</button>
                                                                    <button type="button" data-tw-dismiss="modal"
                                                                        class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="w-56">
                                            <div class="flex justify-center items-center">
                                                <a class="flex items-center mr-3" href="javascript:;"
                                                    data-tw-toggle="modal"
                                                    data-tw-target="#upstat-confirmation-modal{{ $dataReview->id }}"> <i
                                                        data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                                <a class="flex items-center mr-3 text-danger" href="javascript:;"
                                                    data-tw-toggle="modal"
                                                    data-tw-target="#delete-confirmation-modal{{ $dataReview->id }}">
                                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- BEGIN: Update Confirmation Modal -->
                                    <div id="upstat-confirmation-modal{{ $dataReview->id }}" class="modal"
                                        tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body p-0">
                                                    <div class="p-5">
                                                        <form
                                                            action="{{ route('landingpage.review.updateReview', $dataReview) }}"
                                                            method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div>
                                                                <label for="name" class="form-label">Nama <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control w-full"
                                                                    id="name" name="name"
                                                                    placeholder="Masukkan Nama Pemberi Review.."
                                                                    value="{{ $dataReview->name }}" required>
                                                            </div>
                                                            <div class="mt-3">
                                                                <label for="rating" class="form-label">Rating <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" class="form-control w-full"
                                                                    id="rating" name="rating"
                                                                    placeholder="Masukkan Rating.." max="5"
                                                                    min="1" value="{{ $dataReview->rating }}"
                                                                    required>
                                                            </div>
                                                            <div class="mt-3">
                                                                <label for="body" class="form-label">Komentar <span
                                                                        class="text-danger">*</span></label>
                                                                <div class="mt-2">
                                                                    <textarea name="body" id="body" class="form-control w-full" rows="10">{{ $dataReview->body }}</textarea>
                                                                </div>
                                                                {{-- <textarea id="body" name="body" class="editor" required></textarea> --}}
                                                            </div>
                                                            <div class="mt-3">
                                                                <label for="image" class="form-label">Upload Foto <span
                                                                        class="text-danger">(Jangan
                                                                        ubah jika tidak ingin diganti)</span></label>
                                                                <div
                                                                    class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                                                    <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                                                    <span id="fileNames">
                                                                        <span class="text-primary mr-1">Upload a
                                                                            file</span> or drag and drop
                                                                    </span>
                                                                    <input id="image" name="image" type="file"
                                                                        class="w-full h-full top-0 left-0 absolute opacity-0"
                                                                        onchange="previewFiles(this); updateFileNames(this)">
                                                                </div>
                                                                <div id="image-previews" class="hidden mt-2"></div>
                                                                @if (isset($dataReview->image))
                                                                    <div class="mt-2" id="existing-image-preview">
                                                                        <img src="{{ route('getImage', ['path' => 'landingpage', 'imageName' => $dataReview->image]) }}"
                                                                            class="w-auto h-40 object-fit-cover rounded">
                                                                    </div>
                                                                @endif
                                                                @error('image')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="px-5 mt-3 text-center">
                                                                <button type="submit"
                                                                    class="btn btn-primary w-24">Simpan</button>
                                                                <button type="button" data-tw-dismiss="modal"
                                                                    class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END: Update Confirmation Modal -->
                                    <!-- BEGIN: Delete Confirmation Modal -->
                                    <div id="delete-confirmation-modal{{ $dataReview->id }}" class="modal"
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
                                                        <form
                                                            action="{{ route('landingpage.review.deleteReview', $dataReview) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('delete')
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
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Form Layout -->
    <!-- BEGIN: Create Confirmation Modal -->
    <div id="create-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5">
                        <form action="{{ route('landingpage.review.addReview', $review) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div>
                                <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control w-full" id="name" name="name"
                                    placeholder="Masukkan Nama Pemberi Review..">
                            </div>
                            <div class="mt-3">
                                <label for="rating" class="form-label">Rating <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control w-full" id="rating" name="rating"
                                    placeholder="Masukkan Rating.." max="5" min="1" required>
                            </div>
                            <div class="mt-3">
                                <label for="body" class="form-label">Komentar <span
                                        class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <textarea name="body" id="body" class="form-control w-full" rows="10"></textarea>
                                </div>
                                {{-- <textarea id="body" name="body" class="editor" required></textarea> --}}
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
                                        onchange="previewFile(this); updateFileName(this)" required>
                                </div>
                                <div id="image-preview" class="hidden mt-2"></div>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="px-5 mt-3 text-center">
                                <button type="submit" class="btn btn-primary w-24">Simpan</button>
                                <button type="button" data-tw-dismiss="modal"
                                    class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                            </div>
                        </form>
                    </div>
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

        function previewFiles(input) {
            const file = input.files[0];
            const preview = document.getElementById('image-previews');

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

        function updateFileNames(input) {
            const fileName = input.value.split('\\').pop(); // Extract filename from path
            const fileSpan = document.getElementById('fileNames');
            if (fileSpan) {
                fileSpan.textContent = fileName || 'No file chosen'; // Set text to filename or 'No file chosen'
            }
        }
    </script>
@endpush
