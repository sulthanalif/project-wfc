@extends('cms.layouts.app', ['title' => 'Detail Profil'])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Detail Profil
        </h2>
        <a href="{{ route('users.profile.edit', ['id' => auth()->user()->id]) }}" class="btn btn-primary"><i data-lucide="edit"
                class="w-4 h-4 mr-2"></i> Ubah Profil</a>
    </div>
    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    @if ($agent->agentProfile->photo == null)
                        <img alt="Profile" class="rounded-full" src="{{ asset('assets/cms/images/profile.svg') }}">
                        <div
                            class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-primary rounded-full p-2">
                            <i class="w-4 h-4 text-white" data-lucide="camera"></i>
                        </div>
                    @else
                        <img alt="Profile" class="rounded-full"
                            src="{{ route('getImage', ['path' => 'photos', 'imageName' => $agent->agentProfile->photo]) }}">
                        <div
                            class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-primary rounded-full p-2">
                            <i class="w-4 h-4 text-white" data-lucide="camera"></i>
                        </div>
                    @endif
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
                    <div class="flex items-center"> <i data-lucide="mail" class="w-4 h-4 mr-2"></i> {{ $agent->email }}
                    </div>
                    <div class="flex items-center mt-3"> <i data-lucide="phone" class="w-4 h-4 mr-2"></i>
                        {{ $agent->agentProfile->phone_number ? $agent->agentProfile->phone_number : 'Nomer HP Belum Diisi' }}
                    </div>
                    <div class="flex items-center mt-3">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i>
                        @if ($agent->agentProfile->address !== null)
                            <span id="formatted-address"></span>
                        @else
                            <span>Alamat Belum Diisi</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
            <li id="berkas-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4 active"
                    data-tw-target="#berkas" aria-selected="false" role="tab">
                    Berkas </a> </li>
            <li id="cp-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4"
                    data-tw-target="#cp" aria-selected="false" role="tab">
                    Reset Password </a> </li>
        </ul>
    </div>
    <!-- END: Profile Info -->
    <div class="intro-y tab-content mt-5">
        <div id="berkas" class="tab-pane active" role="tabpanel" aria-labelledby="berkas-tab">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Sub Agen -->
                <div class="intro-y box col-span-12">
                    <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Berkas
                        </h2>
                    </div>
                    <div class="px-5 pt-3">
                        @if ($agent->administration == null)
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
                            @if ($agent->administration->ktp == 'default.png')
                                <div
                                    class="grid grid-cols-12 border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                                    <div
                                        class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start mb-3 lg:mb-0">
                                        <div class="flex flex-col items-center justify-center">
                                            <h1 class="font-bold text-xl mb-3">Kartu Tanda Penduduk</h1>
                                            <img alt="KTP" class=" img-fluid rounded-md"
                                                src="{{ asset('assets/logo2.PNG') }}">
                                        </div>
                                    </div>
                                    <div
                                        class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 border-b lg:border-b-0 pb-3 lg:pb-0 pt-3 lg:pt-0">
                                        <div class="flex flex-col items-center justify-center">
                                            <h1 class="font-bold text-xl mb-3">Kartu Keluarga</h1>
                                            <img alt="KK" class=" img-fluid rounded-md"
                                                src="{{ asset('assets/logo2.PNG') }}">
                                        </div>
                                    </div>
                                    <div
                                        class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start mt-3 lg:mt-0">
                                        <div class="flex flex-col items-center justify-center">
                                            <h1 class="font-bold text-xl mb-3">Surat Perjanjian</h1>
                                            <img alt="SURAT PERJANJIAN" class=" img-fluid rounded-md"
                                                src="{{ asset('assets/logo2.PNG') }}">
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
                                                src="{{ asset('storage/images/administration/' . $agent->id . '/' . $agent->administration->ktp) }}">
                                        </div>
                                    </div>
                                    <div
                                        class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 border-b lg:border-b-0 pb-3 lg:pb-0 pt-3 lg:pt-0">
                                        <div class="flex flex-col items-center justify-center">
                                            <h1 class="font-bold text-xl mb-3">Kartu Keluarga</h1>
                                            <img alt="KK" class=" img-fluid rounded-md"
                                                src="{{ asset('storage/images/administration/' . $agent->id . '/' . $agent->administration->kk) }}">
                                        </div>
                                    </div>
                                    <div
                                        class="col-span-12 sm:col-span-6 xl:col-span-4 px-5 items-center justify-center lg:justify-start mt-3 lg:mt-0">
                                        <div class="flex flex-col items-center justify-center">
                                            <h1 class="font-bold text-xl mb-3">Surat Perjanjian</h1>
                                            <img alt="SURAT PERJANJIAN" class=" img-fluid rounded-md"
                                                src="{{ asset('storage/images/administration/' . $agent->id . '/' . $agent->administration->sPerjanjian) }}">
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

@push('custom-scripts')
    <script>
        const dataAddress = "{{ $agent->agentProfile->address }}";
        const dataRt = "{{ $agent->agentProfile->rt }}";
        const dataRw = "{{ $agent->agentProfile->rw }}";
        const dataVillage = "{{ $agent->agentProfile->village }}";
        const dataDistrict = "{{ $agent->agentProfile->district }}";
        const dataRegency = "{{ $agent->agentProfile->regency }}";
        const dataProvince = "{{ $agent->agentProfile->province }}";

        let provinceName = '';
        let regencyName = '';
        let districtName = '';
        let villageName = '';

        const formatAddress = () => {
            const addressDisplay = document.getElementById('formatted-address');
            const formattedAddress =
                `${dataAddress}, RT ${dataRt}/RW ${dataRw}, ${villageName}, ${districtName}, ${regencyName}, ${provinceName}`;
            addressDisplay.textContent = formattedAddress;
        };

        const fetchProvinces = () => {
            return fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                .then(response => response.json())
                .then(data => {
                    const province = data.find(province => province.id === dataProvince);
                    if (province) {
                        provinceName = province.name;
                    }
                });
        };

        const fetchRegencies = () => {
            return fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${dataProvince}.json`)
                .then(response => response.json())
                .then(data => {
                    const regency = data.find(regency => regency.id === dataRegency);
                    if (regency) {
                        regencyName = regency.name;
                    }
                });
        };

        const fetchDistricts = () => {
            return fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${dataRegency}.json`)
                .then(response => response.json())
                .then(data => {
                    const district = data.find(district => district.id === dataDistrict);
                    if (district) {
                        districtName = district.name;
                    }
                });
        };

        const fetchVillages = () => {
            return fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${dataDistrict}.json`)
                .then(response => response.json())
                .then(data => {
                    const village = data.find(village => village.id === dataVillage);
                    if (village) {
                        villageName = village.name;
                    }
                });
        };

        Promise.all([fetchProvinces(), fetchRegencies(), fetchDistricts(), fetchVillages()])
            .then(() => {
                formatAddress();
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>
@endpush
