@extends('auth.layout')

@section('content')
    <div
        class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
            Sign In
        </h2>
        <div class="intro-x mt-2 text-slate-400 xl:hidden text-center">Silahkan masukkan akun Anda yang
            sudah terdaftar.</div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="intro-x mt-8">
                <input id="email" type="email"
                    class="intro-x login__input form-control py-3 px-4 block @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <input id="password" type="password"
                    class="intro-x login__input form-control py-3 px-4 block mt-4 @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password" placeholder="Password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                <div class="flex items-center mr-auto">
                    <input class="form-check-input border mr-2" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="cursor-pointer select-none" for="remember">Ingat Saya</label>
                </div>
                <a href="{{ route('password.request') }}">Lupa Password?</a>
            </div>
            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" type="submit">Login</button>
                <a href="{{ route('register') }}"
                    class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 mt-3 xl:mt-0 align-top">Daftar</a>
            </div>
        </form>
    </div>
@endsection
