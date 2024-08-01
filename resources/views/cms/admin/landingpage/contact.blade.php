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
                <form action="{{ route('landingpage.contact.update', $contact) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 lg:col-span-6 intro-y">
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
                                <label for="subTitle" class="form-label">Sub Judul <span
                                        class="text-danger">*</span></label>
                                <input id="subTitle" name="subTitle" type="text" value="{{ $contact->subTitle }}"
                                    class="form-control w-full" placeholder="Masukkan Nama Paket" required>
                                @error('subTitle')
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
                                <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <textarea id="address" name="address" class="editor" placeholder="Masukkan Deskripsi Paket" required>{{ $contact->address }}</textarea>
                                </div>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="text-left mt-5">
                                <button type="submit" class="btn btn-primary w-24">Simpan</button>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 mt-3 lg:mt-0">
                            <div>
                                <label for="mapUrl" class="form-label">Map URL Google <span
                                        class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <textarea id="mapUrl" name="mapUrl" class="editor" placeholder="Masukkan Deskripsi Paket" required>{{ $contact->mapUrl }}</textarea>
                                </div>
                                @error('mapUrl')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                </form>
                <div class="mt-3">
                    <label>Nomer </label>
                    <table class="table ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Poin</th>
                                <th scope="col">Poin</th>
                                <th scope="col">
                                    <a href="javascript:;" class="flex items-center ml-auto text-primary"
                                        data-tw-toggle="modal" data-tw-target="#create-confirmation-modal"> <i
                                            data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contact->numbers as $number)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $number->description }}</td>
                                    <td>{{ $number->number }}</td>
                                    <td class="flex">
                                        <a class="flex items-center mr-3 text-warning" href="javascript:;"
                                            data-tw-toggle="modal"
                                            data-tw-target="#edit-confirmation-modal{{ $number->id }}">
                                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit </a>
                                        <a class="flex items-center mr-3 text-danger" href="javascript:;"
                                            data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal{{ $number->id }}">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                    </td>
                                </tr>
                                <!-- BEGIN: Delete Confirmation Modal -->
                                <div id="delete-confirmation-modal{{ $number->id }}" class="modal" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <div class="p-5 text-center">
                                                    <i data-lucide="x-circle"
                                                        class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                                    <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                                    <div class="text-slate-500 mt-2">
                                                        Apakah anda yakin untuk menghapus data ini?
                                                        <br>
                                                        Proses tidak akan bisa diulangi.
                                                    </div>
                                                </div>
                                                <div class="px-5 pb-8 text-center">
                                                    <form
                                                        action="{{ route('landingpage.contact.deleteNumber', $number) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger w-24">Hapus</button>
                                                        <button type="button" data-tw-dismiss="modal"
                                                            class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Delete Confirmation Modal -->
                                <div id="edit-confirmation-modal{{ $number->id }}" class="modal" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <div class="p-5">
                                                    <form
                                                        action="{{ route('landingpage.contact.addOrUpdateNumber', $contact) }}"
                                                        method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" value="{{ $number->id }}" name="id" >
                                                        <div class="mt-3">
                                                            <label for="description" class="form-label">Keterangan <span
                                                                    class="text-danger">*</span></label>
                                                            <textarea id="description" name="description" class="form-control" placeholder="Masukkan Keterangan...">
                                                                {{ $number->description }}
                                                            </textarea>
                                                            @error('description')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="mt-3">
                                                            <label for="number" class="form-label">Nomer <span
                                                                    class="text-danger">*</span></label>
                                                            <textarea id="number"  name="number" class="form-control" placeholder="Masukkan Nomer...">
                                                                {{ $number->number }}
                                                            </textarea>
                                                            @error('number')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="px-5 mt-3 pb-8 text-center">
                                                            <button type="submit"
                                                                class="btn btn-primary w-24">Simpan</button>
                                                            <button type="button" data-tw-dismiss="modal"
                                                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                        </div>
                                                </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                </div>
                @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <!-- END: Form Layout -->
    </div>
    </div>
    <!-- BEGIN: Create Confirmation Modal -->
    <div id="create-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5">
                        <form action="{{ route('landingpage.contact.addOrUpdateNumber', $contact) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mt-3">
                                <label for="description" class="form-label">Keterangan <span
                                        class="text-danger">*</span></label>
                                <textarea id="description" name="description" class="form-control" placeholder="Masukkan Keterangan..."> </textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="number" class="form-label">Nomer <span class="text-danger">*</span></label>
                                <textarea id="number" name="number" class="form-control" placeholder="Masukkan Nomer..."> </textarea>
                                @error('number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="px-5 mt-3 pb-8 text-center">
                                <button type="submit" class="btn btn-primary w-24">Simpan</button>
                                <button type="button" data-tw-dismiss="modal"
                                    class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Create Confirmation Modal -->
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>
@endpush
