@extends('cms.layouts.app', [
    'title' => 'Landing Page Header',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Landing Page Header
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('landingpage.header.update', $header) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div>
                        <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                        <input id="title" name="title" type="text" value="{{ $header->title }}"
                            class="form-control w-full" placeholder="Masukkan Nama Paket" required>
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="subTitle" class="form-label">Sub Judul <span class="text-danger">*</span></label>
                        <input id="subTitle" name="subTitle" type="text" value="{{ $header->subTitle }}"
                            class="form-control w-full" placeholder="Masukkan Nama Paket" required>
                        @error('subTitle')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <div class="mt-2">
                            <textarea id="description" name="description" class="form-control" placeholder="Masukkan Deskripsi Paket" required>{{ $header->description }}</textarea>
                        </div>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <label for="buttonTitle" class="form-label">Tulisan Tombol <span class="text-danger">*</span></label>
                        <input id="buttonTitle" name="buttonTitle" type="text" value="{{ $header->buttonTitle }}"
                            class="form-control w-full" placeholder="Masukkan Nama Paket" required>
                        @error('buttonTitle')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="buttonUrl" class="form-label">Link Tombol <span class="text-danger">*</span></label>
                        <input id="buttonUrl" name="buttonUrl" type="text" value="{{ $header->buttonUrl }}"
                            class="form-control w-full" placeholder="Masukkan Nama Paket" required>
                        @error('buttonUrl')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <label for="image" class="form-label">Logo <span class="text-danger">*</span></label>
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
                            <img src="{{ empty($header->image) ? asset('assets/logo2.png') : route('getImage', ['path' => 'landingpage', 'imageName' => $header->image]) }}" alt="">
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
                </form>
            </div>
            <!-- END: Form Layout -->
        </div>
    </div>
@endsection