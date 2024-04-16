@extends('cms.layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Syarat dan Ketentuan -->
                <div class="col-span-12 lg:col-span-6 mt-8">
                    <div class="intro-y block sm:flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Syarat dan Ketentuan
                        </h2>
                    </div>
                    <div class="intro-y box p-5 mt-12 sm:mt-5">
                        <div class="flex flex-col md:flex-row md:items-center">
                            <img src="{{ asset('assets/syarat.jpg') }}" alt="Syarat dan Ketentuan" class="img-fluid">
                        </div>
                    </div>
                </div>
                <!-- END: Syarat dan Ketentuan -->
                <!-- BEGIN: Form Upload Berkas -->
                <div class="col-span-12 sm:col-span-6 lg:col-span-6 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Form Upload Berkas
                        </h2>
                        <a href="" class="ml-auto flex items-center text-primary truncate"> <i
                                data-lucide="refresh-ccw" class="w-4 h-4 mr-3"></i> Reload Data </a>
                    </div>
                    <div class="intro-y box p-5 mt-5">
                        <form action="{{ route('upload') }}" method="post" enctype="multipart/form-data" class="mt-3">
                            @csrf

                            <div>
                                <label for="ktp" class="form-label">Upload Foto Kartu Tanda Penduduk <span
                                        class="text-danger">*</span></label>
                                <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                    <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                    <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                    <input id="ktp" name="ktp" type="file"
                                        class="w-full h-full top-0 left-0 absolute opacity-0" onchange="previewFile(this)">
                                </div>
                                <div id="ktp-preview" class="hidden mt-2"></div>
                                @error('ktp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <label for="kk" class="form-label">Upload Foto Kartu Keluarga <span
                                        class="text-danger">*</span></label>
                                <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                    <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                    <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                    <input id="kk" name="kk" type="file"
                                        class="w-full h-full top-0 left-0 absolute opacity-0" onchange="previewFile(this)">
                                </div>
                                <div id="kk-preview" class="hidden mt-2"></div>
                                @error('kk')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <label for="sPerjanjian" class="form-label">Upload Foto Surat Perjanjian <span
                                        class="text-danger">*</span></label>
                                <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                    <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                    <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                    <input id="sPerjanjian" name="sPerjanjian" type="file"
                                        class="w-full h-full top-0 left-0 absolute opacity-0" onchange="previewFile(this)">
                                </div>
                                <div id="sPerjanjian-preview" class="hidden mt-2"></div>
                                @error('sPerjanjian')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="text-left mt-5">
                                <button type="submit" class="btn btn-primary w-24">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END: Form Upload Berkas -->
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        function previewFile(input) {
            var imagePreviewId = "#" + input.id + "-preview"; // Get corresponding preview div ID
            var imagePreviewElement = document.querySelector(imagePreviewId);

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    imagePreviewElement.classList.remove("hidden"); // Show preview div
                    imagePreviewElement.innerHTML = "<img src='" + e.target.result + "' />";
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                imagePreviewElement.classList.add("hidden"); // Hide preview div if no file selected
                imagePreviewElement.innerHTML = "";
            }
        }
    </script>
@endpush
