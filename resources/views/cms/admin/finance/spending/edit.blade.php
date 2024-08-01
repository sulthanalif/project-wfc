@extends('cms.layouts.app', [
    'title' => 'Ubah Pengeluaran',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Ubah Pengeluaran
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('spending.update', $spending->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 lg:col-span-6 intro-y">
                            <div>
                                <label for="information" class="form-label">Nama Pengeluaran <span class="text-danger">*</span></label>
                                <input id="information" name="information" type="text" class="form-control w-full"
                                    placeholder="Masukkan Nama Pengeluaran" value="{{ old('information', $spending->information) }}" required>
                                @error('information')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="date" class="form-label">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                                <input id="date" name="date" type="date" class="form-control w-full"
                                    placeholder="Masukkan Jumlah Pengeluaran" value="{{ old('date', $spending->date) }}" required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="spending_type_id" class="form-label">Kategori Pengeluaran <span class="text-danger">*</span></label>
                                <select class="tom-select" id="spending_type_id" name="spending_type_id" required>
                                    <option disabled selected>Pilih...</option>
                                    @foreach ($spendingTypes as $spendingType)
                                        <option value="{{ $spendingType->id }}" {{ old('spending_type_id', $spending->spending_type_id) == $spendingType->id ? 'selected' : '' }}>{{ $spendingType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 intro-y mt-3 lg:mt-0">
                            <div class="">
                                <label for="amount" class="form-label">Jumlah Pengeluaran <span class="text-danger">*</span></label>
                                <input id="amount" name="amount" type="number" class="form-control w-full"
                                    placeholder="Masukkan Jumlah Pengeluaran" value="{{ old('amount', $spending->amount) }}" required>
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="method" class="form-label">Metode <span class="text-danger">*</span></label>
                                <select class="form-select" id="method" name="method" required>
                                    <option disabled selected>Pilih...</option>
                                    <option value="transfer" {{ old('method', $spending->method) == 'transfer' ? 'selected' : ''}}>Transfer</option>
                                    <option value="tunai" {{ old('method', $spending->method) == 'tunai' ? 'selected' : ''}}>Tunai</option>
                                </select>
                            </div>
                            <div class="mt-3" id="bank-field" style="display: none">
                                <label for="bank" class="form-label">Bank <span class="text-danger">*</span></label>
                                <select class="form-select" id="bank" name="bank">
                                    <option disabled selected>Pilih...</option>
                                    <option value="BRI" {{ old('bank', $spending->bank) == 'BRI' ? 'selected' : ''}}>BRI</option>
                                    <option value="BCA" {{ old('bank', $spending->bank) == 'BCA' ? 'selected' : ''}}>BCA</option>
                                    <option value="Mandiri" {{ old('bank', $spending->bank) == 'Mandiri' ? 'selected' : ''}}>Mandiri</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
                        <a href="{{ route('income.index') }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
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
            if (event.target.value === 'transfer') {
                bankField.style.display = 'block';
            } else {
                bankField.style.display = 'none';
            }
        });
    </script>
@endpush
