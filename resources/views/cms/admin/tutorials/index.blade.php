
@extends('cms.layouts.app', [
    'title' => 'Tutorial',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Tutorial
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="javascript:;" class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal"
                data-tw-target="#create-confirmation-modal">Tambah Tutorial</a>
            @include('cms.admin.tutorials.modal.create')

             @include('cms.layouts.per-page')

            @if ($datas instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $datas->firstItem() }} hingga
                    {{ $datas->lastItem() }} dari {{ $datas->total() }} data</div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">Menampilkan semua {{ $datas->count() }} data</div>
            @endif
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Cari di halaman ini..." title="Hanya mencari data pada halaman yang sedang aktif" id="filter" value="{{ request('search') }}">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>
        <x-table.filter :filters="$filters" />        <x-table.wrapper>
            <x-slot:thead>
                <tr>
                    <th class="text-center whitespace-nowrap">#</th>
                    <th class="text-center whitespace-nowrap">JUDUL</th>
                    <th class="text-center whitespace-nowrap">DESKRIPSI</th>
                    <th class="text-center whitespace-nowrap">ROLE</th>
                    <th class="text-center whitespace-nowrap">LINK</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">AKSI</th>
                </tr>
            </x-slot:thead>
            @if ($datas->isEmpty())
                <x-table.empty colspan="7" />
            @else
                @foreach ($datas as $data)
                    <tr class="intro-x">
                        <td>
                            <p class="font-medium whitespace-nowrap text-center flex items-center justify-center">
                                {{ $datas instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($datas->firstItem() + $loop->index) : $loop->iteration }}</p>
                        </td>
                        <td>
                            <p class="font-bold whitespace-nowrap flex items-center">{{ $data->title }}</p>
                        </td>
                        <td>
                            <p class="text-slate-500 whitespace-nowrap flex items-center justify-center">
                                {{ Str::limit($data->description, 20, '...') }}
                            </p>
                        </td>
                        <td>
                            <p class="text-slate-500 whitespace-nowrap flex items-center justify-center">
                                {{ $data->role }}
                            </p>
                        </td>
                         <td>
                            <p class="text-slate-500 whitespace-nowrap flex items-center justify-center">
                                {{ $data->link }}
                            </p>
                        </td>
                        <td>
                            <p class="text-slate-500 whitespace-nowrap text-center">
                                {{ $data->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                            </p>
                        </td>
                       
                        <td class="flex items-center justify-center">
                            <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal"
                                data-tw-target="#edit-confirmation-modal{{ $data->id }}">
                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                @include('cms.admin.tutorials.modal.edit')
                            <a class="flex items-center mr-3 text-danger" href="javascript:;" data-tw-toggle="modal"
                                data-tw-target="#delete-confirmation-modal{{ $data->id }}">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                @include('cms.admin.tutorials.modal.delete')
                        </td>
                    </tr>
                @endforeach
            @endif
        </x-table.wrapper>
        <x-table.pagination :collection="$datas" />
    </div>
@endsection

@push('custom-scripts')

    @endpush
