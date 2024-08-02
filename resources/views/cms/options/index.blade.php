@extends('cms.layouts.app', [
    'title' => 'Pengaturan',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Pengaturan
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="intro-y box p-5">
                <div class="mt-3">
                    <h2 class="text-lg font-medium">Periode</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Tanggal Mulai</th>
                                <th scope="col">Tanggal Akhir</th>
                                {{-- <th scope="col">Status</th> --}}
                                <th scope="col">
                                    <a href="javascript:;" class="flex items-center ml-auto text-primary"
                                        data-tw-toggle="modal" data-tw-target="#create-confirmation-modal"> <i
                                            data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($periods as $period)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $period->description }}</td>
                                    <td>{{ $period->start_date }}</td>
                                    <td>{{ $period->end_date }}</td>
                                    {{-- <td>
                                        @if ($period->is_active)
                                            <p class="text-success">Aktif</p>
                                        @else
                                        <a class="flex items-center mr-3 text-danger" href="javascript:;"
                                            data-tw-toggle="modal"
                                            data-tw-target="#active-confirmation-modal{{ $period->id }}">
                                             Tidak Aktif </a>
                                        @endif
                                    </td> --}}
                                    <td class="flex">
                                        <a class="flex items-center mr-3 text-warning" href="javascript:;"
                                            data-tw-toggle="modal"
                                            data-tw-target="#edit-confirmation-modal{{ $period->id }}">
                                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit </a>
                                        <a class="flex items-center mr-3 text-danger" href="javascript:;"
                                            data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal{{ $period->id }}">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                    </td>
                                </tr>
                                <!-- BEGIN: Delete Confirmation Modal -->
                                {{-- <div id="active-confirmation-modal{{ $period->id }}" class="modal" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <div class="p-5 text-center">
                                                    <i data-lucide="check-circle"
                                                        class="w-16 h-16 text-success mx-auto mt-3"></i>
                                                    <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                                    <div class="text-slate-500 mt-2">
                                                        Apakah anda yakin untuk aktifkan periode ini?
                                                        <br>
                                                        Proses tidak akan bisa diulangi.
                                                    </div>
                                                </div>
                                                <div class="px-5 pb-8 text-center">
                                                    <form action="{{ route('activatePeriod') }}" method="post">
                                                        @csrf
                                                        @method('post')
                                                        <input type="hidden" name="id" value="{{ $period->id }}">
                                                        <button type="submit" class="btn btn-primary w-24">{{ $period->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                                        <button type="button" data-tw-dismiss="modal"
                                                            class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div id="delete-confirmation-modal{{ $period->id }}" class="modal" tabindex="-1"
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
                                                    <form action="{{ route('deletePeriod', $period) }}" method="post">
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
                                <div id="edit-confirmation-modal{{ $period->id }}" class="modal" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <div class="p-5">
                                                    <form action="{{ route('addOrUpdatePeriod') }}" method="post"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="mt-3">
                                                            <input type="hidden" id="id" name="id" value="{{ $period->id }}">
                                                            <label for="description" class="form-label">Keterangan <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" value="{{ $period->description }}" id="description" name="description"
                                                                class="form-control">
                                                            @error('description')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="mt-3">
                                                            <label for="start_date" class="form-label">Tanggal Awal <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" value="{{ $period->start_date }}" id="start_date" name="start_date"
                                                                class="form-control">
                                                            @error('start_date')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="mt-3">
                                                            <label for="end_date" class="form-label">Tanggal Akhir <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" value="{{ $period->end_date }}" id="end_date" name="end_date"
                                                                class="form-control">
                                                            @error('end_date')
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
                        <form action="{{ route('addOrUpdatePeriod') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mt-3">
                                <label for="description" class="form-label">Keterangan <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="description" name="description" class="form-control">
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="start_date" class="form-label">Tanggal Awal <span
                                        class="text-danger">*</span></label>
                                <input type="date" id="start_date" name="start_date" class="form-control">
                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="end_date" class="form-label">Tanggal Akhir <span
                                        class="text-danger">*</span></label>
                                <input type="date" id="end_date" name="end_date" class="form-control">
                                @error('end_date')
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
