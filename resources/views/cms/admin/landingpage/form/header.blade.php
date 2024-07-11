<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Header
    </h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <!-- BEGIN: Form Layout -->
        <div class="intro-y box p-5">
            <form action="{{ route('landing-page.updateHeader', $header->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div>
                    <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                    <input id="title" name="title" type="text" value="{{ $header->title }}" class="form-control w-full"
                        placeholder="Masukkan Judul.." required>
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="sub_title" class="form-label">Sub Judul <span class="text-danger">*</span></label>
                    <input id="sub_title" name="sub_title" type="text" value="{{ $header->sub_title }}" class="form-control w-full"
                        placeholder="Masukkan Sub Judul.." required>
                    @error('sub_title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mt-3">
                    <label>Deskripsi <span class="text-danger">*</span></label>
                    <div class="mt-2">
                        <textarea id="description" name="description" class="editor">
                        {{ $header->description }}
                      </textarea>
                    </div>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="button_title" class="form-label">Judul Tombol <span class="text-danger">*</span></label>
                    <input id="button_title" name="button_title" type="text" value="{{ $header->button_title }}" class="form-control w-full"
                        placeholder="Masukkan Sub Judul.." required>
                    @error('button_title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="button_url" class="form-label">Url Tombol <span class="text-danger">*</span></label>
                    <input id="button_url" name="button_url" type="text" value="{{ $header->button_url }}" class="form-control w-full"
                        placeholder="Masukkan Sub Judul.." required>
                    @error('button_url')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="image" class="form-label">Upload Logo <span class="text-danger">*</span></label>
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
                    <div class="mt-3">
                        <img src="{{ empty($header->image) ? asset('assets/logo2.png') : route('getImage', ['path' => 'images/header/', 'imageName' => $header->image]) }}" class="img-fluid ms-5" alt="">
                    </div>
                </div>
                <div class="text-left mt-5">
                    <button type="submit" class="btn btn-primary w-24">Simpan</button>
                    {{-- <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a> --}}
                </div>
            </form>
        </div>
        <!-- END: Form Layout -->
    </div>
</div>
