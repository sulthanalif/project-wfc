@extends('cms.layouts.app', [
    'title' => 'Edit Komisi',
])

@section('content')

    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Edit Komisi
        </h2>
    </div>

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

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('commissions.update', $commission->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 lg:col-span-6 intro-y">
                            <div>
                                <label for="title" class="form-label">Judul Komisi <span class="text-danger">*</span></label>
                                <input id="title" name="title" type="text" class="form-control w-full"
                                    placeholder="Masukkan Judul Komisi" value="{{ old('title', $commission->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="package_id" class="form-label">Paket <span class="text-danger">*</span></label>
                                <select class="tom-select mt-2 sm:mr-2" id="package_id" name="package_id">
                                    <option disabled>Pilih Paket...</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}" {{ $package->id == $commission->package_id ? 'selected' : '' }}>
                                            {{ $package->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="term" class="form-label">Target <span class="text-danger">*</span></label>
                                <input id="term" name="term" type="number" class="form-control w-full"
                                    placeholder="Masukkan Target" value="{{ old('term', $commission->term) }}" required>
                                @error('term')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="reward" class="form-label">Bonus <span class="text-danger">*</span></label>
                                <input id="reward" name="reward" type="text" class="form-control w-full"
                                    placeholder="Masukkan Bonus" value="{{ old('reward', $commission->reward) }}" required>
                                @error('reward')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 intro-y mt-3 lg:mt-0">
                            <div>
                                <label>Deskripsi <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <textarea id="description" name="description" class="editor">
                                        {{ old('description', $commission->description) }}
                                    </textarea>
                                </div>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-left mt-5">
                        <button type="submit" class="btn btn-primary w-24">Update</button>
                        <a href="{{ route('commissions.index') }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                    </div>
                </form>
                <!-- END: Form Layout -->
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>
@endpush
