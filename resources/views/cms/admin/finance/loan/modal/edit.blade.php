<div id="edit-modal{{ $loan->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header flex items-center justify-between">
                <h2 class="font-medium text-base mr-auto">Ubah Piutang</h2>
                <button type="button" class="btn btn-outline-secondary btn-sm btn-icon btn-circle"
                    data-tw-dismiss="modal" aria-label="Close"> <i data-lucide="x" class="w-4 h-4"></i> </button>
            </div>
            <div class="modal-body p-0">
                <div class="px-5 pb-5">
                    <form action="{{ route('loan.update', $loan->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mt-3">
                            <label for="borrower_name" class="form-label">Nama Peminjam <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="borrower_name" name="borrower_name" value="{{ $loan->borrower_name }}" class="form-control">
                            @error('borrower_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-12 gap-3 mt-3">
                            <div class="col-span-6">
                                <label for="amount" class="form-label">Jumlah Pinjaman <span
                                        class="text-danger">*</span></label>
                                <input id="amount" name="amount" type="number" value="{{ $loan->amount }}"
                                    class="form-control w-full" placeholder="Masukkan Jumlah Pinjaman" required>
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-span-6">
                                <label for="date" class="form-label">Tanggal Pinjaman <span
                                        class="text-danger">*</span></label>
                                <input id="date" name="date" type="date" value="{{ $loan->date }}" class="form-control w-full"
                                    max="{{ date('Y-m-d') }}" required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-3 mt-3">
                            <div class="col-span-6">
                                <label for="upd_method_{{ $loan->id }}" class="form-label">Metode Pembayaran <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="upd_method_{{ $loan->id }}" name="upd_method" required>
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                                @error('upd_method')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-span-6" id="upd_bank-field-{{ $loan->id }}" style="display: none">
                                <label for="upd_bank_{{ $loan->id }}" class="form-label">Bank <span class="text-danger">*</span></label>
                                <select class="form-select" id="upd_bank_{{ $loan->id }}" name="upd_bank">
                                    <option value="">Pilih...</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }} -
                                            {{ $bank->account_number }} ({{ $bank->account_name }})</option>
                                    @endforeach
                                </select>
                                @error('bank')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3 mb-3">
                            <label for="description" class="form-label">Keterangan <span
                                    class="text-danger">*</span></label>
                            <textarea id="description" name="description" class="editor">{{ $loan->description }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="px-5 mt-3 text-center">
                            <button type="submit" class="btn btn-primary w-24">Perbarui</button>
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const methodSelect = document.getElementById('upd_method_{{ $loan->id }}');
            const bankField = document.getElementById('upd_bank-field-{{ $loan->id }}');
    
            // Show/hide on page load
            if (methodSelect.value === 'Transfer') {
                bankField.style.display = 'block';
            } else {
                bankField.style.display = 'none';
            }
    
            methodSelect.addEventListener('change', function (event) {
                if (event.target.value === 'Transfer') {
                    bankField.style.display = 'block';
                } else {
                    bankField.style.display = 'none';
                }
            });
        });
    </script>
@endpush