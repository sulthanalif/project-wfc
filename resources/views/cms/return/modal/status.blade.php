<div id="change-status-modal-{{ $return->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <i data-lucide="alert-circle" class="w-16 h-16 text-warning mx-auto mt-3"></i>
                    <div class="text-3xl mt-5">Apakah anda yakin?</div>
                    <div class="text-slate-500 mt-2">
                        Apakah anda yakin untuk <strong>mengubah status</strong> pengembalian ini?
                        <br>
                        Proses tidak akan bisa diulangi.
                    </div>
                </div>
                <div class="px-5 pb-8">
                    <form action="{{ route('return.changeStatus', $return) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <select class="form-select mt-2 sm:mr-2" id="status" name="status" required>
                                <option value="pending" {{ $return->status == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="processed" {{ $return->status == 'processed' ? 'selected' : '' }}>
                                    Diproses</option>
                                <option value="finished" {{ $return->status == 'finished' ? 'selected' : '' }}>
                                    Selesai</option>
                                <option value="rejected" {{ $return->status == 'rejected' ? 'selected' : '' }}>Ditolak
                                </option>

                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-warning w-24">Ubah</button>
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
