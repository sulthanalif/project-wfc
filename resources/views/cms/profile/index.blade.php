@extends('cms.layouts.app', ['title' => 'Detail Profil'])

@section('content')
    @if (session('success'))
        <div id="success-notification-content" class="toastify-content flex" role="alert">
            <i class="text-success" data-lucide="check-circle"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Profil berhasil diubah!</div>
                {{-- <div class="text-slate-500 mt-1"> Please check your e-mail for further info! </div> --}}
            </div>
        </div>
    @endif
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Profil
        </h2>
        <a href="{{ route('users.profile.edit', ['id' => auth()->user()->id]) }}" class="btn btn-primary"><i
                data-lucide="edit" class="w-4 h-4 mr-2"></i> Ubah Profil</a>
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
                        {{ $agent->agentProfile->name }}</div>
                        @if (auth()->user()->hasRole('agent'))
                            <div class="text-slate-500">Agen</div>
                        @endif
                </div>
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-3">Detail Kontak</div>
                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                    <div class="truncate sm:whitespace-normal flex items-center"> <i data-lucide="mail"
                            class="w-4 h-4 mr-2"></i> {{ $agent->email }} </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-lucide="phone"
                            class="w-4 h-4 mr-2"></i> {{ $agent->agentProfile->phone_number ? $agent->agentProfile->phone_number : 'Nomer HP Belum Diisi' }} </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-lucide="map-pin"
                            class="w-4 h-4 mr-2"></i>
                        {{ $agent->agentProfile->address == null ? 'Harap isi alamat!' : $agent->agentProfile->address }}
                    </div>
                </div>
            </div>
        </div>
        <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
            <li id="sub-agen-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4 active"
                    data-tw-target="#sub-agen" aria-controls="sub-agen" aria-selected="true" role="tab"> Sub Agen
                </a> </li>
            <li id="cp-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4"
                    data-tw-target="#cp" aria-selected="false" role="tab">
                    Reset Password </a> </li>
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
                    <div class="p-5">
                        <div class="relative flex items-center">
                            <div class="w-12 h-12 flex-none image-fit">
                                <img alt="Profil" class="rounded-full" src="dist/images/profile-14.jpg">
                            </div>
                            <div class="ml-4 mr-auto">
                                <a href="" class="font-medium">Salwa</a>
                                <div class="text-slate-500 mr-5 sm:mr-5">Bootstrap 4 HTML Admin Template</div>
                            </div>
                            <div class="font-medium text-slate-600 dark:text-slate-500">+$19</div>
                        </div>
                        <div class="relative flex items-center mt-5">
                            <div class="w-12 h-12 flex-none image-fit">
                                <img alt="Profil" class="rounded-full" src="dist/images/profile-2.jpg">
                            </div>
                            <div class="ml-4 mr-auto">
                                <a href="" class="font-medium">Salwa</a>
                                <div class="text-slate-500 mr-5 sm:mr-5">Tailwind HTML Admin Template</div>
                            </div>
                            <div class="font-medium text-slate-600 dark:text-slate-500">+$25</div>
                        </div>
                        <div class="relative flex items-center mt-5">
                            <div class="w-12 h-12 flex-none image-fit">
                                <img alt="Profil" class="rounded-full" src="dist/images/profile-6.jpg">
                            </div>
                            <div class="ml-4 mr-auto">
                                <a href="" class="font-medium">Salwa</a>
                                <div class="text-slate-500 mr-5 sm:mr-5">Vuejs HTML Admin Template</div>
                            </div>
                            <div class="font-medium text-slate-600 dark:text-slate-500">+$21</div>
                        </div>
                    </div>
                </div>
                <!-- END: Sub Agen -->
            </div>
        </div>
        <div id="cp" class="tab-pane" role="tabpanel" aria-labelledby="cp-tab">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Change Password -->
                <div class="intro-y box col-span-12 lg:col-span-6">
                    <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        @if (session('status'))
                            <div id="success-notification-content" class="toastify-content flex my-3 w-full">
                                <i class="text-success" data-lucide="check-circle"></i>
                                <div class="ml-4 mr-4">
                                    <div class="font-medium">{{ session('status') }}</div>
                                </div>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}" class="w-full">
                            @csrf
                            <div class="intro-x mt-8">
                                <input id="email" type="email"
                                    class="intro-x login__input form-control py-3 px-4 block @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="Email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                                <button class="btn btn-primary py-3 px-4 w-full xl:mr-3 align-top"
                                    type="submit">{{ __('Kirim Link Reset Password') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END: Change Password -->
            </div>
        </div>
    </div>
@endsection