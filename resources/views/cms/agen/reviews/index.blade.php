@extends('cms.layouts.app', [
    'title' => 'Ulasan',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Ulasan
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="javascript:;" class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal"
                data-tw-target="#create-confirmation-modal">Tambah Ulasan</a>
            @include('cms.agen.reviews.partials.create')

            <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $reviews->firstItem() }} hingga
                {{ $reviews->lastItem() }} dari {{ $reviews->total() }} data</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="filter">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">#</th>
                        <th class="text-center whitespace-nowrap">NAMA</th>
                        <th class="text-center whitespace-nowrap">RATING</th>
                        <th class="text-center whitespace-nowrap">KOMENTAR</th>
                        <th class="text-center whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($reviews->isEmpty())
                        <tr>
                            <td colspan="5" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($reviews as $review)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td class="!py-3.5">
                                    <div class="flex items-center">
                                        <div class="w-9 h-9 image-fit zoom-in">
                                            <img alt="PAKET SMART WFC" class="rounded-lg border-white shadow-md"
                                                src="{{ empty($review->image) ? asset('assets/cms/images/profile.svg') : route('getImage', ['path' => 'landingpage', 'imageName' => $review->image]) }}">
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-medium whitespace-nowrap text-slate-500 flex items-center mr-3">
                                                {{ $review->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-slate-500 flex items-center justify-center">
                                        {{ $review->rating }}</p>
                                </td>
                                <td>
                                    <p class="text-slate-500 flex items-center">{{ $review->body }}</p>
                                </td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#upstat-confirmation-modal{{ $review->id }}"> <i
                                                data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                        @include('cms.agen.reviews.partials.update')
                                        
                                        <a class="flex items-center mr-3 text-danger" href="javascript:;"
                                            data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal{{ $review->id }}">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                        @include('cms.agen.reviews.partials.delete')
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            {{ $reviews->links('cms.layouts.paginate') }}
        </div>
        <!-- END: Pagination -->
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
