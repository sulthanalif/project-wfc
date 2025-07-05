<!-- BEGIN: Payment Confirmation Modal -->
<div id="update-detail-confirmation-modal{{ $payment->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Ubah Detail Pembayaran</h2>
            </div>
            <div class="modal-body p-0">
                <div class="p-5">
                    <form action="{{ route('updatePaymentAgent', $payment) }}" method="post"
                        enctype="multipart/form-data">
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

                        <div class="mt-3">
                            <label for="photo" class="form-label">Upload Bukti <span class="text-danger">(Jangan
                                    ubah jika tidak ingin diganti)</span></label>
                            <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                <span id="fileName">
                                    <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                </span>
                                @php $inputId = 'photo_' . $payment->id; @endphp

                                <input id="{{ $inputId }}" name="photo" type="file"
                                    class="w-full h-full top-0 left-0 absolute opacity-0"
                                    onchange="previewFile(this, '{{ $payment->id }}'); updateFileName(this)">
                            </div>
                            <div id="image-preview-{{ $payment->id }}" class="hidden mt-2"></div>
                            @if (isset($payment->photo))
                                <div class="mt-2" id="existing-image-preview-{{ $payment->id }}">
                                    <img src="{{ asset('storage/images/proofs/' . $payment->photo) }}"
                                        class="w-auto h-40 object-fit-cover rounded">
                                </div>
                            @endif
                            @error('photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
        function previewFile(input, id) {
            const file = input.files[0];
            const preview = document.getElementById('image-preview-' + id);
            const existing = document.getElementById('existing-image-preview-' + id);

            if (file) {
                // Validasi ukuran
                if (file.size > 2 * 1024 * 1024) {
                    alert("Ukuran gambar lebih dari 2MB. Silakan pilih gambar lain.");
                    preview.innerHTML = '';
                    preview.classList.add('hidden');
                    input.value = '';
                    return;
                }

                // Validasi ekstensi
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const extension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(extension)) {
                    alert("Hanya file JPG, JPEG, atau PNG yang diperbolehkan.");
                    preview.innerHTML = '';
                    preview.classList.add('hidden');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('w-auto', 'h-40', 'object-cover', 'rounded');
                    existing.innerHTML = '';
                    preview.innerHTML = '';
                    preview.classList.remove('hidden');
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
                preview.classList.add('hidden');
            }
        }

        // Fungsi tampilkan nama file
        function updateFileName(input) {
            const fileNameSpan = input.parentElement.querySelector('#fileName');
            if (input.files.length > 0) {
                fileNameSpan.innerHTML = '<span class="text-primary mr-1">' + input.files[0].name + '</span>';
            }
        }

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
