@extends('cms.layouts.app', [
    'title' => 'Detail Sub Agen',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Sub Agen
        </h2>
        @hasrole('super_admin')
            <a href="{{ route('sub-agent.edit', $subAgent) }}" class="btn btn-primary"><i data-lucide="edit" class="w-4 h-4 mr-2"></i> Ubah
                Sub Agent</a>
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
                        {{ $subAgent->name }}</div>

                        <div class="text-slate-500">                        {{ $subAgent->agent->agentProfile->name }}
                        </div>

                </div>
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-3">Detail Kontak</div>
                <div class="flex flex-col justify-center items-center lg:items-start mt-4">


                        <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-lucide="phone"
                                class="w-4 h-4 mr-2"></i>
                            {{ $subAgent->phone_number }}
                        </div>
                        <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-lucide="map-pin"
                                class="w-4 h-4 mr-2"></i>
                            {{ $subAgent->address }}
                        </div>


                </div>
            </div>
        </div>
        <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
            <li id="sub-agen-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4 active"
                    data-tw-target="#sub-agen" aria-controls="sub-agen" aria-selected="true" role="tab"> Detail Agen
                </a> </li>
        </ul>
    </div>
    <!-- END: Profile Info -->
    <div class="intro-y tab-content mt-5">
        <div id="sub-agen" class="tab-pane active" role="tabpanel" aria-labelledby="sub-agen-tab">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Sub Agen -->
                <div class="intro-y box col-span-12 lg:col-span-6">
                    <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Detail Agent
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
                    <div class="p-5">
                        <div class="relative flex items-center">
                            <div class="flex flex-col justify-center items-center lg:items-start mt-4">


                                <div class="truncate sm:whitespace-normal flex items-center mt-3"> Nama :
                                    {{ $subAgent->agent->agentProfile->name }}
                                </div>
                                <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-lucide="map-pin"
                                        class="w-4 h-4 mr-2"></i>
                                    {{ $subAgent->agent->agentProfile->address ? $subAgent->agent->agentProfile->address : 'Agen Belum Mengisi Alamat' }}
                                </div>
                                <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-lucide="phone"
                                    class="w-4 h-4 mr-2"></i>
                                {{ $subAgent->agent->agentProfile->phone_number ? $subAgent->agent->agentProfile->phone_number : 'Agen Belum Mengisi Nomer Telepon' }}
                                </div>


                        </div>
                        </div>
                    </div>
                </div>
                <!-- END: Sub Agen -->
            </div>
        </div>
    </div>
@endsection
