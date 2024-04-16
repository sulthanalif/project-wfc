@extends('cms.layouts.app', [
    'title' => 'Detail User',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail User
        </h2>
        @hasrole('super_admin')
            <a href="{{ route('user.edit', $user) }}" class="btn btn-primary"><i data-lucide="edit" class="w-4 h-4 mr-2"></i> Ubah
                User</a>
        @endhasrole
    </div>
    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    <img alt="Profile" class="rounded-full" src="{{ asset('assets/cms/images/profile.svg') }}">
                    <div
                        class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-primary rounded-full p-2">
                        <i class="w-4 h-4 text-white" data-lucide="camera"></i>
                    </div>
                </div>
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">
                        {{ $user->agentProfile ? $user->agentProfile->name : $user->roles->first()->name }}</div>
                    @if ($user->roles->first()->name == 'super_admin')
                        <div class="text-slate-500">Super Admin</div>
                    @elseif ($user->roles->first()->name == 'admin')
                        <div class="text-slate-500">Admin</div>
                    @elseif ($user->roles->first()->name == 'finance_admin')
                        <div class="text-slate-500">Admin Keuangan</div>
                    @else
                        <div class="text-slate-500">Agen</div>
                    @endif
                </div>
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-3">Detail Kontak</div>
                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                    <div class="truncate sm:whitespace-normal flex items-center"> <i data-lucide="mail"
                            class="w-4 h-4 mr-2"></i> {{ $user->email }} </div>
                    @if ($user->roles->first()->name == 'agent')
                        <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-lucide="phone"
                                class="w-4 h-4 mr-2"></i>
                            {{ $user->agentProfile->phone_number ? $user->agentProfile->phone_number : 'Nomer HP Belum Diisi' }}
                        </div>
                        <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-lucide="map-pin"
                                class="w-4 h-4 mr-2"></i>
                            {{ $user->agentProfile->address ? $user->agentProfile->address : 'Alamat Belum Diisi' }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
        @if ($user->roles->first()->name == 'agent')
            <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
                <li id="sub-agen-tab" class="nav-item" role="presentation"> <a href="javascript:;"
                        class="nav-link py-4 active" data-tw-target="#sub-agen" aria-controls="sub-agen"
                        aria-selected="true" role="tab"> Sub Agen
                    </a> </li>
                <li id="berkas-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4"
                        data-tw-target="#berkas" aria-controls="berkas" aria-selected="true" role="tab"> Berkas
                    </a> </li>
            </ul>
        @endif
    </div>
    <!-- END: Profile Info -->
    @if ($user->roles->first()->name == 'agent')
        <div class="intro-y tab-content mt-5">
            <div id="sub-agen" class="tab-pane active" role="tabpanel" aria-labelledby="sub-agen-tab">
                <div class="grid grid-cols-12 gap-6">
                    <!-- BEGIN: Sub Agen -->
                    <div class="intro-y box col-span-12">
                        <div
                            class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                Daftar Sub Agen
                            </h2>
                            <div class="dropdown ml-auto sm:hidden">
                                <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false"
                                    data-tw-toggle="dropdown"> <i data-lucide="more-horizontal"
                                        class="w-5 h-5 text-slate-500"></i> </a>
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a href="javascript:;" class="dropdown-item"> <i data-lucide="file"
                                                    class="w-4 h-4 mr-2"></i> Download Excel </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <button class="btn btn-outline-secondary hidden sm:flex"> <i data-lucide="file"
                                    class="w-4 h-4 mr-2"></i> Download Excel </button>
                        </div>
                        <div class="p-5 overflow-auto lg:overflow-visible">
                            <table class="table table-report -mt-2">
                                <thead>
                                    <tr>
                                        <th class="text-center whitespace-nowrap">#</th>
                                        <th class="whitespace-nowrap">NAMA SUB AGENT</th>
                                        <th class="text-center whitespace-nowrap">ALAMAT</th>
                                        <th class="text-center whitespace-nowrap">NO TELEPON</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($subAgents->isEmpty())
                                        <tr>
                                            <td colspan="4" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                                        </tr>
                                    @else
                                        @foreach ($subAgents as $data)
                                            <tr class="intro-x">
                                                <td>
                                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                                </td>
                                                <td>
                                                    <p class="font-medium">{{ $data->name }}</p>
                                                </td>
                                                <td>
                                                    <p class="font-medium">{!! $data->address !!}</p>
                                                </td>
                                                <td>
                                                    <p class="font-medium text-center">{{ $data->phone_number }}</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END: Sub Agen -->
                </div>
            </div>
            <div id="berkas" class="tab-pane" role="tabpanel" aria-labelledby="berkas-tab">
                <div class="grid grid-cols-12 gap-6">
                    <!-- BEGIN: Sub Agen -->
                    <div class="intro-y box col-span-12">
                        <div
                            class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                Berkas
                            </h2>
                        </div>
                        <div class="px-5 pt-3">
                            @if ($user->administration == null)
                            <div
                                class="grid grid-cols-12 border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
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
                            <div
                                class="grid grid-cols-12 border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                                <div
                                    class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start mb-3 lg:mb-0">
                                    <div class="flex flex-col items-center justify-center">
                                        <h1 class="font-bold text-xl mb-3">Kartu Tanda Penduduk</h1>
                                        <img alt="KTP" class=" img-fluid rounded-md"
                                            src="{{ asset('storage/images/administration/' . $user->id . '/' . $user->administration->ktp) }}">
                                    </div>
                                </div>
                                <div
                                    class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 border-b lg:border-b-0 pb-3 lg:pb-0 pt-3 lg:pt-0">
                                    <div class="flex flex-col items-center justify-center">
                                        <h1 class="font-bold text-xl mb-3">Kartu Keluarga</h1>
                                        <img alt="KK" class=" img-fluid rounded-md"
                                            src="{{ asset('storage/images/administration/' . $user->id . '/' . $user->administration->kk) }}">
                                    </div>
                                </div>
                                <div
                                    class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start mt-3 lg:mt-0">
                                    <div class="flex flex-col items-center justify-center">
                                        <h1 class="font-bold text-xl mb-3">Surat Perjanjian</h1>
                                        <img alt="SURAT PERJANJIAN" class=" img-fluid rounded-md"
                                            src="{{ asset('storage/images/administration/' . $user->id . '/' . $user->administration->sPerjanjian) }}">
                                    </div>
                                </div>
                            </div>
                        @endif
                        </div>
                    </div>
                    <!-- END: Sub Agen -->
                </div>
            </div>
        </div>
    @endif
@endsection
