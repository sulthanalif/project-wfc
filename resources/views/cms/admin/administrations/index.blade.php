@extends('cms.layouts.app', [
    'title' => 'Administrasi',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Berkas Administrasi {{ $user->agentProfile->name }}
        </h2>
        <a href="{{ route('user.index') }}" class="btn px-2 box mr-2"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
        @if ($user->roles->first()->name === 'agent')
            @if ($user->administration !== null)
                <a class="btn btn-primary" href="javascript:;" data-tw-toggle="modal"
                    data-tw-target="#upstat-confirmation-modal{{ $user->id }}"> <i data-lucide="edit"
                        class="w-4 h-4 mr-1"></i> {{ $user->active == 0 ? 'Approve' : 'Non aktif' }} </a>
            @endif
        @endif
    </div>

    <div class="intro-y box px-5 pt-5 mt-5">
        @if ($user->administration == null)
            <div class="grid grid-cols-12 border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                <div
                    class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start mb-3 lg:mb-0">
                    <div class="flex flex-col items-center justify-center">
                        <h1 class="font-bold text-xl mb-3">Kartu Tanda Penduduk</h1>
                        <span class="text-muted">Belum Ada Berkas</span>
                    </div>
                </div>
                <div
                    class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 border-b lg:border-b-0 pb-3 lg:pb-0 pt-3 lg:pt-0">
                    <div class="flex flex-col items-center justify-center">
                        <h1 class="font-bold text-xl mb-3">Kartu Keluarga</h1>
                        <span class="text-muted">Belum Ada Berkas</span>
                    </div>
                </div>
                <div
                    class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start mt-3 lg:mt-0">
                    <div class="flex flex-col items-center justify-center">
                        <h1 class="font-bold text-xl mb-3">Surat Perjanjian</h1>
                        <span class="text-muted">Belum Ada Berkas</span>
                    </div>
                </div>
            </div>
        @else
            @if ($user->administration->ktp == 'default.png')
                <div class="grid grid-cols-12 border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                    <div
                        class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start mb-3 lg:mb-0">
                        <div class="flex flex-col items-center justify-center">
                            <h1 class="font-bold text-xl mb-3">Kartu Tanda Penduduk</h1>
                            <img alt="KTP" class=" img-fluid rounded-md" src="{{ asset('assets/logo2.png') }}">
                        </div>
                    </div>
                    <div
                        class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 border-b lg:border-b-0 pb-3 lg:pb-0 pt-3 lg:pt-0">
                        <div class="flex flex-col items-center justify-center">
                            <h1 class="font-bold text-xl mb-3">Kartu Keluarga</h1>
                            <img alt="KK" class=" img-fluid rounded-md" src="{{ asset('assets/logo2.png') }}">
                        </div>
                    </div>
                    <div
                        class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start mt-3 lg:mt-0">
                        <div class="flex flex-col items-center justify-center">
                            <h1 class="font-bold text-xl mb-3">Surat Perjanjian</h1>
                            <img alt="SURAT PERJANJIAN" class=" img-fluid rounded-md" src="{{ asset('assets/logo2.png') }}">
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-12 border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                    <div
                        class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start mb-3 lg:mb-0">
                        <div class="flex flex-col items-center justify-center">
                            <h1 class="font-bold text-xl mb-3">Kartu Tanda Penduduk</h1>
                            <img alt="KTP" class=" img-fluid rounded-md"
                                src="{{ route('getImage', ['path' => 'administration' , 'imageName' => $user->administration->ktp]) }}">
                        </div>
                    </div>
                    <div
                        class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 border-b lg:border-b-0 pb-3 lg:pb-0 pt-3 lg:pt-0">
                        <div class="flex flex-col items-center justify-center">
                            <h1 class="font-bold text-xl mb-3">Kartu Keluarga</h1>
                            <img alt="KK" class=" img-fluid rounded-md"
                                src="{{ route('getImage', ['path' => 'administration' , 'imageName' => $user->administration->kk]) }}">
                        </div>
                    </div>
                    <div
                        class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start mt-3 lg:mt-0">
                        <div class="flex flex-col items-center justify-center">
                            <h1 class="font-bold text-xl mb-3">Surat Perjanjian</h1>
                            <img alt="SURAT PERJANJIAN" class=" img-fluid rounded-md"
                                src="{{ route('getImage', ['path' => 'administration' , 'imageName' => $user->administration->sPerjanjian]) }}">
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- BEGIN: Update Status Confirmation Modal -->
    <div id="upstat-confirmation-modal{{ $user->id }}" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="x-circle" class="w-16 h-16 text-warning mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">Apakah anda yakin?</div>
                        <div class="text-slate-500 mt-2">
                            Apakah anda yakin untuk menyetujui berkas data ini?
                            <br>
                            Proses tidak akan bisa diulangi.
                        </div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <form action="{{ route('user.status', $user) }}" method="post">
                            @csrf
                            @method('put')
                            {{-- <input type="hidden" name="active" value {{ ($user->active == 1) ? 0 : 1 }}> --}}
                            <button type="submit" class="btn btn-warning w-24">Setujui</button>
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Update Status Confirmation Modal -->
@endsection
