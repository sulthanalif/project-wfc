@extends('cms.layouts.app', [
    'title' => 'Tambah Pengadaan',
])

@section('content')
@if ($errors->any())
    <div class="intro-y mt-5">
        <div class="alert alert-danger show mb-2" role="alert">
            <div class="flex items-center">
                <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i>
                <div>
                    <h4 class="font-medium">Terjadi Kesalahan!</h4>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Formulir Tambah Pengadaan
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <form action="{{ route('procurement.store') }}" method="post" id="procurement-form">
                @csrf
                <div class="intro-y box p-5">
                    {{-- SECTION: DETAIL PENGADAAN --}}
                    <div>
                        <h3 class="text-base font-medium">Detail Pengadaan</h3>
                        <div class="grid grid-cols-12 gap-4 mt-4">
                            {{-- KOLOM KIRI --}}
                            <div class="col-span-12 lg:col-span-6 intro-y">
                                <div>
                                    <label for="date" class="form-label">Tanggal Pengadaan <span class="text-danger">*</span></label>
                                    <input id="date" name="date" type="date" class="form-control w-full" value="{{ date('Y-m-d') }}" required>
                                    @error('date')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label for="item" class="form-label">Pilih Item untuk Ditambahkan</label>
                                    <select class="tom-select" id="item" placeholder="Pilih item...">
                                        <option value="">Pilih item...</option>
                                        @foreach ($datasubs as $sub)
                                            <option value="{{ $sub['id'] }}" data-unit="{{ $sub['unit'] }}">{{ $sub['name'] }} (Sisa: {{ $sub['remaining'] }} {{ $sub['unit'] }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- KOLOM KANAN --}}
                            <div class="col-span-12 lg:col-span-6 intro-y">
                                <div>
                                    <label for="method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                    <select class="form-select" id="method" name="method" required>
                                        <option disabled selected value="">Pilih metode...</option>
                                        <option value="transfer">Transfer</option>
                                        <option value="tunai">Tunai</option>
                                    </select>
                                </div>
                                <div class="mt-4" id="bank-field" style="display: none;">
                                    <label for="bank" class="form-label">Bank <span class="text-danger">*</span></label>
                                    <select class="form-select" id="bank" name="bank">
                                        <option disabled selected value="">Pilih bank...</option>
                                        <option value="BRI">BRI</option>
                                        <option value="BCA">BCA</option>
                                        <option value="Mandiri">Mandiri</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6">

                    {{-- SECTION: ITEM YANG DIPILIH --}}
                    <div>
                        <h3 class="text-base font-medium">Daftar Item yang Akan Diadakan</h3>
                        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-4">
                            <table class="table table-report -mt-2">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-left">NAMA ITEM</th>
                                        <th class="w-48">JUMLAH</th>
                                        <th class="w-24">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody id="product-item">
                                    <tr id="empty-row">
                                        <td colspan="3" class="text-center text-slate-500 py-4">
                                            Belum ada item yang dipilih. Silakan pilih dari dropdown di atas.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- SECTION: TOMBOL AKSI --}}
                    <div class="text-right mt-8 border-t border-slate-200/60 pt-5">
                        <a href="{{ route('spending.index') }}" class="btn btn-outline-secondary w-24 mr-1">Batal</a>
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
                    </div>
                </div>
            </form>
            </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===================================================================
            // Data & Variabel Global
            // ===================================================================
            const allItems = @json($datasubs);
            const itemSelect = document.getElementById('item');
            const productTableBody = document.getElementById('product-item');
            const emptyRow = document.getElementById('empty-row');
            const tomSelectInstance = itemSelect.tomselect;

            // ===================================================================
            // Fungsi untuk Mengelola Tampilan Placeholder Tabel
            // ===================================================================
            function updateTablePlaceholder() {
                // Cek apakah ada baris item (selain empty-row) di tbody
                const itemCount = productTableBody.querySelectorAll('tr:not(#empty-row)').length;
                if (itemCount > 0) {
                    emptyRow.style.display = 'none'; // Sembunyikan jika ada item
                } else {
                    emptyRow.style.display = 'table-row'; // Tampilkan jika kosong
                }
            }

            // ===================================================================
            // Event Listener untuk Menambahkan Item ke Tabel
            // ===================================================================
            itemSelect.addEventListener('change', function() {
                const selectedId = this.value;

                if (!selectedId) return;

                const selectedItem = allItems[selectedId];

                // Cek duplikat
                if (document.querySelector(`tr[data-id="${selectedId}"]`)) {
                    alert('Item ini sudah ditambahkan.');
                    tomSelectInstance.setValue('');
                    return;
                }

                // Buat baris baru
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-id', selectedId);
                newRow.classList.add('intro-x'); // Animasi masuk

                // Kolom Nama
                const nameCell = document.createElement('td');
                nameCell.textContent = selectedItem.name;

                // Kolom Jumlah
                const qtyCell = document.createElement('td');
                qtyCell.classList.add('flex', 'items-center', 'justify-center'); // Styling flexbox
                const qtyInput = document.createElement('input');
                qtyInput.type = 'number';
                qtyInput.name = `items[${selectedId}]`;
                qtyInput.classList.add('form-control', 'w-24');
                qtyInput.placeholder = 'Qty';
                qtyInput.min = 1;
                qtyInput.required = true;

                qtyInput.value = selectedItem.remaining; // Otomatis isi jumlah dengan sisa
                qtyInput.max = selectedItem.remaining;   // Batasi jumlah maksimal

                const unitSpan = document.createElement('span');
                unitSpan.textContent = selectedItem.unit;
                unitSpan.classList.add('ml-2', 'font-medium');

                qtyCell.appendChild(qtyInput);
                qtyCell.appendChild(unitSpan);

                // Kolom Tombol Hapus
                const actionCell = document.createElement('td');
                actionCell.classList.add('text-center');
                const deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm');
                deleteBtn.innerHTML = 'Hapus';
                deleteBtn.onclick = function() {
                    newRow.remove();
                    updateTablePlaceholder(); // Cek kembali placeholder setelah menghapus
                };
                actionCell.appendChild(deleteBtn);

                // Gabungkan semua
                newRow.appendChild(nameCell);
                newRow.appendChild(qtyCell);
                newRow.appendChild(actionCell);
                productTableBody.appendChild(newRow);

                // Update tampilan placeholder dan reset dropdown
                updateTablePlaceholder();
                tomSelectInstance.setValue('');
                tomSelectInstance.blur(); // Hilangkan fokus dari select
            });

            // ===================================================================
            // Event Listener untuk Metode Pembayaran
            // ===================================================================
            const methodSelect = document.getElementById('method');
            const bankField = document.getElementById('bank-field');
            const bankSelect = document.getElementById('bank');

            methodSelect.addEventListener('change', (event) => {
                if (event.target.value === 'transfer') {
                    bankField.style.display = 'block';
                    bankSelect.required = true;
                } else {
                    bankField.style.display = 'none';
                    bankSelect.required = false;
                }
            });

            // Panggil sekali di awal untuk inisialisasi
            updateTablePlaceholder();
        });
    </script>
@endpush
