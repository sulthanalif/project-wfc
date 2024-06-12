@extends('auth.layout')

@section('content')
    <div
        class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center">
            {{ __('Verifikasi Alamat Email') }}
        </h2>
        @if (session('resent'))
            <div id="success-notification-content" class="toastify-content flex my-3">
                <i class="text-success" data-lucide="check-circle"></i>
                <div class="ml-4 mr-4">
                    <div class="font-medium">{{ __('Pesan baru verifikasi email telah dikirimkan.') }}</div>
                </div>
            </div>
        @endif
        <div class="intro-x mt-2 text-slate-400 text-center">
            {{ __('Sebelum masuk, silahkan cek email yang didaftarkan terlebih dahulu lalu lalu klik tombol verifikasi.') }}
            {{ __('Jika pesan email tidak tersedia, silahkan klik tombol dibawah untuk mendapatkan pesan email baru') }},
            <form class="d-inline mt-2" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-primary align-baseline">{{ __('Klik disini') }}</button>.
            </form>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none mt-5">
                @csrf
                <button type="submit" class="btn btn-danger align-baseline">{{ __('Logout') }}</button>
            </form>
        </div>
    </div>
@endsection
