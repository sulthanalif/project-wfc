@extends('cms.layouts.app', [
    'title' => 'Katalog',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Katalog
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('catalog.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Katalog</a>
            <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $catalogs->firstItem() }} hingga
                {{ $catalogs->lastItem() }} dari {{ $catalogs->total() }} data</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search...">
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
                        <th class="whitespace-nowrap">NAMA KATALOG</th>
                        <th class="whitespace-nowrap">DESKRIPSI</th>
                        <th class="text-center whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($catalogs->isEmpty())
                        <tr>
                            <td colspan="4" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($catalogs as $catalog)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <a class="text-slate-500 flex items-center mr-3" href="javascript:;"
                                        data-tw-toggle="modal" data-tw-target="#detail-modal{{ $catalog->id }}"> <i
                                            data-lucide="external-link" class="w-4 h-4 mr-2"></i> {{ $catalog->name }} </a>
                                </td>
                                <!-- BEGIN: Detail Modal -->
                                <div id="detail-modal{{ $catalog->id }}" class="modal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="intro-x mr-auto">
                                                    <p class="font-bold text-lg truncate mr-5">
                                                        {{ $catalog->name }}
                                                    </p>
                                                </div>
                                                <div class="intro-x">
                                                    <button type="button" data-tw-dismiss="modal"
                                                        class="btn btn-outline-secondary mr-2">
                                                        <i data-lucide="x-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                {!! $catalog->description !!}
                                            </div>
                                            <div class="modal-footer">
                                                <div class="flex items-center"> <i data-lucide="clock"
                                                        class="w-4 h-4 mr-2"></i> Dibuat
                                                    {{ \Carbon\Carbon::parse($catalog->created_at)->format('d M Y, H:m:i') }}
                                                </div>
                                                <div class="flex items-center mt-2"> <i data-lucide="clock"
                                                        class="w-4 h-4 mr-2"></i> Diupdate
                                                    {{ \Carbon\Carbon::parse($catalog->updated_at)->format('d M Y, H:m:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Detail Modal -->
                                <td>
                                    <p class="text-slate-500 flex items-center mr-3"> {!! $catalog->description !!} </>
                                </td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3" href="{{ route('catalog.edit', $catalog) }}"> <i
                                                data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                        <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal{{ $catalog->id }}"> <i
                                                data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="delete-confirmation-modal{{ $catalog->id }}" class="modal" tabindex="-1"
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
                                                <form action="{{ route('catalog.destroy', $catalog) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <input type="hidden" name="page"
                                                        value="{{ $catalogs->currentPage() }}">
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
            {{ $catalogs->links('cms.layouts.paginate') }}
        </div>
        <!-- END: Pagination -->
    </div>
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
            <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> {{ session('error') }}
            <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i
                    data-lucide="x" class="w-4 h-4"></i> </button>
        </div>
    @endif
@endsection
