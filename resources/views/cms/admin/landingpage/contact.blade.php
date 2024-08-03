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
                                <div class="mt-3">
                                    <span class="text-danger">Catatan Map Url</span>
                                    <img id="thumbnail" src="{{ asset('assets/cms/images/map-url.jpeg') }}"
                                        data-tw-toggle="modal" data-tw-target="#fullscreen-modal"
                                        class="w-auto h-40 object-cover rounded cursor-pointer">
                                </div>

                                <!-- Fullscreen Modal -->
                                <div id="fullscreen-modal" class="modal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <button type="button" class="absolute top-4 right-4 text-white text-3xl"
                                                data-tw-dismiss="modal">×</button>
                                            <img src="{{ asset('assets/cms/images/map-url.jpeg') }}" class="w-full h-full rounded">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="text-left mt-5 flex flex-row">
                            <button type="submit" class="btn btn-primary w-24">Simpan</button>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-24 ml-1">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END: Form Layout -->
        </div>
    </div>

    <div class="grid grid-cols-12 mt-5">
        <div class="intro-y col-span-12">
            <div class="intro-y box p-5">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6">
                        <label>Daftar Nomor Kontak</label>
                    </div>
                    <div class="col-span-6">
                        <a href="javascript:;" class="flex items-center ml-auto text-primary" data-tw-toggle="modal"
                            data-tw-target="#create-confirmation-modal"> <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            Tambah </a>
                    </div>
                </div>
                <div class="overflow-auto lg:overflow-visible">
                    <table class="table mt-2">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Nomor</th>
                                <th scope="col" class="text-center">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($contact->numbers->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada kontak yang dapat ditampilkan</td>
                                </tr>
                            @else
                                @foreach ($contact->numbers as $number)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="!py-3.5">
                                            <p class="font-medium whitespace-nowrap text-slate-500 flex items-center mr-3">
                                                {{ $number->description }}</p>
                                        </td>
                                        <td>
                                            <p class="text-slate-500 flex items-center">{{ $number->number }}</p>
                                        </td>
                                        <td class="w-56">
                                            <div class="flex justify-center items-center">
                                                <a class="flex items-center mr-3" href="javascript:;"
                                                    data-tw-toggle="modal"
                                                    data-tw-target="#edit-confirmation-modal{{ $number->id }}"> <i
                                                        data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                                <a class="flex items-center mr-3 text-danger" href="javascript:;"
                                                    data-tw-toggle="modal"
                                                    data-tw-target="#delete-confirmation-modal{{ $number->id }}">
                                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                            </div>
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
                                                            <button type="submit"
                                                                class="btn btn-danger w-24">Hapus</button>
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
                                                            <input type="hidden" value="{{ $number->id }}"
                                                                name="id">
                                                            <div class="mt-3">
                                                                <label for="description" class="form-label">Keterangan
                                                                    <span class="text-danger">*</span></label>
                                                                <input type="text" id="description" name="description"
                                                                    class="form-control"
                                                                    placeholder="Masukkan Keterangan..."
                                                                    value="{{ old('description', $number->description) }}"
                                                                    required>
                                                                @error('description')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="mt-3">
                                                                <label for="number" class="form-label">Nomer <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" class="form-control w-full"
                                                                    id="number" name="number"
                                                                    placeholder="Masukkan Nomor.."
                                                                    value="{{ old('number', $number->number) }}" required>
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
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
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
                                <input type="text" id="description" name="description" class="form-control"
                                    placeholder="Masukkan Keterangan..." required>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="number" class="form-label">Nomer <span class="text-danger">*</span></label>
                                <input type="number" class="form-control w-full" id="number" name="number"
                                    placeholder="Masukkan Nomor.." required>
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Create Confirmation Modal -->
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>
@endpush
