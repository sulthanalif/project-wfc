@extends('cms.layouts.app', ['title' => 'Edit Profil'])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Edit Profil
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 lg:col-span-6">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">
                <div id="form-validation" class="p-5">
                    <div class="preview">
                        <!-- BEGIN: Validation Form -->
                        <form  method="POST" action="{{ route('users.profile.update', ["id" => auth()->user()->id]) }}">
                            @method('PUT')
                            @csrf
                            {{-- <input type="hidden" name="id" id="id" value="{{ auth()->user()->id }}"> --}}
                            {{-- <div class="input-form">
                                <label for="name" class="form-label w-full flex flex-col sm:flex-row"> Nama Lengkap
                                </label>
                                <input id="name" type="text" name="name" class="form-control"
                                    placeholder="Isikan Nama Lengkap" minlength="2" required
                                    value="{{ $profile['name'] }}">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> --}}
                            <div class="input-form mt-3">
                                <label for="address" class="form-label w-full flex flex-col sm:flex-row"> Alamat
                                    Lengkap</label>
                                <textarea id="address" class="form-control" name="address" placeholder="Type your comments" minlength="10" required>{{ $profile['address']}}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary mt-5 mr-1">Simpan</button>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mt-5">Kembali</a>
                        </form>
                        <!-- END: Validation Form -->
                        <!-- BEGIN: Success Notification Content -->
                        <div id="success-notification-content" class="toastify-content hidden flex">
                            <i class="text-success" data-lucide="check-circle"></i>
                            <div class="ml-4 mr-4">
                                <div class="font-medium">Profil berhasil diubah!</div>
                                {{-- <div class="text-slate-500 mt-1"> Please check your e-mail for further info! </div> --}}
                            </div>
                        </div>
                        <!-- END: Success Notification Content -->
                        <!-- BEGIN: Failed Notification Content -->
                        <div id="failed-notification-content" class="toastify-content hidden flex">
                            <i class="text-danger" data-lucide="x-circle"></i>
                            <div class="ml-4 mr-4">
                                <div class="font-medium">Profil gagal diubah!</div>
                                <div class="text-slate-500 mt-1"> Please check the fileld form. </div>
                            </div>
                        </div>
                        <!-- END: Failed Notification Content -->
                    </div>
                </div>
            </div>
            <!-- END: Form Validation -->
        </div>
    </div>
@endsection
