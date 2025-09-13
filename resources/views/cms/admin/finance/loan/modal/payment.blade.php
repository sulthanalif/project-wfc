<!-- BEGIN: Payment Confirmation Modal -->
<div id="payment-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5">
                    <form action="{{ route('loan.storePayment', $loan) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mt-3 text-center">
                            <label class="form-label">Total Pembayaran</label>
                            <span class="font-bold"> Rp.
                                {{ number_format($loan->amount - $loan->loanPayments()->sum('pay'), 0, ',', '.') }}</span>
                        </div>

                        <div class="mt-3">
                            <label for="pay" class="form-label">Jumlah Pembayaran <span
                                    class="text-danger">*</span></label>
                            <input id="pay" name="pay" type="number"
                                value="{{ number_format($loan->amount - $loan->loanPayments()->sum('pay'), 0, ',', '') }}"
                                class="form-control w-full" placeholder="Masukkan Jumlah Pembayaran" required>
                            @error('pay')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-12 gap-3 mt-3">
                            <div class="col-span-6">
                                <label for="method" class="form-label">Metode Pembayaran <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="method" name="method" required>
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                                @error('method')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-span-6">
                                <label for="date" class="form-label">Tanggal <span
                                        class="text-danger">*</span></label>
                                <input id="date" name="date" type="date" class="form-control w-full" max="{{ date('Y-m-d') }}"
                                    required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3" id="bank-field" style="display: none">
                            <label for="bank" class="form-label">Bank <span class="text-danger">*</span></label>
                            <select class="form-select" id="bank" name="bank">
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
                        <div class="mt-3">
                            <label for="photo" class="form-label">Upload Bukti <span
                                    class="text-danger">*</span></label>
                            <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                <span id="fileName">
                                    <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                </span>
                                <input id="photo" name="photo" type="file"
                                    class="w-full h-full top-0 left-0 absolute opacity-0"
                                    onchange="previewFile(this); updateFileName(this)" required>
                            </div>
                            <div id="image-preview" class="hidden mt-2"></div>
                            @error('photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="description" class="form-label">Keterangan <span
                                    class="text-danger">*</span></label>
                            <textarea id="description" name="description" class="editor"> </textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="px-5 mt-3 py-3 text-center">
                            <button type="submit" class="btn btn-primary w-24">Setor</button>
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
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>

    <script>
        const methodSelect = document.getElementById('method');
        const bankField = document.getElementById('bank-field');

        methodSelect.addEventListener('change', (event) => {
            if (event.target.value === 'Transfer') {
                bankField.style.display = 'block';
            } else {
                bankField.style.display = 'none';
            }
        });

        const currentDate = '{{ now()->format('Y-m-d') }}'; // Blade templating to get current date
        const dateInput = document.getElementById('date');

        dateInput.value = currentDate;


        function previewFile(input) {
            const file = input.files[0];
            const preview = document.getElementById('image-preview');

            if (file) {
                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    alert("Ukuran gambar lebih dari 2MB. Silahkan pilih gambar yang lebih kecil");
                    preview.innerHTML = ''; // Clear any existing preview
                    preview.classList.add('hidden'); // Hide the preview container
                    input.value = ''; // Clear the file input value
                    return;
                }

                // Check file type (images only)
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const extension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(extension)) {
                    alert("Hanya file dengan tipe (jpg, jpeg, png) yang diperbolehkan!!");
                    preview.innerHTML = ''; // Clear any existing preview
                    preview.classList.add('hidden'); // Hide the preview container
                    input.value = ''; // Clear the file input value
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('w-auto', 'h-40', 'object-cover', 'rounded'); // Adjust size and styles as needed
                    preview.innerHTML = ''; // Clear previous previews
                    preview.classList.remove('hidden'); // Show the preview container
                    preview.appendChild(img);
                };

                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = ''; // Clear any existing preview
                preview.classList.add('hidden'); // Hide the preview container
            }
        }
    </script>
@endpush
