@extends('cms.layouts.app', [
    'title' => 'Tambah Supplier',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Tambah Supplier
        </h2>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
            <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> {{ session('error') }}
            <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x"
                    class="w-4 h-4"></i> </button>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('supplier.store') }}" method="post">
                    @csrf
                    <div>
                        <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input id="name" name="name" type="text" class="form-control w-full"
                            placeholder="Masukkan Nama Supplier" required>
                    </div>
                    <div class="mt-3">
                        <label>Alamat Lengkap <span class="text-danger">*</span></label>
                        <div class="mt-2">
                            <textarea id="address" name="address" class="editor">
                                    Masukkan Alamat Lengkap
                                </textarea>
                        </div>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="phone_number" class="form-label">No Telepon <span class="text-danger">*</span></label>
                        <input id="phone_number" name="phone_number" type="number" class="form-control w-full"
                            placeholder="Masukkan Nomor Telepon" minlength="11" maxlength="13" required>
                    </div>
                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                    </div>
                </form>
            </div>
            <!-- END: Form Layout -->
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>
@endpush
