@extends('cms.layouts.app', [
    'title' => 'Paket',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Paket
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('package.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Paket</a>
            <div class="dropdown">
                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="plus"></i>
                    </span>
                </button>
                <div class="dropdown-menu w-40">
                    <ul class="dropdown-content">
                        <li>
                            <a href="{{ route('export.package') }}" class="dropdown-item"> <i data-lucide="download"
                                    class="w-4 h-4 mr-2"></i> Export </a>
                        </li>
                        <li>
                            <a href="javascript:;" class="dropdown-item" data-tw-toggle="modal"
                                data-tw-target="#import-confirmation-modal"> <i data-lucide="upload"
                                    class="w-4 h-4 mr-2"></i> Import </a>
                        </li>
                        <li>
                            <a href="{{ route('package.archive') }}" class="dropdown-item"> <i data-lucide="archive"
                                    class="w-4 h-4 mr-2"></i> Arsip </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $packages->firstItem() }} hingga
                {{ $packages->lastItem() }} dari {{ $packages->total() }} data</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="filter">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">#</th>
                        <th class="text-center whitespace-nowrap">NAMA PAKET</th>
                        <th class="text-center whitespace-nowrap">PERIODE</th>
                        <th class="text-center whitespace-nowrap">BATAS WAKTU</th>
                        <th class="whitespace-nowrap">FOTO</th>
                        <th class="text-center whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($packages->isEmpty())
                        <tr>
                            <td colspan="5" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($packages as $package)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <a class="text-slate-500 flex items-center mr-3"
                                        href="{{ route('package.show', $package) }}"> <i data-lucide="external-link"
                                            class="w-4 h-4 mr-2"></i> {{ $package->name }} </a>
                                </td>
                                <td>
                                    <p class="text-slate-500 whitespace-nowrap text-center">{{ $package->period->description }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 whitespace-nowrap text-center">{{ \Carbon\Carbon::parse($package->period->access_date)->format('d M Y') }}
                                    </p>
                                </td>
                                <td class="w-40">
                                    <div class="flex">
                                        <div class="w-10 h-10 image-fit zoom-in">
                                            @if ($package->image == null)
                                                <img alt="PAKET SMART WFC" class="tooltip rounded-full"
                                                    src="{{ asset('assets/logo2.png') }}" title="PAKET SMART WFC">
                                            @else
                                                <img alt="PAKET SMART WFC" class="tooltip rounded-full"
                                                    src="{{ route('getImage', ['path' => 'package', 'imageName' => $package->image]) }}"
                                                    title="@if ($package->created_at == $package->updated_at) Diupload {{ \Carbon\Carbon::parse($package->created_at)->format('d M Y, H:m:i') }}
                                                @else
                                                Diupdate {{ \Carbon\Carbon::parse($package->updated_at)->format('d M Y, H:m:i') }} @endif">
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3" href="{{ route('package.edit', $package) }}"> <i
                                                data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                        <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal{{ $package->id }}"> <i
                                                data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="delete-confirmation-modal{{ $package->id }}" class="modal" tabindex="-1"
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
                                                <form action="{{ route('package.destroy', $package) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <input type="hidden" name="page"
                                                        value="{{ $packages->currentPage() }}">
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
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            {{ $packages->links('cms.layouts.paginate') }}
        </div>
        <!-- END: Pagination -->
    </div>

    <!-- BEGIN: Delete Confirmation Modal -->
    <div id="import-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <form action="{{ route('import.package') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="p-5 text-center">
                            <div class="modal-header">
                                <h2 class="font-medium text-base mr-auto">Import Data</h2> <a
                                    href="{{ route('download.file', ['file' => 'format-paket.xlsx']) }}"
                                    class="btn btn-outline-secondary"> <i data-lucide="download"
                                        class="w-4 h-4 mr-2"></i> Download Format </a>
                            </div>
                            <div class="modal-body text-slate-500 mt-2">
                                <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                    <i data-lucide="file" class="w-4 h-4 mr-2"></i>
                                    <span id="fileName">
                                        <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                    </span>
                                    <input id="file" name="file" type="file"
                                        class="w-full h-full top-0 left-0 absolute opacity-0"
                                        onchange="updateFileName(this)" required>
                                </div>
                            </div>
                        </div>
                        <div class="px-5 pb-8 text-center">
                            <button type="submit" class="btn btn-primary w-24">Import</button>
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Delete Confirmation Modal -->
@endsection
