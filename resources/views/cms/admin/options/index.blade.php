@extends('cms.layouts.app', [
    'title' => 'Periode',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Periode
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="javascript:;" class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal"
                data-tw-target="#create-confirmation-modal">Tambah Paket</a>
            <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $periods->firstItem() }} hingga
                {{ $periods->lastItem() }} dari {{ $periods->total() }} data</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="filter">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">#</th>
                        <th class="text-center whitespace-nowrap">KETERANGAN</th>
                        <th class="text-center whitespace-nowrap">TANGGAL MULAI</th>
                        <th class="text-center whitespace-nowrap">TANGGAL AKHIR</th>
                        <th class="text-center whitespace-nowrap">BATAS WAKTU</th>
                        <th class="text-center whitespace-nowrap">STATUS</th>
                        <th class="text-center whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($periods->isEmpty())
                        <tr>
                            <td colspan="7" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($periods as $period)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center flex items-center justify-center">
                                        {{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <p class="font-bold whitespace-nowrap flex items-center">{{ $period->description }}</p>
                                </td>
                                <td>
                                    <p class="text-slate-500 whitespace-nowrap flex items-center justify-center">
                                        {{ \Carbon\Carbon::parse($period->start_date)->format('d-m-Y') }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 whitespace-nowrap flex items-center justify-center">
                                        {{ \Carbon\Carbon::parse($period->end_date)->format('d-m-Y') }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 whitespace-nowrap flex items-center justify-center">
                                        {{ \Carbon\Carbon::parse($period->access_date)->format('d-m-Y') }}
                                    </p>
                                </td>
                                <td>
                                    @if ($period->is_active)
                                    <a class="flex items-centerjustify-center text-success" href="javascript:;"
                                            data-tw-toggle="modal"
                                            data-tw-target="#active-confirmation-modal{{ $period->id }}"><i
                                                data-lucide="check-square" class="w-4 h-4 mr-2"></i>
                                            Aktif </a>
                                    @else
                                        <a class="flex items-centerjustify-center text-danger" href="javascript:;"
                                            data-tw-toggle="modal"
                                            data-tw-target="#active-confirmation-modal{{ $period->id }}"><i
                                                data-lucide="x-square" class="w-4 h-4 mr-2"></i>
                                            Tidak Aktif </a>
                                    @endif
                                </td>
                                <td class="flex">
                                    <a class="flex items-center mr-3" href="javascript:;"
                                        data-tw-toggle="modal" data-tw-target="#edit-confirmation-modal{{ $period->id }}">
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                    <a class="flex items-center mr-3 text-danger" href="javascript:;" data-tw-toggle="modal"
                                        data-tw-target="#delete-confirmation-modal{{ $period->id }}">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                </td>
                            </tr>
                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="active-confirmation-modal{{ $period->id }}" class="modal" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body p-0">
                                            <div class="p-5 text-center">
                                                <i data-lucide="alert-circle"
                                                    class="w-16 h-16 text-warning mx-auto mt-3"></i>
                                                <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                                <div class="text-slate-500 mt-2">
                                                    Apakah anda yakin untuk {{ $period->is_active ? 'Nonaktifkan' : 'Aktifkan' }} periode ini?
                                                    <br>
                                                    Proses tidak akan bisa diulangi.
                                                </div>
                                            </div>
                                            <div class="px-5 pb-8 text-center">
                                                <form action="{{ route('activatePeriod') }}" method="post">
                                                    @csrf
                                                    @method('post')
                                                    <input type="hidden" name="id" value="{{ $period->id }}">
                                                    <button type="submit"
                                                        class="btn btn-{{ $period->is_active ? 'danger' : 'primary' }} w-24">{{ $period->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                                    <button type="button" data-tw-dismiss="modal"
                                                        class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="delete-confirmation-modal{{ $period->id }}" class="modal" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body p-0">
                                            <div class="p-5 text-center">
                                                <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
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
                                                        <input type="hidden" id="id" name="id"
                                                            value="{{ $period->id }}">
                                                        <label for="description" class="form-label">Keterangan <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $period->description }}"
                                                            id="description" name="description" class="form-control">
                                                        @error('description')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="mt-3">
                                                        <label for="start_date" class="form-label">Tanggal Awal <span
                                                                class="text-danger">*</span></label>
                                                        <input type="date" value="{{ $period->start_date }}"
                                                            id="start_date" name="start_date" class="form-control">
                                                        @error('start_date')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="mt-3">
                                                        <label for="end_date" class="form-label">Tanggal Akhir <span
                                                                class="text-danger">*</span></label>
                                                        <input type="date" value="{{ $period->end_date }}"
                                                            id="end_date" name="end_date" class="form-control">
                                                        @error('end_date')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="mt-3">
                                                        <label for="access_date" class="form-label">Batas Waktu <span
                                                                class="text-danger">*</span></label>
                                                        <input type="date" value="{{ $period->access_date }}"
                                                            id="access_date" name="access_date" class="form-control">
                                                        @error('access_date')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="px-5 mt-3 text-center">
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
                            <div class="mt-3">
                                <label for="access_date" class="form-label">Batas Waktu <span
                                        class="text-danger">*</span></label>
                                <input type="date" id="access_date" name="access_date" class="form-control">
                                @error('access_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="px-5 mt-3 text-center">
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
