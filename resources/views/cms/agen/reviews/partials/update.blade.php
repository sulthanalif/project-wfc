<div id="upstat-confirmation-modal{{ $review->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5">
                    <form action="{{ route('reviews.update', $review) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-full" id="name" name="name"
                                placeholder="Masukkan Nama Pemberi Review.." value="{{ $review->name }}" required>
                        </div>
                        <div class="mt-3">
                            <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                            <input type="number" class="form-control w-full" id="rating" name="rating"
                                placeholder="Masukkan Rating.." max="5" min="1"
                                value="{{ $review->rating }}" required>
                        </div>
                        <div class="mt-3">
                            <label for="body" class="form-label">Komentar <span class="text-danger">*</span></label>
                            <div class="mt-2">
                                <textarea name="body" id="body" class="form-control w-full" rows="10">{{ $review->body }}</textarea>
                            </div>
                            {{-- <textarea id="body" name="body" class="editor" required></textarea> --}}
                        </div>
                        <div class="mt-3">
                            <label for="image" class="form-label">Upload Foto <span class="text-danger">(Jangan
                                    ubah jika tidak ingin diganti)</span></label>
                            <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
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
                            @if (isset($review->image))
                                <div class="mt-2" id="existing-image-preview">
                                    <img src="{{ route('getImage', ['path' => 'landingpage', 'imageName' => $review->image]) }}"
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
