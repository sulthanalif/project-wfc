@extends('cms.layouts.app', [
    'title' => 'Pengembalian Barang',
])

@section('content')
    <div class="grid grid-cols-12 mt-12">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap">
            <h2 class="w-auto relative text-lg font-medium">
                Pengembalian Barang
            </h2>
            {{-- <div class="w-full xl:w-auto flex items-center mt-1 lg:mt-0 ml-auto gap-2">
                @hasrole('admin|super_admin')
                    <a href="{{ route('return.index', ['export' => 'true']) }}" class="btn btn-sm btn-primary"><i class="w-4 h-4"
                            data-lucide="download"></i> Export</a>
                @endhasrole
            </div> --}}
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
                        <th class="text-center whitespace-nowrap">NOMOR PENGEMBALIAN</th>
                        @hasrole('super_admin|admin')
                            <th class="text-center whitespace-nowrap">DARI AGEN</th>
                        @endhasrole
                        <th class="text-center whitespace-nowrap">TOTAL ITEM</th>
                        <th class="text-center whitespace-nowrap">STATUS PENGEMBALIAN</th>
                        <th class="text-center whitespace-nowrap">TANGGAL PENGAJUAN</th>
                        @hasrole('super_admin|admin')
                            <th class="text-center whitespace-nowrap">TANGGAL PENGEMBALIAN</th>
                        @endhasrole
                        <th class="text-center whitespace-nowrap">KETERANGAN</th>
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
                                <td>
                                    <a class="text-slate-500 flex items-center mr-3 font-bold"
                                        href="{{ route('return.show', $return->id) }}"> <i data-lucide="external-link"
                                            class="w-4 h-4 mr-2"></i> {{ $return->return_number }} </a>
                                </td>
                                @hasrole('super_admin|admin')
                                    <td class="text-center capitalize">
                                        <p class="font-normal whitespace-nowrap text-center">
                                            {{ $return->user->agentProfile->name ?? 'N/A' }}
                                        </p>
                                    </td>
                                @endhasrole
                                <td>
                                    <p class="font-normal whitespace-nowrap text-center">
                                        {{ $return->productReturnDetail->count() }} Item
                                    </p>
                                </td>
                                <td>
                                    @if ($return->status == 'pending')
                                        <div class="flex items-center justify-center text-warning"> <i data-lucide="clock"
                                                class="w-4 h-4 mr-2"></i> <strong>Pending</strong> </div>
                                    @elseif ($return->status == 'processed')
                                        <div class="flex items-center justify-center text-primary"> <i data-lucide="loader"
                                                class="w-4 h-4 mr-2"></i> <strong>Diproses</strong> </div>
                                    @elseif ($return->status == 'finished')
                                        <div class="flex items-center justify-center text-success"> <i
                                                data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                                            <strong>Selesai</strong>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center text-danger"> <i data-lucide="x-circle"
                                                class="w-4 h-4 mr-2"></i> <strong>Ditolak</strong> </div>
                                    @endif
                                </td>
                                <td>
                                    <p class="font-normal whitespace-nowrap text-center">
                                        {{ $return->date_in }}
                                    </p>
                                </td>
                                @hasrole('super_admin|admin')
                                    <td>
                                        <p class="font-normal whitespace-nowrap text-center">
                                            {{ $return->date_out ?? 'N/A' }}
                                        </p>
                                    </td>
                                @endhasrole
                                <td>
                                    <p class="font-normal whitespace-nowrap text-center">
                                        {!! $return->notes ?? 'N/A' !!}
                                    </p>
                                </td>
                                <td class="table-report__action w-56">
                                    @if ($return->status == 'finished')
                                        <div class="flex items-center justify-center text-success"> <i
                                                data-lucide="check-square" class="w-4 h-4 mr-2"></i> </div>
                                    @elseif ($return->status == 'pending' || $return->status == 'processed')
                                        @hasrole('super_admin|admin')
                                            <div class="flex justify-center items-center">
                                                <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal"
                                                    data-tw-target="#change-status-modal-{{ $return->id }}">
                                                    <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah Status </a>
                                            </div>
                                            @include('cms.return.modal.status', ['return' => $return])
                                        @endhasrole

                                        @hasrole('agent')
                                            <div class="flex items-center justify-center text-success"> <i
                                                    data-lucide="check-square" class="w-4 h-4 mr-2"></i> </div>
                                        @endhasrole
                                    @else
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center text-danger" href="javascript:;"
                                                data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal-{{ $return->id }}"> <i
                                                    data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                            @include('cms.return.modal.delete', ['return' => $return])
                                        </div>
                                    @endif
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
