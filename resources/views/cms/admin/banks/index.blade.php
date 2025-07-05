@extends('cms.layouts.app', [
    'title' => 'Periode',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Bank
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="javascript:;" class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal"
                data-tw-target="#create-confirmation-modal">Tambah Bank</a>
            @include('cms.admin.banks.modal.create')

            <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $datas->firstItem() }} hingga
                {{ $datas->lastItem() }} dari {{ $datas->total() }} data</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="filter">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">#</th>
                        <th class="text-center whitespace-nowrap">NAMA BANK</th>
                        <th class="text-center whitespace-nowrap">NOMOR REKENING</th>
                        <th class="text-center whitespace-nowrap">ATAS NAMA</th>
                        <th class="text-center whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($datas->isEmpty())
                        <tr>
                            <td colspan="7" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($datas as $data)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center flex items-center justify-center">
                                        {{ $loop->iteration }}</p>
                                </td>
                                <td>
                                    <p class="font-bold whitespace-nowrap flex items-center">{{ $data->name }}</p>
                                </td>
                                <td>
                                    <p class="text-slate-500 whitespace-nowrap flex items-center justify-center">
                                        {{ $data->account_number }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 whitespace-nowrap flex items-center justify-center">
                                        {{ $data->account_name }}
                                    </p>
                                </td>
                                <td class="flex items-center justify-center">
                                    <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal"
                                        data-tw-target="#edit-confirmation-modal{{ $data->id }}">
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Ubah </a>
                                        @include('cms.admin.banks.modal.edit')
                                    <a class="flex items-center mr-3 text-danger" href="javascript:;" data-tw-toggle="modal"
                                        data-tw-target="#delete-confirmation-modal{{ $data->id }}">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus </a>
                                        @include('cms.admin.banks.modal.delete')
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script src="{{ asset('assets/cms/js/ckeditor-classic.js') }}"></script>
@endpush
