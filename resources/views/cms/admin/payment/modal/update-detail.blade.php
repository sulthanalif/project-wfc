<!-- BEGIN: Payment Confirmation Modal -->
<div id="update-detail-confirmation-modal{{ $payment->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Ubah Detail Pembayaran</h2>
            </div>
            <div class="modal-body p-0">
                <div class="p-5">
                    <form action="{{ route('updatePayment', $payment) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div>
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
                            <div class="mt-3">
                                <label for="upd_bank" class="form-label">Bank <span class="text-danger">*</span></label>
                                <select class="form-select" id="upd_bank" name="upd_bank">
                                    <option value="">Pilih...</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}"
                                            {{ old('upd_bank', $payment->bank_owner_id) == $bank->id ? 'selected' : '' }}>
                                            {{ $bank->name }} - {{ $bank->account_number }}
                                            ({{ $bank->account_name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('upd_bank')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="px-5 mt-3 py-3 text-center">
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
