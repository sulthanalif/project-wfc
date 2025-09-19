@extends('cms.layouts.app', [
    'title' => 'Pengembalian Barang',
])

@section('content')
    <div class="grid grid-cols-12 mt-12">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap">
            <h2 class="w-auto relative text-lg font-medium">
                Pengembalian Barang
            </h2>
            <div class="w-full xl:w-auto flex items-center mt-1 lg:mt-0 ml-auto gap-2">
                @hasrole('admin|super_admin')
                    <a href="{{ route('return.index', ['export' => 'true']) }}" class="btn btn-sm btn-primary"><i class="w-4 h-4"
                            data-lucide="download"></i> Export</a>
                @endhasrole
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @hasrole('super_admin|admin|agent')
                <a href="{{ route('return.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Pengembalian</a>
            @endhasrole
            <div class="w-auto relative text-slate-500 mr-2">
                <select id="records_per_page" class="form-control box">
                    <option value="10" {{ request()->get('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->get('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request()->get('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="all" {{ request()->get('perPage') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>
            <div class="w-auto relative text-slate-500 mt-2 lg:mt-0">
                <select id="records_per_status" class="form-control box">
                    <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="finished" {{ request()->get('status') == 'finished' ? 'selected' : '' }}>Selesai
                    </option>
                    <option value="processed" {{ request()->get('status') == 'processed' ? 'selected' : '' }}>Proses
                    </option>
                    <option value="rejected" {{ request()->get('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="all" {{ request()->get('status') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            @if ($returns instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $returns->firstItem() }} hingga
                    {{ $returns->lastItem() }} dari {{ $returns->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ $returns->count() }} data
                </div>
            @endif
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
                        @hasrole('super_admin|admin')
                            <th class="text-center whitespace-nowrap">DARI AGEN</th>
                        @endhasrole
                        <th class="text-center whitespace-nowrap">NOMOR PESANAN</th>
                        <th class="text-center whitespace-nowrap">PAKET</th>
                        <th class="text-center whitespace-nowrap">ITEM</th>
                        <th class="text-center whitespace-nowrap">STATUS ITEM</th>
                        <th class="text-center whitespace-nowrap">STATUS PENGEMBALIAN</th>
                        <th class="text-center whitespace-nowrap">TANGGAL PENGAJUAN</th>
                        @hasrole('super_admin|admin')
                            <th class="text-center whitespace-nowrap">TANGGAL PENGEMBALIAN</th>
                        @endhasrole
                        <th class="text-center whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($returns->isEmpty())
                        <tr>
                            <td colspan="10" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($returns as $return)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                @hasrole('super_admin|admin')
                                    <td class="text-center capitalize">
                                        Nama Pembuat Pengajuan
                                    </td>
                                @endhasrole
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">
                                        Nomor Pesanan
                                    </p>
                                </td>
                                <td class="text-center">
                                    <p class="font-normal whitespace-nowrap text-center">
                                        Paket yang Dikembalikan
                                    </p>
                                </td>
                                <td class="text-center">
                                    <p class="font-normal whitespace-nowrap text-center">
                                        Item yang Dikembalikan
                                    </p>
                                </td>
                                <td>
                                    <div class="flex items-center justify-center text-success"> <i
                                            data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Diterima </div>
                                </td>
                                <td>
                                    <div class="flex items-center justify-center text-success"> <i
                                            data-lucide="check-square" class="w-4 h-4 mr-2"></i> Lunas </div>
                                </td>
                                <td>
                                    <p class="font-normal whitespace-nowrap text-center">
                                        Tanggal Pengajuan
                                    </p>
                                </td>
                                @hasrole('super_admin|admin')
                                    <td>
                                        <p class="font-normal whitespace-nowrap text-center">
                                            Tanggal Pengembalian
                                        </p>
                                    </td>
                                @endhasrole
                                <td class="table-report__action w-56">

                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->

        <!-- BEGIN: Pagination -->
        @if ($returns instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                {{ $returns->links('cms.layouts.paginate') }}
            </div>
        @endif
        <!-- END: Pagination -->
    </div>
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
            document.getElementById('records_per_status').addEventListener('change', function() {
                const status = this.value;
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('status', status);
                window.location.search = urlParams.toString();
            });
        });
    </script>
@endpush
