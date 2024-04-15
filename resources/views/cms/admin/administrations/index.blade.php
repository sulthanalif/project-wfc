@extends('cms.layouts.app', [
    'title' => 'Administrasi',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Administrasi {{ $user->agentProfile->name }}
        </h2>
    </div>

    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <img alt="KTP" class=" img-fluid rounded-md"
                    src="{{ asset('storage/images/administration/'. $user->id . '/' . $user->administration->ktp) }}">
            </div>
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <img alt="KK" class=" img-fluid rounded-md"
                    src="{{ asset('storage/images/administration/'. $user->id . '/' . $user->administration->kk) }}">
            </div>
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <img alt="SURAT PERJANJIAN" class=" img-fluid rounded-md"
                    src="{{ asset('storage/images/administration/'. $user->id . '/' . $user->administration->sPerjanjian) }}">
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="text-slate-600 dark:text-slate-500">
                    <div class="flex flex-col items-center justify-center border-b pb-2">
                        <h1 class="font-bold text-xl">{{ $user->agentProfile->name }}</h1>
                        {{-- <span class="text-muted">Katalog: {!! $package->catalogName->name !!}</span> --}}
                    </div>
                    <div class="flex items-center justify-between mt-2 gap-3 pt-2">
                        <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal"
                                                data-tw-target="#upstat-confirmation-modal{{ $user->id }}"> <i
                                                    data-lucide="edit" class="w-4 h-4 mr-1"></i> {{ ($user->active == 0) ? 'Approve' : 'Non aktif' }} </a>


                    </div>

                </div>
            </div>
        </div>
    </div>

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
@endsection
