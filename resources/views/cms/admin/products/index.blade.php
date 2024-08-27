@extends('cms.layouts.app', [
    'title' => 'Barang',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Barang
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('product.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Barang</a>
            <div class="dropdown">
                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="plus"></i>
                    </span>
                </button>
                <div class="dropdown-menu w-40">
                    <ul class="dropdown-content">
                        <li>
                            <a href="{{ route('export.product') }}" class="dropdown-item"> <i data-lucide="download"
                                    class="w-4 h-4 mr-2"></i> Export </a>
                        </li>
                        <li>
                            <a href="javascript:;" class="dropdown-item" data-tw-toggle="modal"
                                data-tw-target="#import-confirmation-modal"> <i data-lucide="upload"
                                    class="w-4 h-4 mr-2"></i> Import </a>
                        </li>
                        <li>
                            <a href="{{ route('product.archive') }}" class="dropdown-item"> <i data-lucide="archive"
                                    class="w-4 h-4 mr-2"></i> Arsip </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-auto relative text-slate-500 ml-2">
                <select id="records_per_page" class="form-control box">
                    <option value="10" {{ request()->get('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->get('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request()->get('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="all" {{ request()->get('perPage') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $products->firstItem() }} hingga
                    {{ $products->lastItem() }} dari {{ $products->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ $products->count() }} data
                </div>
            @endif
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
                        <th class="text-center whitespace-nowrap">NAMA BARANG</th>
                        <th class="text-center whitespace-nowrap">HARGA</th>
                        <th class="text-center whitespace-nowrap">PAKET</th>
                        <th class="text-center whitespace-nowrap">PERIODE</th>
                        <th class="text-center whitespace-nowrap">FOTO</th>
                        <th class="text-center whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($products->isEmpty())
                        <tr>
                            <td colspan="7" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($products as $product)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <a class="text-slate-500 flex items-center mr-3"
                                        href="{{ route('product.show', $product) }}"> <i data-lucide="external-link"
                                            class="w-4 h-4 mr-2"></i> {{ $product->name }}
                                        {{ $product->is_safe_point == 1 ? '(Titik Aman)' : '' }}
                                    </a>
                                </td>
                                <td>
                                    <p class="text-slate-500 text-center">Rp.
                                        {{ number_format($product->price, 0, ',', '.') }}/hari </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 text-center">{{ $product->package->package->name }}</p>
                                </td>
                                <td>
                                    <p class="text-slate-500 whitespace-nowrap text-center">
                                        {{ $product->package->package->period->description }}
                                    </p>
                                </td>
                                <td class="w-40">
                                    <div class="flex items-center justify-center">
                                        <div class="w-10 h-10 image-fit zoom-in">
                                            @if ($product->detail->image == 'image.jpg' || $product->detail->image == null)
                                                <img alt="PAKET SMART WFC" class="rounded-full"
                                                    src="{{ asset('assets/logo2.png') }}">
                                            @else
                                                <img alt="PAKET SMART WFC" class="tooltip rounded-full"
                                                    src="{{ route('getImage', ['path' => 'product', 'imageName' => $product->detail->image]) }}"
                                                    title="@if ($product->created_at == $product->updated_at) Diupload {{ \Carbon\Carbon::parse($product->created_at)->format('d M Y, H:m:i') }} @else Diupdate {{ \Carbon\Carbon::parse($product->updated_at)->format('d M Y, H:m:i') }} @endif">
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3" href="{{ route('product.edit', $product) }}"> <i
                                                data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                        <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal{{ $product->id }}"> <i
                                                data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                    </div>
                                </td>
                            </tr>


                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="delete-confirmation-modal{{ $product->id }}" class="modal" tabindex="-1"
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
                                                <form action="{{ route('product.destroy', $product) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                                        <input type="hidden" name="page"
                                                            value="{{ $products->currentPage() }}">
                                                    @endif
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
        @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                {{ $products->links('cms.layouts.paginate') }}
            </div>
        @endif
        <!-- END: Pagination -->
    </div>

    <!-- BEGIN: Import Confirmation Modal -->
    <div id="import-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <form action="{{ route('import.product') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="p-5 text-center">
                            <div class="modal-header">
                                <h2 class="font-medium text-base mr-auto">Import Data</h2> <a
                                    href="{{ route('download.file', ['file' => 'format-produk.xlsx']) }}"
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

@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('records_per_page').addEventListener('change', function() {
                const perPage = this.value;
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('perPage', perPage);
                window.location.search = urlParams.toString();
            });
        });
    </script>
@endpush
