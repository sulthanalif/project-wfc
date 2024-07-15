@extends('cms.layouts.app', [
    'title' => 'Landing Page Contact',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Landing Page Contact
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <form action="{{ route('landingpage.contact.update', $contact) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div>
                        <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                        <input id="title" name="title" type="text" value="{{ $contact->title }}"
                            class="form-control w-full" placeholder="Masukkan Nama Paket" required>
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="subTitle" class="form-label">Sub Judul <span class="text-danger">*</span></label>
                        <input id="subTitle" name="subTitle" type="text" value="{{ $contact->subTitle }}"
                            class="form-control w-full" placeholder="Masukkan Nama Paket" required>
                        @error('subTitle')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="mt-3">
                        <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <div class="mt-2">
                            <textarea id="address" name="address" class="form-control" placeholder="Masukkan Deskripsi Paket" required>{{ $contact->address }}</textarea>
                        </div>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input id="email" name="email" type="text" value="{{ $contact->email }}"
                            class="form-control w-full" placeholder="Masukkan Nama Paket" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="phoneNumber" class="form-label">Nomer Telp <span class="text-danger">*</span></label>
                        <input id="phoneNumber" name="phoneNumber" type="text" value="{{ $contact->phoneNumber }}"
                            class="form-control w-full h-auto" placeholder="Masukkan Nama Paket" required>
                        @error('phoneNumber')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <label for="mapUrl" class="form-label">Map URL Google <span class="text-danger">*</span></label>
                        <div class="mt-2">
                            <textarea id="mapUrl" name="mapUrl" class="form-control" placeholder="Masukkan Deskripsi Paket" required>{{ $contact->mapUrl }}</textarea>
                        </div>
                        @error('mapUrl')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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
