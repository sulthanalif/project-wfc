@extends('cms.layouts.app', [
    'title' => 'Cash Flow',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Cash Flow
    </h2>
    {{-- <div class="grid grid-cols-12 gap-6 mt-5">
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
    </div> --}}
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('cash-flow.create') }}" class="btn btn-primary shadow-md mr-2">Pindah Kas</a>
            {{-- <div class="dropdown mr-2">
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
            </div> --}}
            {{-- <button class="btn btn-primary shadow-md mr-2 ml-auto sm:ml-0"><span
                    class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="filter"></i>
                </span></button> --}}
            @include('cms.layouts.per-page')

            @if ($cashFlows instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $cashFlows->firstItem() }} hingga
                    {{ $cashFlows->lastItem() }} dari {{ $cashFlows->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ count($cashFlows) }} data
                </div>
            @endif
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Cari di halaman ini..." title="Hanya mencari data pada halaman yang sedang aktif" id="filter" value="{{ request('search') }}">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>
        <x-table.filter :filters="$filters" />
        <!-- BEGIN: Data List -->
        <x-table.wrapper>
            <x-slot:thead>
                <tr>
                    <th class="text-center whitespace-nowrap">#</th>
                    <th class="text-center whitespace-nowrap">NAMA BANK</th>
                    <th class="text-center whitespace-nowrap">PEMASUKAN</th>
                    <th class="text-center whitespace-nowrap">SETORAN</th>
                    <th class="text-center whitespace-nowrap">PENGELUARAN</th>
                    <th class="text-center whitespace-nowrap">PIUTANG</th>
                    <th class="text-center whitespace-nowrap">SALDO</th>
                </tr>
            </x-slot:thead>
            @if (empty($cashFlows))
                <x-table.empty colspan="7" />
            @else
                @foreach ($cashFlows as $cashFlow)
                    <tr class="intro-x">
                        <td>
                            <p class="font-medium whitespace-nowrap text-center">{{ $cashFlows instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($cashFlows->firstItem() + $loop->index) : $loop->iteration }}</p>
                        </td>
                        <td>
                            <span class="text-slate-500">{{ $cashFlow['method'] }}</span>
                        </td>
                        <td>
                            <p class="text-slate-500 flex items-center justify-center mr-3">Rp.
                                {{ number_format($cashFlow['incomes'], 0, ',', '.') }} </p>
                        </td>
                        <td>
                            <p class="text-slate-500 flex items-center justify-center mr-3">Rp.
                                {{ number_format($cashFlow['orders'], 0, ',', '.') }} </p>
                        </td>
                        <td>
                            <p class="text-slate-500 flex items-center justify-center mr-3">Rp.
                                {{ number_format($cashFlow['spendings'], 0, ',', '.') }} </p>
                        </td>
                        <td>
                            <p class="text-slate-500 flex items-center justify-center mr-3">Rp.
                                {{ number_format($cashFlow['loans'], 0, ',', '.') }} </p>
                        </td>
                        <td>
                            <p class="text-slate-500 flex items-center justify-center mr-3">Rp.
                                {{ number_format($cashFlow['balance'], 0, ',', '.') }} </p>
                        </td>
                    </tr>
                @endforeach
            @endif
        </x-table.wrapper>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        {{-- @if ($incomes instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                {{ $incomes->links('cms.layouts.paginate') }}
            </div>
        @endif --}}
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

            });
    </script>
@endpush
