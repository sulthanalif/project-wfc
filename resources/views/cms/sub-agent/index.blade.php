@extends('cms.layouts.app', [
    'title' => 'Sub Agen',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Sub Agen
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @hasrole('super_admin|agent')
                <a href="{{ route('sub-agent.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Sub Agen</a>
                @hasrole('super_admin')
                    <div class="dropdown">
                        <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                            <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="plus"></i>
                            </span>
                        </button>
                        <div class="dropdown-menu w-40">
                            <ul class="dropdown-content">
                                <li>
                                    <a href="{{ route('export.subAgent') }}" class="dropdown-item"> <i data-lucide="log-out"
                                            class="w-4 h-4 mr-2"></i> Export </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endhasrole
            @endhasrole
            <div class="w-auto relative text-slate-500 ml-2">
                <select id="records_per_page" class="form-control box">
                    <option value="10" {{ request()->get('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->get('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request()->get('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="all" {{ request()->get('perPage') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>
            @if ($subAgents instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $subAgents->firstItem() }} hingga
                    {{ $subAgents->lastItem() }} dari {{ $subAgents->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ $subAgents->count() }} data
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
                        <th class="whitespace-nowrap">NAMA SUB AGEN</th>
                        @hasrole('super_admin|admin')
                            <th class="text-center whitespace-nowrap">DARI AGEN</th>
                        @endhasrole
                        <th class="whitespace-nowrap">ALAMAT</th>
                        <th class="text-center whitespace-nowrap">NOMER TELEPON</th>
                        @hasrole('super_admin|agent')
                            <th class="text-center whitespace-nowrap">AKSI</th>
                        @endhasrole
                    </tr>
                </thead>
                <tbody>
                    @if ($subAgents->isEmpty())
                        <tr>
                            <td colspan="6" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($subAgents as $subAgent)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <p class="font-medium whitespace-nowrap">{{ $subAgent->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                @hasrole('super_admin|admin')
                                    <td class="text-center capitalize">
                                        {{ $subAgent->agent->agentProfile->name }}
                                    </td>
                                @endhasrole
                                <td class="!py-3.5">
                                    <p>
                                        {!! $subAgent->address !!}
                                    </p>
                                </td>
                                <td class="w-40">
                                    <p>
                                        {{ $subAgent->phone_number }}
                                    </p>
                                </td>
                                @hasrole('super_admin|agent')
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center mr-3" href="{{ route('sub-agent.edit', $subAgent) }}">
                                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                            <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal{{ $subAgent->id }}"> <i
                                                    data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                        </div>
                                    </td>
                                @endhasrole
                            </tr>



                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="delete-confirmation-modal{{ $subAgent->id }}" class="modal" tabindex="-1"
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
                                                <form action="{{ route('sub-agent.destroy', $subAgent) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    @if ($subAgents instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                                        <input type="hidden" name="page"
                                                            value="{{ $subAgents->currentPage() }}">
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
        @if ($subAgents instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                {{ $subAgents->links('cms.layouts.paginate') }}
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
        });
    </script>
@endpush
