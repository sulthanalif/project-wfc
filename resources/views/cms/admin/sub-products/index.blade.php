@extends('cms.layouts.app', [
    'title' => 'Sub Barang',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Sub Barang
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @hasrole('super_admin|admin')
                <a href="{{ route('sub-product.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Sub Barang</a>
                <div class="dropdown">
                    <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                        <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="plus"></i>
                        </span>
                    </button>
                    <div class="dropdown-menu w-40">
                        <ul class="dropdown-content">
                            <li>
                                <a href="" class="dropdown-item"> <i data-lucide="download"
                                        class="w-4 h-4 mr-2"></i> Export </a>
                            </li>
                            <li>
                                <a href="javascript:;" class="dropdown-item" data-tw-toggle="modal"
                                    data-tw-target="#import-confirmation-modal"> <i data-lucide="upload"
                                        class="w-4 h-4 mr-2"></i> Import </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $subProducts->firstItem() }} hingga
                    {{ $subProducts->lastItem() }} dari {{ $subProducts->total() }} data</div>
            @endhasrole
            <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
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
                        <th class="text-center whitespace-nowrap">NAMA SUB BARANG</th>
                        <th class="text-center whitespace-nowrap">SATUAN</th>
                        <th class="text-center whitespace-nowrap">HARGA</th>
                        @hasrole('super_admin|admin')
                            <th class="text-center whitespace-nowrap">AKSI</th>
                        @endhasrole
                    </tr>
                </thead>
                <tbody>
                    @if ($subProducts->isEmpty())
                        <tr>
                            <td colspan="5" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($subProducts as $subProduct)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <p class="whitespace-nowrap">{{ $subProduct->name }}</p>
                                </td>
                                <td>
                                    <p class="text-center">
                                        {{ $subProduct->unit }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-center">
                                        Rp. {{ number_format($subProduct->price, 0, ',', '.') }}
                                    </p>
                                </td>
                                @hasrole('super_admin|admin')
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center mr-3" href="{{ route('sub-product.edit', $subProduct) }}"> <i
                                                    data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                                    @if ($subProduct->product->isEmpty())
                                                    <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                                        data-tw-target="#delete-confirmation-modal{{ $subProduct->id }}"> <i
                                                            data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                                    @endif
                                        </div>
                                    </td>
                                @endhasrole
                            </tr>



                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="delete-confirmation-modal{{ $subProduct->id }}" class="modal" tabindex="-1"
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
                                                <form action="{{ route('sub-product.destroy', $subProduct) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <input type="hidden" name="page"
                                                        value="{{ $subProducts->currentPage() }}">
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
            {{ $subProducts->links('cms.layouts.paginate') }}
        </div>
        <!-- END: Pagination -->
    </div>

    <!-- BEGIN: Import Confirmation Modal -->
    <div id="import-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="p-5 text-center">
                            <div class="modal-header">
                                <h2 class="font-medium text-base mr-auto">Import Data</h2> <a
                                    href="{{ route('download.file', ['file' => 'format-subProduk.xlsx']) }}"
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
    <!-- END: Import Confirmation Modal -->
@endsection
