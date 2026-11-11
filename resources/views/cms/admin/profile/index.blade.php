@extends('cms.layouts.app', [
    'title' => 'Profil Admin',
])

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Profil Admin
        </h2>
    </div>

    <!-- BEGIN: Profile Header -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    <img alt="Profile" class="rounded-full" src="{{ asset('assets/cms/images/profile.svg') }}">
                </div>
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">
                        {{ $user->adminProfile ? $user->adminProfile->name : $user->name ?? 'Admin' }}
                    </div>
                    <div class="text-slate-500 capitalize">
                        {{ str_replace('_', ' ', $user->roles->first()?->name ?? 'Admin') }}
                    </div>
                </div>
            </div>
            <div class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-3">Detail Kontak</div>
                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                    <div class="truncate sm:whitespace-normal flex items-center">
                        <i data-lucide="mail" class="w-4 h-4 mr-2"></i> {{ $user->email }}
                    </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
                        <i data-lucide="shield" class="w-4 h-4 mr-2"></i> Role: {{ $user->roles->first()?->name ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Profile Header -->

    <!-- BEGIN: Forms Grid -->
    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- BEGIN: Change Profile Card -->
        <div class="intro-y box col-span-12 lg:col-span-6">
            <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    Ubah Profil
                </h2>
            </div>
            <div class="p-5">
                <form action="{{ route('admin.profile.email') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input id="name" name="name" type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', optional($user->adminProfile)->name) }}" required placeholder="Masukkan nama lengkap">
                        @error('name')
                            <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input id="email" name="email" type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required placeholder="Masukkan email">
                        @error('email')
                            <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-5 text-right">
                        <button type="submit" class="btn btn-primary w-36">Simpan Profil</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Change Profile Card -->

        <!-- BEGIN: Change Password Card -->
        <div class="intro-y box col-span-12 lg:col-span-6">
            <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    Ubah Password
                </h2>
            </div>
            <div class="p-5">
                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input id="current_password" name="current_password" type="password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            required placeholder="Masukkan password saat ini">
                        @error('current_password')
                            <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <label for="password" class="form-label">Password Baru</label>
                        <input id="password" name="password" type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required minlength="8" placeholder="Minimal 8 karakter">
                        @error('password')
                            <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="form-control"
                            required minlength="8" placeholder="Ulangi password baru">
                    </div>
                    <div class="mt-5 text-right">
                        <button type="submit" class="btn btn-primary w-36">Simpan Password</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Change Password Card -->
    </div>
    <!-- END: Forms Grid -->
@endsection
