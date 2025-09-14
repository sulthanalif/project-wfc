@extends('cms.layouts.app', [
    'title' => 'Akun Pengeluaran',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Akun Pengeluaran
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#create-modal"
                class="btn btn-primary shadow-md mr-2">Tambah Akun</a>

            <!-- BEGIN: Create Modal -->
            <div id="create-modal" class="modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('type-spending.storeOrUpdate') }}" method="post">
                            @csrf
                            <div class="p-5">
                                <div class="modal-header">
                                    <h2 class="font-medium text-base mr-auto">Tambah Akun Pengeluaran</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="text-start">
                                        <label for="name" class="form-label">Nama Akun <span
                                                class="text-danger">*</span></label>
                                        <input id="name" name="name" type="text" class="form-control w-full"
                                            placeholder="Masukkan Nama Akun" required>
                                    </div>
                                </div>
                            </div>
                            <div class="px-5 pb-8 text-center">
                                <button type="submit" class="btn btn-primary w-24">Simpan</button>
                                <button type="button" data-tw-dismiss="modal"
                                    class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END: Create Modal -->

            <div class="w-auto relative text-slate-500">
                <select id="records_per_page" class="form-control box">
                    <option value="10" {{ request()->get('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->get('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request()->get('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="all" {{ request()->get('perPage') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            @if ($spendingTypes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $spendingTypes->firstItem() }} hingga
                    {{ $spendingTypes->lastItem() }} dari {{ $spendingTypes->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ $spendingTypes->count() }} data
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
                        <th class="whitespace-nowrap">NAMA AKUN PENGELUARAN</th>
                        <th class="text-center whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($spendingTypes->isEmpty())
                        <tr>
                            <td colspan="3" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($spendingTypes as $type)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">
                                        @if ($spendingTypes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                            {{ $loop->iteration + $spendingTypes->perPage() * ($spendingTypes->currentPage() - 1) }}
                                        @else
                                            {{ $loop->iteration }}
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    <span class="text-slate-500">{{ $type->name }}</span>
                                </td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#edit-modal{{ $type->id }}"> <i data-lucide="edit"
                                                class="w-4 h-4 mr-1"></i> Ubah </a>
                                        <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal{{ $type->id }}"> <i
                                                data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                    </div>
                                </td>
                            </tr>
                            <!-- BEGIN: Edit Modal -->
                            <div id="edit-modal{{ $type->id }}" class="modal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('type-spending.storeOrUpdate') }}" method="post">
                                            @csrf
                                            <div class="p-5">
                                                <div class="modal-header">
                                                    <h2 class="font-medium text-base mr-auto">Ubah Akun Pengeluaran</h2>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="text" name="id" value="{{ $type->id }}"
                                                        hidden>
                                                    <div class="text-start">
                                                        <label for="name" class="form-label">Nama Akun <span
                                                                class="text-danger">*</span></label>
                                                        <input id="name" name="name" type="text"
                                                            class="form-control w-full" placeholder="Masukkan Nama Akun"
                                                            value="{{ old('name', $type->name) }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="px-5 pb-8 text-center">
                                                <button type="submit" class="btn btn-primary w-24">Simpan</button>
                                                <button type="button" data-tw-dismiss="modal"
                                                    class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Edit Modal -->

                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="delete-confirmation-modal{{ $type->id }}" class="modal" tabindex="-1"
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
                                                <form action="{{ route('type-spending.destroy', $type) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <input type="text" name="page" value="{{ $type->id }}"
                                                        hidden>
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
        @if ($spendingTypes instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <!-- BEGIN: Pagination -->
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                {{ $spendingTypes->appends(['perPage' => request()->get('perPage')])->links('cms.layouts.paginate') }}
            </div>
            <!-- END: Pagination -->
        @endif
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
