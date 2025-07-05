<!-- END: Edit Confirmation Modal -->
<div id="edit-confirmation-modal{{ $data->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Ubah Bank</h2>
                </div>
            <div class="modal-body p-0">
                <div class="p-5">
                    <form action="{{ route('bank-owner.update', $data) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div>
                            <label for="name" class="form-label">Nama Bank <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $data->name }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="account_number" class="form-label">Nomor Rekening <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="account_number" name="account_number" class="form-control" value="{{ $data->account_number }}" required>
                            @error('account_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="account_name" class="form-label">Atas Nama <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="account_name" name="account_name" class="form-control" value="{{ $data->account_name }}" required>
                            @error('account_name')
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
