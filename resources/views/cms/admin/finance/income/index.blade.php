@extends('cms.layouts.app', [
    'title' => 'Pemasukan',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Pemasukan
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('income.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Pemasukan</a>
            {{-- <div class="dropdown">
                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="plus"></i>
                    </span>
                </button>
                <div class="dropdown-menu w-40">
                    <ul class="dropdown-content">
                        <li>
                            <a href="{{ route('export.income') }}" class="dropdown-item"> <i data-lucide="download"
                                    class="w-4 h-4 mr-2"></i> Export </a>
                        </li>
                        <li>
                            <a href="javascript:;" class="dropdown-item" data-tw-toggle="modal"
                                data-tw-target="#import-confirmation-modal"> <i data-lucide="upload"
                                    class="w-4 h-4 mr-2"></i> Import </a>
                        </li>
                    </ul>
                </div>
            </div> --}}
            <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $incomes->firstItem() }} hingga
                {{ $incomes->lastItem() }} dari {{ $incomes->total() }} data</div>
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
                        <th class="whitespace-nowrap">NAMA PEMASUKAN</th>
                        <th class="whitespace-nowrap">JUMLAH</th>
                        <th class="whitespace-nowrap">METODE</th>
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
                                    <p class="text-slate-500 flex items-center mr-3">Rp.
                                        {{ number_format($income->amount, 0, ',', '.') }} </p>
                                </td>
                                <td>
                                    <span class="text-slate-500">
                                        {{ $income->method == 'tunai' ? 'Tunai' : 'Transfer' }}
                                        @if ($income->method == 'transfer')
                                            ({{ $income->bank }})
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="text-slate-500">{{ $income->date }}</span>
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
                                                    <input type="hidden" name="page"
                                                        value="{{ $incomes->currentPage() }}">
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
            {{ $incomes->links('cms.layouts.paginate') }}
        </div>
        <!-- END: Pagination -->
    </div>
@endsection
