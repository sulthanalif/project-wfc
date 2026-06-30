@extends('cms.layouts.app', [
    'title' => 'Landing Page Benefit',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Landing Page Benefit
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('landingpage.benefit.update', $benefit) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 lg:col-span-6 intro-y">
                            <div>
                                <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                                <input id="title" name="title" type="text" value="{{ $benefit->title }}"
                                    class="form-control w-full" placeholder="Masukkan Nama Paket" required>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <label for="benefit_agen" class="form-label">Keuntungan Agen <span class="text-danger">*</span></label>
                                <div>
                                    <textarea id="benefit_agen" name="benefit_agen" class="editor" required>{{ $benefit->benefit_agen }}</textarea>
                                </div>
                                @error('benefit_agen')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 intro-y">
                            <div>
                                <label for="subtitle" class="form-label">Subjudul <span class="text-danger">*</span></label>
                                <input id="subtitle" name="subtitle" type="text" value="{{ $benefit->subtitle }}"
                                    class="form-control w-full" placeholder="Masukkan Subjudul" required>
                                @error('subtitle')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <label for="benefit_mitra" class="form-label">Keuntungan Mitra <span class="text-danger">*</span></label>
                                <div>
                                    <textarea id="benefit_mitra" name="benefit_mitra" class="editor" required>{{ $benefit->benefit_mitra }}</textarea>
                                </div>
                                @error('benefit_mitra')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Simpan</button>
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