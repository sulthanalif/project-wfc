@extends('cms.layouts.app', [
    'title' => 'Sub Agen',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Sub Agen
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @hasrole('super_admin')
                <a href="{{ route('sub-agent.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Sub Agen</a>
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $subAgents->firstItem() }} hingga
                    {{ $subAgents->lastItem() }} dari {{ $subAgents->total() }} data</div>
            @endhasrole
            <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search...">
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
                        <th class="text-center whitespace-nowrap">DARI AGEN</th>
                        <th class="whitespace-nowrap">ALAMAT</th>
                        <th class="text-center whitespace-nowrap">NOMER TELEPON</th>
                        @hasrole('super_admin')
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
                                <td class="!py-3.5">
                                    <div class="flex items-center">
                                        <div class="w-9 h-9 image-fit zoom-in">
                                            <img alt="PAKET SMART EFC" class="rounded-lg border-white shadow-md tooltip" src="{{ asset('assets/cms/images/profile.svg') }}" title="{{ $subAgent->phone_number }}">
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('sub-agent.show', $subAgent) }}" class="font-medium whitespace-nowrap">{{ $subAgent->name }}</a>
                                            {{-- <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $subAgent->agent->agentProfile->name }}</div> --}}
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center capitalize">
                                    {{ $subAgent->agent->agentProfile->name  }}
                                </td>
                                <td class="w-40">
                                    <p>
                                        {{ $subAgent->address }}
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
                                        <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal{{ $subAgent->id }}"> <i data-lucide="trash-2"
                                                class="w-4 h-4 mr-1"></i> Hapus </a>
                                    </div>
                                </td>
                                @endhasrole
                            </tr>



                        <!-- BEGIN: Delete Confirmation Modal -->
                        <div id="delete-confirmation-modal{{ $subAgent->id }}" class="modal" tabindex="-1" aria-hidden="true">
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
                                                <input type="hidden" name="page" value="{{ $subAgents->currentPage() }}">
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
            {{ $subAgents->links('cms.layouts.paginate') }}
        </div>
        {{-- <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <ul class="pagination">
                    <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                            <i class="w-4 h-4" data-lucide="chevrons-left"></i>
                        </a>
                    </li>
                    <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                            <i class="w-4 h-4" data-lucide="chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item {{ $paginator->currentPage() === $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    </li>
                    </ul>
            </nav>

            <select class="w-20 form-select box mt-3 sm:mt-0" wire:model="perPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="35">35</option>
                <option value="50">50</option>
            </select>
        </div> --}}
        <!-- END: Pagination -->
    </div>
@endsection