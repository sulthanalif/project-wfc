@extends('cms.layouts.app', [
    'title' => 'Pemasukan',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Pemasukan
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="credit-card" class="report-box__icon text-primary"></i>
                    </div>
                    <div class="text-2xl font-bold leading-8 mt-6">Rp.
                        {{ number_format($totalIncome, 0, ',', '.') }}</div>
                    <div class="text-base text-slate-500 mt-1">Total Pemasukan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('income.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Pemasukan</a>
            <div class="dropdown mr-2">
                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4"
                            data-lucide="more-vertical"></i>
                    </span>
                </button>
                <div class="dropdown-menu w-40">
                    <ul class="dropdown-content">
                        <li>
                            <a href="{{ route('income.export') }}" class="dropdown-item"> <i data-lucide="download"
                                    class="w-4 h-4 mr-2"></i> Export </a>
                        </li>
                        <li>
                            <a href="javascript:;" class="dropdown-item" data-tw-toggle="modal"
                                data-tw-target="#filter-modal"> <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Urutkan
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            {{-- <button class="btn btn-primary shadow-md mr-2 ml-auto sm:ml-0"><span
                    class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="filter"></i>
                </span></button> --}}
            <div class="w-auto relative text-slate-500 ml-2">
                <select id="records_per_page" class="form-control box">
                    <option value="10" {{ request()->get('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->get('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request()->get('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="all" {{ request()->get('perPage') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            @if ($incomes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $incomes->firstItem() }} hingga
                    {{ $incomes->lastItem() }} dari {{ $incomes->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ $incomes->count() }} data
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
                        <th class="text-center whitespace-nowrap">NAMA PEMASUKAN</th>
                        <th class="text-center whitespace-nowrap">JUMLAH</th>
                        <th class="text-center whitespace-nowrap">METODE</th>
                        <th class="text-center whitespace-nowrap">TANGGAL</th>
                        <th class="text-center whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($incomes->isEmpty())
                        <tr>
                            <td colspan="6" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($incomes as $income)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <span class="text-slate-500">{{ $income->information }}</span>
                                </td>
                                <td>
                                    <p class="text-slate-500 flex items-center justify-center mr-3">Rp.
                                        {{ number_format($income->amount, 0, ',', '.') }} </p>
                                </td>
                                <td>
                                    <span class="text-slate-500 flex items-center justify-center">
                                        {{ $income->method == 'tunai' ? 'Tunai' : 'Transfer' }}
                                        @if ($income->method == 'transfer')
                                            ({{ $income->bank }})
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="text-slate-500 flex items-center justify-center">{{ \Carbon\Carbon::parse($income->date)->format('d-m-Y') }}</span>
                                </td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3" href="{{ route('income.edit', $income) }}"> <i
                                                data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                        <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal{{ $income->id }}"> <i
                                                data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="delete-confirmation-modal{{ $income->id }}" class="modal" tabindex="-1"
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
                                                <form action="{{ route('income.destroy', $income) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    @if ($incomes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                                        <input type="hidden" name="page"
                                                            value="{{ $incomes->currentPage() }}">
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
        @if ($incomes instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                {{ $incomes->links('cms.layouts.paginate') }}
            </div>
        @endif
        <!-- END: Pagination -->
    </div>

    <!-- BEGIN: Filter Confirmation Modal -->
    <div id="filter-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <form id="filter-form">
                        <div class="modal-header flex items-center justify-end">
                            <button type="button" id="export_button" class="btn btn-outline-secondary"><i
                                    data-lucide="download" class="w-4 h-4 mr-2"></i> Download</button>
                        </div>
                        <div class="p-5 text-center">
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 lg:col-span-6 intro-y">
                                    <div>
                                        <label for="start_date" class="form-label">Tanggal Awal <span
                                                class="text-danger">*</span></label>
                                        <input id="start_date" name="start_date" type="date"
                                            class="form-control w-full" placeholder="Masukkan Tanggal Awal"
                                            value="{{ old('start_date') }}" required>
                                        @error('start_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-span-12 lg:col-span-6 intro-y mt-3 lg:mt-0">
                                    <div>
                                        <label for="end_date" class="form-label">Tanggal Akhir <span
                                                class="text-danger">*</span></label>
                                        <input id="end_date" name="end_date" type="date" class="form-control w-full"
                                            placeholder="Masukkan Tanggal Akhir" value="{{ old('end_date') }}" required>
                                        @error('end_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-5 pb-5 text-center">
                            <button type="button" id="filter_button" class="btn btn-primary w-24">Urutkan</button>
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Filter Confirmation Modal -->
@endsection

@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filter_button').addEventListener('click', function() {
                // Get the start and end dates
                const startDateInput = document.getElementById('start_date').value;
                const endDateInput = document.getElementById('end_date').value;

                if (!startDateInput || !endDateInput) {
                    alert('Silakan masukkan tanggal awal dan akhir.');
                    return;
                }

                const startDate = new Date(startDateInput);
                const endDate = new Date(endDateInput);

                const rows = document.querySelectorAll('.table tbody tr');

                rows.forEach(row => {
                    const dateCell = row.querySelector('td:nth-child(5)');
                    if (dateCell) {
                        const dateText = dateCell.textContent.trim();
                        const [day, month, year] = dateText.split('-').map(num => parseInt(num,
                            10));
                        const rowDate = new Date(year, month - 1, day);

                        if (rowDate >= startDate && rowDate <= endDate) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });

            document.getElementById('export_button').addEventListener('click', function() {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;

                if (!startDate || !endDate) {
                    alert('Please select both start and end dates.');
                    return;
                }

                const url = `{{ route('income.export') }}?start_date=${startDate}&end_date=${endDate}`;
                window.location.href = url;
            });

            document.getElementById('records_per_page').addEventListener('change', function() {
                const perPage = this.value;
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('perPage', perPage);
                window.location.search = urlParams.toString();
            });
        });
    </script>
@endpush
