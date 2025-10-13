@extends('cms.layouts.app', [
    'title' => 'Transfer Kas',
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
            Formulir Transfer Kas
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <form action="{{ route('cash-flow.store') }}" method="post" id="cash-flow-form">
                @csrf
                <div class="intro-y box p-5">
                    <div>
                        <h3 class="text-base font-medium">Detail Transfer</h3>
                        <div class="grid grid-cols-12 gap-4 mt-4">
                            <div class="col-span-12 lg:col-span-6 intro-y">
                                <div>
                                    <label for="from_bank_id" class="form-label">Dari Bank <span class="text-danger">*</span></label>
                                    <select class="tom-select sm:mr-2" id="from_bank_id" name="from_bank_id" required>
                                        <option disabled selected value="">Pilih bank asal...</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }} - {{ $bank->account_number }}</option>
                                        @endforeach
                                    </select>
                                    @error('from_bank_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label for="to_bank_id" class="form-label">Ke Bank <span class="text-danger">*</span></label>
                                    <select class="tom-select mt-2 sm:mr-2" id="to_bank_id" name="to_bank_id" required>
                                        <option disabled selected value="">Pilih bank tujuan...</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }} - {{ $bank->account_number }}</option>
                                        @endforeach
                                    </select>
                                    @error('to_bank_id')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-span-12 lg:col-span-6 intro-y">
                                <div>
                                    <label for="amount" class="form-label">Jumlah Transfer <span class="text-danger">*</span></label>
                                    <input id="amount" name="amount" type="number" class="form-control w-full" placeholder="Masukkan jumlah transfer..." min="1" required>
                                    @error('amount')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label for="description" class="form-label">Keterangan</label>
                                    <textarea id="description" name="description" class="form-control w-full" placeholder="Masukkan keterangan transfer..."></textarea>
                                    @error('description')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-8 border-t border-slate-200/60 pt-5">
                        <a href="{{ route('cash-flow.index') }}" class="btn btn-outline-secondary w-24 mr-1">Batal</a>
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
            const fromBankSelect = document.getElementById('from_bank_id');
            const toBankSelect = document.getElementById('to_bank_id');

            // Prevent selecting same bank for source and destination
            fromBankSelect.addEventListener('change', function() {
                Array.from(toBankSelect.options).forEach(option => {
                    option.disabled = option.value === this.value;
                });
            });

            toBankSelect.addEventListener('change', function() {
                Array.from(fromBankSelect.options).forEach(option => {
                    option.disabled = option.value === this.value;
                });
            });
        });
    </script>
@endpush
