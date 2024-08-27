@extends('cms.layouts.app', [
    'title' => 'Users',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Users
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @hasrole('super_admin')
                <a href="{{ route('user.create') }}" class="btn btn-primary shadow-md mr-2">Tambah User</a>
                <div class="dropdown">
                    <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                        <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="plus"></i>
                        </span>
                    </button>
                    <div class="dropdown-menu w-40">
                        <ul class="dropdown-content">
                            <li>
                                <a href="{{ route('export.agent') }}" class="dropdown-item"> <i data-lucide="download"
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
            @endhasrole
            <div class="w-auto relative text-slate-500 ml-2">
                <select id="records_per_page" class="form-control box">
                    <option value="10" {{ request()->get('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->get('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request()->get('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="all" {{ request()->get('perPage') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $users->firstItem() }} hingga
                    {{ $users->lastItem() }} dari {{ $users->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ $users->count() }} data
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
                        <th class="whitespace-nowrap">NAMA USER</th>
                        <th class="text-center whitespace-nowrap">JABATAN</th>
                        <th class="text-center whitespace-nowrap">STATUS</th>
                        @hasrole('super_admin|admin')
                            <th class="text-center whitespace-nowrap">AKSI</th>
                        @endhasrole
                    </tr>
                </thead>
                <tbody>
                    @if ($users->isEmpty())
                        <tr>
                            <td colspan="6" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($users as $user)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td class="!py-3.5">
                                    <div class="flex items-center">
                                        <div class="w-9 h-9 image-fit zoom-in">
                                            <img alt="PAKET SMART WFC" class="rounded-lg border-white shadow-md tooltip"
                                                src="{{ empty($user->agentProfile->photo) ? asset('assets/cms/images/profile.svg') : route('getImage', ['path' => 'photos', 'imageName' => $user->agentProfile->photo]) }}"
                                                title="{{ empty($user->agentProfile->phone_number) ? 'Nomer HP Belum Diisi' : $user->agentProfile->phone_number }}">
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('user.show', $user) }}"
                                                class="font-medium whitespace-nowrap">
                                                @if ($user->roles->first()->name == 'agent')
                                                    {{ $user->agentProfile ? $user->agentProfile->name : $user->email }}
                                                @else
                                                    {{ $user->adminProfile ? $user->adminProfile->name : $user->email }}
                                                @endif
                                            </a>
                                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center capitalize">
                                    @if ($user->roles->first()->name == 'super_admin')
                                        Super Admin
                                    @elseif ($user->roles->first()->name == 'admin')
                                        Admin
                                    @elseif ($user->roles->first()->name == 'finance_admin')
                                        Admin Keuangan
                                    @else
                                        Agen
                                    @endif
                                </td>
                                <td class="w-40">
                                    @if ($user->active == 1)
                                        <div class="flex items-center justify-center text-success"> <i
                                                data-lucide="check-square" class="w-4 h-4 mr-2"></i> Aktif </div>
                                    @else
                                        <div class="flex items-center justify-center text-danger"> <i data-lucide="x-square"
                                                class="w-4 h-4 mr-2"></i> Tidak Aktif</div>
                                    @endif
                                </td>
                                @if ($user->roles->first()->name == 'agent')
                                    @hasrole('super_admin|admin')
                                        @if ($user->active == 1)
                                            <td class="table-report__action w-56">
                                                <div class="flex justify-center items-center">
                                                    <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal"
                                                        data-tw-target="#upstat-confirmation-modal{{ $user->id }}"> <i
                                                            data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah Status </a>
                                                    @hasrole('super_admin')
                                                        <a class="flex items-center text-danger" href="javascript:;"
                                                            data-tw-toggle="modal"
                                                            data-tw-target="#delete-confirmation-modal{{ $user->id }}"> <i
                                                                data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                                    @endhasrole
                                                </div>
                                            </td>
                                        @else
                                            <td class="table-report__action w-56">
                                                <div class="flex justify-center items-center">
                                                    <a class="flex items-center mr-3"
                                                        href="{{ route('getAdministration', $user) }}"> <i data-lucide="edit"
                                                            class="w-4 h-4 mr-1"></i> Cek Administrasi </a>
                                                    @hasrole('super_admin')
                                                        <a class="flex items-center text-danger" href="javascript:;"
                                                            data-tw-toggle="modal"
                                                            data-tw-target="#delete-confirmation-modal{{ $user->id }}"> <i
                                                                data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                                    @endhasrole
                                                </div>
                                            </td>
                                        @endif
                                    @endhasrole
                                @else
                                    @hasrole('admin')
                                        <td class="table-report__action w-56">
                                            <div class="flex justify-center items-center">
                                                <span class="flex items-center mr-3"> Bukan Agent </span>
                                            </div>
                                        </td>
                                    @endhasrole
                                    @hasrole('super_admin')
                                        <td class="table-report__action w-56">
                                            <div class="flex justify-center items-center">
                                                <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal"
                                                    data-tw-target="#upstat-confirmation-modal{{ $user->id }}"> <i
                                                        data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah Status </a>
                                                <a class="flex items-center text-danger" href="javascript:;"
                                                    data-tw-toggle="modal"
                                                    data-tw-target="#delete-confirmation-modal{{ $user->id }}"> <i
                                                        data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                            </div>
                                        </td>
                                    @endhasrole
                                @endif
                            </tr>

                            <!-- BEGIN: Update Status Confirmation Modal -->
                            <div id="upstat-confirmation-modal{{ $user->id }}" class="modal" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body p-0">
                                            <div class="p-5 text-center">
                                                <i data-lucide="x-circle" class="w-16 h-16 text-warning mx-auto mt-3"></i>
                                                <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                                <div class="text-slate-500 mt-2">
                                                    Apakah anda yakin untuk mengubah status data ini?
                                                    <br>
                                                    Proses tidak akan bisa diulangi.
                                                </div>
                                            </div>
                                            <div class="px-5 pb-8 text-center">
                                                <form action="{{ route('user.status', $user) }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                    @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                                        <input type="hidden" name="page"
                                                            value="{{ $users->currentPage() }}">
                                                    @endif
                                                    {{-- <input type="hidden" name="active" value {{ ($user->active == 1) ? 0 : 1 }}> --}}
                                                    <button type="submit" class="btn btn-warning w-24">Ubah</button>
                                                    <button type="button" data-tw-dismiss="modal"
                                                        class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Update Status Confirmation Modal -->

                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="delete-confirmation-modal{{ $user->id }}" class="modal" tabindex="-1"
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
                                                <form action="{{ route('user.destroy', $user) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                                        <input type="hidden" name="page"
                                                            value="{{ $users->currentPage() }}">
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
        @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                {{ $users->links('cms.layouts.paginate') }}
            </div>
        @endif
        <!-- END: Pagination -->
    </div>
    <!-- BEGIN: Import Confirmation Modal -->
    <div id="import-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <form action="{{ route('import.agent') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="p-2 text-center">
                            <div class="modal-header">
                                <h2 class="font-medium text-base mr-auto">Import Data</h2> <a
                                    href="{{ route('download.file', ['file' => 'format-agent.xlsx']) }}"
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
