@extends('cms.layouts.app', [
    'title' => 'Detail User',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail User
        </h2>
        <a href="{{ route('user.index') }}" class="btn px-2 box mr-2"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
        @hasrole('super_admin')
            <a href="{{ route('user.edit', $user) }}" class="btn btn-primary"><i data-lucide="edit" class="w-4 h-4 mr-2"></i> Ubah
                Detail</a>
        @endhasrole
    </div>
    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    <img alt="Profile" class="rounded-full"
                        src="{{ empty($user->agentProfile->photo) ? asset('assets/cms/images/profile.svg') : route('getImage', ['path' => 'photos', 'imageName' => $user->agentProfile->photo]) }}">
                    <div
                        class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-primary rounded-full p-2">
                        <i class="w-4 h-4 text-white" data-lucide="camera"></i>
                    </div>
                </div>
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">
                        @if ($user->roles->first()->name == 'agent')
                            {{ $user->agentProfile ? $user->agentProfile->name : 'Belum Mengisi Nama Lengkap' }}
                        @else
                            {{ $user->adminProfile ? $user->adminProfile->name : 'Belum Mengisi Nama Lengkap' }}
                        @endif
                    </div>
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
                        <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-lucide="user"
                                class="w-4 h-4 mr-2"></i>
                            <p>{{ $user->agentProfile->ktp_address ? $user->agentProfile->ktp_address : 'Alamat KTP Belum Diisi' }}</p>
                        </div>
                        <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-lucide="map-pin"
                                class="w-4 h-4 mr-2"></i>
                            <p>{{ $user->agentProfile->address ? $user->agentProfile->address . " RT " . $user->agentProfile->rt . " / RW " . $user->agentProfile->rw . ", " . $user->agentProfile->village . ", " . $user->agentProfile->district . ", " . $user->agentProfile->regency . ", " . $user->agentProfile->province : 'Alamat Belum Diisi' }}</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
            @if ($user->roles->first()->name == 'agent')
                <li id="sub-agen-tab" class="nav-item" role="presentation"> <a href="javascript:;"
                        class="nav-link py-4 active" data-tw-target="#sub-agen" aria-controls="sub-agen"
                        aria-selected="true" role="tab"> Sub Agen
                    </a> </li>
                <li id="berkas-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4"
                        data-tw-target="#berkas" aria-controls="berkas" aria-selected="true" role="tab"> Berkas
                    </a> </li>
            @endif
            @hasrole('super_admin')
                <li id="cpassword-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4"
                        data-tw-target="#cpassword" aria-controls="cpassword" aria-selected="true" role="tab"> Reset
                        Password
                    </a> </li>
                <li id="cemail-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4"
                        data-tw-target="#cemail" aria-controls="cemail" aria-selected="true" role="tab"> Ubah Email
                    </a> </li>
                <li id="cakses-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4"
                        data-tw-target="#cakses" aria-controls="cakses" aria-selected="true" role="tab"> Ubah Hak Akses
                    </a> </li>
            @endhasrole
        </ul>
    </div>
    <!-- END: Profile Info -->
    <div class="intro-y tab-content mt-5">
        @if ($user->roles->first()->name == 'agent')
            <div id="sub-agen" class="tab-pane active" role="tabpanel" aria-labelledby="sub-agen-tab">
                <div class="grid grid-cols-12 gap-6">
                    <!-- BEGIN: Sub Agen -->
                    <div class="intro-y box col-span-12">
                        <div
                            class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                Daftar Sub Agen
                            </h2>
                            {{-- <div class="dropdown ml-auto sm:hidden">
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
                                    class="w-4 h-4 mr-2"></i> Download Excel </button> --}}
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
                                            <td colspan="4" class="font-medium whitespace-nowrap text-center">Belum Ada
                                                Data</td>
                                        </tr>
                                    @else
                                        @foreach ($subAgents as $data)
                                            <tr class="intro-x">
                                                <td>
                                                    <p class="font-medium whitespace-nowrap text-center">
                                                        {{ $loop->iteration }}</p>
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
                            <a href="javascript:;" class="btn btn-outline-secondary hidden sm:flex" 
                            data-tw-toggle="modal"
                            data-tw-target="#update-files-modal"> <i data-lucide="edit"
                                class="w-4 h-4 mr-2"></i> Ubah </a>

                            <!-- BEGIN: Update Files Modal -->
                            <div id="update-files-modal" class="modal" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body p-0">
                                            <form action="{{ route('updateAdministration', $user->id)}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="p-2">
                                                    <div class="modal-header">
                                                        <h2 class="font-medium text-base mr-auto">Ubah Berkas</h2>
                                                    </div>
                                                    <div class="modal-body text-slate-500 mt-2">
                                                        <div>
                                                            <label for="ktp" class="form-label">Upload Foto Kartu Tanda Penduduk <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                                                <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                                                <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                                                <input id="ktp" name="ktp" type="file"
                                                                    class="w-full h-full top-0 left-0 absolute opacity-0" onchange="previewFile(this)">
                                                            </div>
                                                            <div id="ktp-preview" class="hidden mt-2"></div>
                                                            @error('ktp')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                            
                                                        <div class="mt-3">
                                                            <label for="sPerjanjian" class="form-label">Upload Foto Surat Perjanjian <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="px-4 pb-4 mt-5 flex items-center justify-center cursor-pointer relative">
                                                                <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                                                <span class="text-primary mr-1">Upload a file</span> or drag and drop
                                                                <input id="sPerjanjian" name="sPerjanjian" type="file"
                                                                    class="w-full h-full top-0 left-0 absolute opacity-0" onchange="previewFile(this)">
                                                            </div>
                                                            <div id="sPerjanjian-preview" class="hidden mt-2"></div>
                                                            @error('sPerjanjian')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="px-5 pb-8 text-center">
                                                    <button type="submit" class="btn btn-primary w-24">Simpan</button>
                                                    <button type="button" data-tw-dismiss="modal"
                                                        class="btn btn-outline-secondary w-24 ml-1">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Update Files Modal -->
                        </div>
                        <div class="px-5 pt-3">
                            @if ($user->administration == null)
                                <div
                                    class="grid grid-cols-12 border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                                    <div
                                        class="col-span-12 sm:col-span-6 xl:col-span-6 px-5 items-center justify-center lg:justify-start mb-3 lg:mb-0">
                                        <div class="flex flex-col items-center justify-center">
                                            <h1 class="font-bold text-xl mb-3">Kartu Tanda Penduduk</h1>
                                            <span class="text-muted">Belum Ada Berkas</span>
                                        </div>
                                    </div>
                                    {{-- <div
                                        class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 border-b lg:border-b-0 pb-3 lg:pb-0 pt-3 lg:pt-0">
                                        <div class="flex flex-col items-center justify-center">
                                            <h1 class="font-bold text-xl mb-3">Kartu Keluarga</h1>
                                            <span class="text-muted">Belum Ada Berkas</span>
                                        </div>
                                    </div> --}}
                                    <div
                                        class="col-span-12 sm:col-span-6 xl:col-span-6 px-5 items-center justify-center lg:justify-start mt-3 lg:mt-0">
                                        <div class="flex flex-col items-center justify-center">
                                            <h1 class="font-bold text-xl mb-3">Surat Perjanjian</h1>
                                            <span class="text-muted">Belum Ada Berkas</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if ($user->administration->ktp == 'image.jpg')
                                    <div
                                        class="grid grid-cols-12 border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                                        <div
                                            class="col-span-12 sm:col-span-6 xl:col-span-6 px-5 items-center justify-center lg:justify-start mb-3 lg:mb-0">
                                            <div class="flex flex-col items-center justify-center">
                                                <h1 class="font-bold text-xl mb-3">Kartu Tanda Penduduk</h1>
                                                <img alt="KTP" class=" img-fluid rounded-md"
                                                    src="{{ asset('assets/logo2.png') }}">
                                            </div>
                                        </div>
                                        {{-- <div
                                            class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 border-b lg:border-b-0 pb-3 lg:pb-0 pt-3 lg:pt-0">
                                            <div class="flex flex-col items-center justify-center">
                                                <h1 class="font-bold text-xl mb-3">Kartu Keluarga</h1>
                                                <img alt="KK" class=" img-fluid rounded-md"
                                                    src="{{ asset('assets/logo2.png') }}">
                                            </div>
                                        </div> --}}
                                        <div
                                            class="col-span-12 sm:col-span-6 xl:col-span-6 px-5 items-center justify-center lg:justify-start mt-3 lg:mt-0">
                                            <div class="flex flex-col items-center justify-center">
                                                <h1 class="font-bold text-xl mb-3">Surat Perjanjian</h1>
                                                <img alt="SURAT PERJANJIAN" class=" img-fluid rounded-md"
                                                    src="{{ asset('assets/logo2.png') }}">
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="grid grid-cols-12 border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                                        <div
                                            class="col-span-12 sm:col-span-6 xl:col-span-6 px-5 items-center justify-center lg:justify-start mb-3 lg:mb-0">
                                            <div class="flex flex-col items-center justify-center">
                                                <h1 class="font-bold text-xl mb-3">Kartu Tanda Penduduk</h1>
                                                <img alt="KTP" class="img-fluid rounded-md"
                                                    src="{{ route('getImage', ['path' => 'administration', 'imageName' => $user->administration->ktp]) }}">
                                            </div>
                                        </div>
                                        {{-- <div
                                            class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 border-b lg:border-b-0 pb-3 lg:pb-0 pt-3 lg:pt-0">
                                            <div class="flex flex-col items-center justify-center">
                                                <h1 class="font-bold text-xl mb-3">Kartu Keluarga</h1>
                                                <img alt="KK" class=" img-fluid rounded-md"
                                                    src="{{ route('getImage', ['path' => 'administration', 'imageName' => $user->administration->kk]) }}">
                                            </div>
                                        </div> --}}
                                        <div
                                            class="col-span-12 sm:col-span-6 xl:col-span-6 px-5 items-center justify-center lg:justify-start mt-3 lg:mt-0">
                                            <div class="flex flex-col items-center justify-center">
                                                <h1 class="font-bold text-xl mb-3">Surat Perjanjian</h1>
                                                <img alt="SURAT PERJANJIAN" class="img-fluid rounded-md"
                                                    src="{{ route('getImage', ['path' => 'administration', 'imageName' => $user->administration->sPerjanjian]) }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <!-- END: Sub Agen -->
                </div>
            </div>
        @endif
        @hasrole('super_admin')
            <div id="cpassword" class="tab-pane" role="tabpanel" aria-labelledby="cpassword-tab">
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
            <div id="cemail" class="tab-pane" role="tabpanel" aria-labelledby="cemail-tab">
                <div class="grid grid-cols-12 gap-6">
                    <!-- BEGIN: Change Email -->
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
                                        type="submit">{{ __('Simpan Perubahan') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- END: Change Email -->
                </div>
            </div>
            <div id="cakses" class="tab-pane" role="tabpanel" aria-labelledby="cakses-tab">
                <div class="grid grid-cols-12 gap-6">
                    <!-- BEGIN: Change Role -->
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
                            <form method="POST" action="{{ route('user.role', $user->id) }}" class="w-full">
                                @csrf
                                <div class="intro-x mt-8">
                                    <select class="form-select mt-2 sm:mr-2" id="role" name="role" required>
                                        <option value="" disabled>Pilih Hak Akses...</option>
                                        <option value="super_admin"
                                            {{ isset($user->roles->first()->name) && $user->roles->first()->name === 'super_admin' ? 'selected' : '' }}>
                                            Super Admin</option>
                                        <option value="admin"
                                            {{ isset($user->roles->first()->name) && $user->roles->first()->name === 'admin' ? 'selected' : '' }}>
                                            Admin</option>
                                        <option value="finance_admin"
                                            {{ isset($user->roles->first()->name) && $user->roles->first()->name === 'finance_admin' ? 'selected' : '' }}>
                                            Keuangan</option>
                                        <option value="agent"
                                            {{ isset($user->roles->first()->name) && $user->roles->first()->name === 'agent' ? 'selected' : '' }}>
                                            Agent</option>
                                    </select>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                                    <button class="btn btn-primary py-3 px-4 w-full xl:mr-3 align-top"
                                        type="submit">{{ __('Simpan Perubahan') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- END: Change Role -->
                </div>
            </div>
        @endhasrole
    </div>
@endsection


@push('custom-scripts')
    <script>
        function previewFile(input) {
            var imagePreviewId = "#" + input.id + "-preview"; // Get corresponding preview div ID
            var imagePreviewElement = document.querySelector(imagePreviewId);

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    imagePreviewElement.classList.remove("hidden"); // Show preview div
                    imagePreviewElement.innerHTML = "<img src='" + e.target.result + "' />";
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                imagePreviewElement.classList.add("hidden"); // Hide preview div if no file selected
                imagePreviewElement.innerHTML = "";
            }
        }
    </script>
@endpush