@extends('auth.layout')

@section('content')
    <div
        class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
            {{ __('Reset Password') }}
        </h2>
        <div class="intro-x mt-2 text-slate-400 text-center">{{ __('Please confirm your password before continuing.') }}
        </div>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="intro-x mt-8">
                <input id="email" type="email"
                    class="intro-x login__input form-control py-3 px-4 block mt-4 @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <input id="password" type="password"
                    class="intro-x login__input form-control py-3 px-4 block mt-4 @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password" placeholder="Password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <input id="password-confirm" type="password" class="intro-x login__input form-control py-3 px-4 block mt-4"
                    name="password_confirmation" required autocomplete="new-password" placeholder="Password Confirmation">
            </div>
            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                <button class="btn btn-primary py-3 px-4 w-full xl:mr-3 align-top"
                    type="submit">{{ __('Reset Password') }}</button>
            </div>
        </form>
    </div>
@endsection
