@extends('cms.layouts.app', [
    'title' => 'Tambah Pengeluaran',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Tambah Pengeluaran
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('spending.store') }}" method="post">
                    @csrf
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 lg:col-span-6 intro-y">
                            <div>
                                <label for="information" class="form-label">Nama Pengeluaran <span
                                        class="text-danger">*</span></label>
                                <input id="information" name="information" type="text" class="form-control w-full"
                                    placeholder="Masukkan Nama Pengeluaran" required>
                                @error('information')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="date" class="form-label">Tanggal Pengeluaran <span
                                        class="text-danger">*</span></label>
                                <input id="date" name="date" type="date" class="form-control w-full"
                                    placeholder="Masukkan Jumlah Pengeluaran" required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="spending_type_id" class="form-label">Kategori Pengeluaran <span
                                        class="text-danger">*</span></label>
                                <select class="tom-select" id="spending_type_id" name="spending_type_id" required>
                                    <option disabled selected>Pilih...</option>
                                    @foreach ($spendingTypes as $spendingType)
                                        <option value="{{ $spendingType->id }}">{{ $spendingType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 intro-y mt-3 lg:mt-0">
                            <div class="">
                                <label for="amount" class="form-label">Harga Pengeluaran (Satuan) <span
                                        class="text-danger">*</span></label>
                                <input id="amount" name="amount" type="number" class="form-control w-full"
                                    placeholder="Masukkan Harga Pengeluaran (Satuan)" required>
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="qty" class="form-label">Jumlah Yang Dibeli (Qty) </label>
                                <input id="qty" name="qty" type="number" class="form-control w-full"
                                    placeholder="Masukkan Jumlah Yang Dibeli">
                                @error('qty')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="method" class="form-label">Metode <span class="text-danger">*</span></label>
                                <select class="form-select" id="method" name="method" required>
                                    <option disabled selected>Pilih...</option>
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                            </div>
                            <div class="mt-3" id="bank-field" style="display: none">
                                <label for="bank" class="form-label">Bank <span class="text-danger">*</span></label>
                                <select class="form-select" id="bank" name="bank">
                                    <option disabled selected>Pilih...</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }} -
                                            {{ $bank->account_number }} ({{ $bank->account_name }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
                        <a href="{{ route('spending.index') }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                    </div>
                </form>
                <!-- END: Form Layout -->
            </div>
        </div>
    </div>
    </div>
@endsection

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
    </script>
@endpush
