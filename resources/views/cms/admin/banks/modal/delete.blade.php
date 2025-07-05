<!-- BEGIN: Delete Confirmation Modal -->
<div id="delete-confirmation-modal{{ $data->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                    <div class="text-3xl mt-5">Apakah anda yakin?</div>
                    <div class="text-slate-500 mt-2">
                        Apakah anda yakin untuk menghapus data ini?
                        <br>
                        Proses tidak akan bisa diulangi.
                    </div>
                </div>
                <div class="px-5 pb-8 text-center">
                    <form action="{{ route('bank-owner.destroy', $data) }}" method="post">
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
