@extends('cms.layouts.app', [
    'title' => 'Tambah Distribusi',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Tambah Distribusi
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('distribution.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-12 lg:col-span-6">
                            <div>
                                <label for="distribution_number" class="form-label">Nomer Distribusi <span
                                        class="text-danger">*</span></label>
                                <input id="distribution_number" name="distribution_number" type="text"
                                    class="form-control w-full" value="{{ $distributionNumber }}" readonly>
                                @error('distribution_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="date" class="form-label">Tanggal Distribusi <span
                                        class="text-danger">*</span></label>
                                <input id="date" name="date" type="date" class="form-control w-full" required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="order_id" class="form-label">Pesanan <span class="text-danger">*</span></label>
                                <select class="form-select" id="order_id" name="order_id">
                                    <option value="">Pilih Pesanan...</option>
                                    @foreach ($datas as $order)
                                        <option value="{{ $order->id }}">{{ $order->order_number }} -
                                            {{ $order->agent->agentProfile->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 mt-3 lg:mt-0">
                            <div>
                                <label for="driver" class="form-label">Nama Pengemudi <span class="text-danger">*</span></label>
                                <input id="driver" name="driver" type="text" class="form-control w-full" required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
        
                            <div class="mt-3">
                                <label for="police_number" class="form-label">Nomer Polisi <span
                                        class="text-danger">*</span></label>
                                <input id="police_number" name="police_number" type="text" class="form-control w-full" required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Form Layout -->
    </div>
    </div>
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>
    <script>
        const currentDate = '{{ now()->format('Y-m-d') }}'; // Blade templating to get current date
        const dateInput = document.getElementById('date');

        dateInput.value = currentDate;
    </script>
@endpush
