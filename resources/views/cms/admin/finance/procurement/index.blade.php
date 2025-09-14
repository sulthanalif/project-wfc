@extends('cms.layouts.app', [
    'title' => 'Pengadaan',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Pengadaan
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="credit-card" class="report-box__icon text-danger"></i>
                    </div>
                    <div class="text-2xl font-bold leading-8 mt-6">
                        {{-- Rp.{{ number_format($totaldata, 0, ',', '.') }} --}}
                        0
                    </div>
                    <div class="text-base text-slate-500 mt-1">Total Pengadaan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('procurement.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Pengadaan</a>
            <div class="dropdown">
                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="plus"></i>
                    </span>
                </button>
                <div class="dropdown-menu w-40">
                    <ul class="dropdown-content">
                        <li>
                            {{-- <a href="{{ route('data.export') }}" class="dropdown-item"> <i data-lucide="download"
                                    class="w-4 h-4 mr-2"></i> Export </a> --}}
                        </li>
                        {{-- <li>
                            <a href="javascript:;" class="dropdown-item" data-tw-toggle="modal"
                                data-tw-target="#import-confirmation-modal"> <i data-lucide="upload"
                                    class="w-4 h-4 mr-2"></i> Import </a>
                        </li> --}}
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

            {{-- @if ($procurements instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $procurements->firstItem() }} hingga
                    {{ $procurements->lastItem() }} dari {{ $procurements->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ $procurements->count() }} data
                </div>
            @endif --}}
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
                        <th class="text-center whitespace-nowrap">ITEM</th>
                        {{-- <th class="text-center whitespace-nowrap">HARGA</th> --}}
                        <th class="text-center whitespace-nowrap">SATUAN</th>
                        <th class="text-center whitespace-nowrap">JUMLAH</th>
                        <th class="text-center whitespace-nowrap">JUMLAH PENGADAAN</th>
                        <th class="text-center whitespace-nowrap">JUMLAH SISA</th>
                        {{-- <th class="text-center whitespace-nowrap">TOTAL</th>
                        <th class="text-center whitespace-nowrap">METODE</th>
                        <th class="text-center whitespace-nowrap">TANGGAL</th> --}}
                        {{-- <th class="text-center whitespace-nowrap">AKSI</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @if (!isset($procurements) || (is_array($procurements) && empty($procurements)) || (is_object($procurements) && $procurements->isEmpty()))
                        <tr>
                            <td colspan="8" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($procurements as $data)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <span class="text-slate-500">{{ $data['name'] }}</span>
                                </td>
                                <td align='center'>
                                    <span class="text-slate-500">{{ $data['unit'] }}</span>
                                </td>
                                {{-- <td>
                                    <p class="text-slate-500 flex items-center whitespace-nowrap">Rp.
                                        {{ number_format($data['price'], 0, ',', '.') }} </p>
                                </td> --}}
                                <td align='center'>
                                    <span class="text-slate-500">{{ $data['needed'] ?? 1 }}</span>
                                </td>
                                <td align='center'>
                                    <span class="text-slate-500">{{ $data['procurement'] ?? 0 }}</span>
                                </td>
                                <td align='center'>
                                    <span class="text-slate-500">{{ $data['remaining'] }}</span>
                                </td>
                                {{-- <td>
                                    <p class="text-slate-500 flex items-center whitespace-nowrap">Rp.
                                        {{ number_format($data->amount * ($data->qty ?? 1), 0, ',', '.') }} </p>
                                </td>
                                <td>
                                <span class="text-slate-500">
                                    {{ $data->method == 'tunai' ? 'Tunai' : 'Transfer' }}
                                    @if ($data->method == 'transfer')
                                        ({{ $data->bank }})
                                    @endif
                                </span>
                                </td>
                                <td>
                                    <span
                                        class="text-slate-500">{{ \Carbon\Carbon::parse($data->date)->format('d-m-Y') }}</span>
                                </td> --}}
                                {{-- <td class="table-report__action">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3" href="{{ route('procurement.edit', $data['id']) }}">
                                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                        <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal{{ $data['id'] }}"> <i
                                                data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                    </div>
                                </td> --}}
                            </tr>


                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="delete-confirmation-modal{{ $data['id'] }}" class="modal" tabindex="-1"
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
                                            {{-- <div class="px-5 pb-8 text-center">
                                                <form action="{{ route('data.destroy', $data) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    @if ($procurements instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                                        <input type="hidden" name="page"
                                                            value="{{ $procurements->currentPage() }}">
                                                    @endif
                                                    <button type="submit" class="btn btn-danger w-24">Hapus</button>
                                                    <button type="button" data-tw-dismiss="modal"
                                                        class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                </form>
                                            </div> --}}
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
        {{-- @if ($procurements instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                {{ $procurements->links('cms.layouts.paginate') }}
            </div>
        @endif --}}
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
        });
    </script>
@endpush
