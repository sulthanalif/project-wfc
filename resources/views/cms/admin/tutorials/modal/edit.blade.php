<!-- END: Edit Confirmation Modal -->
<div id="edit-confirmation-modal{{ $data->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Ubah Bank</h2>
                </div>
            <div class="modal-body p-0">
                <div class="p-5">
                    <form action="{{ route('tutorial.update', $data) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div>
                            <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="form-control" value="{{ $data->title }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea id="description" name="description" class="form-control" rows="3" required>{{ $data->description }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="link" class="form-label">Link <span class="text-danger">*</span></label>
                            <input type="url" id="link" name="link" class="form-control" value="{{ $data->link }}" required>
                            @error('link')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select id="role" name="role" class="form-control" required>
                                <option value="">Pilih Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ $data->role == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
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
