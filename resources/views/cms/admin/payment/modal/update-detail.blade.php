<!-- BEGIN: Payment Confirmation Modal -->
<div id="update-detail-confirmation-modal{{ $payment->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5">
                    <form action="{{ route('updatePayment', $payment) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mt-3">
                            <label for="upd_method_{{ $payment->id }}" class="form-label">Metode Pembayaran <span
                                    class="text-danger">*</span></label>
                            <select class="form-select mt-2 sm:mr-2 method-select" id="upd_method_{{ $payment->id }}"
                                name="upd_method" data-id="{{ $payment->id }}" required>
                                <option value="Tunai"
                                    {{ old('upd_method', $payment->method) == 'Tunai' ? 'selected' : '' }}>Tunai
                                </option>
                                <option value="Transfer"
                                    {{ old('upd_method', $payment->method) == 'Transfer' ? 'selected' : '' }}>Transfer
                                </option>
                            </select>
                            @error('upd_method')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div id="upd-bank-field-{{ $payment->id }}"
                            style="display: {{ old('upd_method', $payment->method) == 'Transfer' ? 'block' : 'none' }}">
                            <div class="grid grid-cols-12 gap-2 mt-3">
                                <div class="col-span-12 lg:col-span-6">
                                    <label for="upd_bank" class="form-label">Bank <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="upd_bank" name="upd_bank">
                                        <option value="">Pilih...</option>
                                        <option value="BRI" {{ old('upd_bank', $payment->bank) == 'BRI' ? 'selected' : ''}}>BRI</option>
                                        <option value="BCA" {{ old('upd_bank', $payment->bank) == 'BCA' ? 'selected' : ''}}>BCA</option>
                                        <option value="Mandiri" {{ old('upd_bank', $payment->bank) == 'Mandiri' ? 'selected' : ''}}>Mandiri</option>
                                    </select>
                                    @error('upd_bank')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-span-12 lg:col-span-6 sm:mt-3">
                                    <label for="upd_bank_number" class="form-label">Nomor Rekening <span
                                            class="text-danger">*</span></label>
                                    <input id="upd_bank_number" name="upd_bank_number" type="number"
                                        class="form-control w-full" placeholder="Masukkan Nomor Rekening" required>
                                    @error('upd_bank_number')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="upd_bank_owner" class="form-label">Pemilik Bank <span
                                        class="text-danger">*</span></label>
                                <input id="upd_bank_owner" name="upd_bank_owner" type="text"
                                    class="form-control w-full" placeholder="Masukkan Atas Nama Rekening" required>
                                @error('upd_bank_owner')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="px-5 mt-3 pb-8 text-center">
                            <button type="submit" class="btn btn-primary w-24">Update</button>
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Payment Confirmation Modal -->

@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.method-select').forEach(function(select) {
                const id = select.getAttribute('data-id');
                const bankField = document.getElementById(`upd-bank-field-${id}`);

                function toggleBankField() {
                    if (select.value === 'Transfer') {
                        bankField.style.display = 'block';
                    } else {
                        bankField.style.display = 'none';
                    }
                }

                // Jalankan saat halaman dimuat
                toggleBankField();

                // Event ketika dropdown diubah
                select.addEventListener('change', toggleBankField);
            });
        });
    </script>
@endpush
