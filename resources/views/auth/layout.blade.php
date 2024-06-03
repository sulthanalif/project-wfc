<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content=" " />
    <meta name="keywords" content="" />

    <title>Smart WFC</title>

    <link href="{{ asset('assets/logo2.png') }}" rel="shortcut icon">

    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ asset('assets/cms/css/app.css') }}" />
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->

<body class="login">
    <div class="container sm:px-10 sm:">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- BEGIN: Login Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="{{ url('/') }}" class="-intro-x flex items-center pt-5">
                    <img alt="Paket Smart WFC" class="w-6"
                        src="{{ asset('assets/logo2.png') }}">
                    <span class="text-white text-lg ml-3"> Paket Smart WFC </span>
                </a>
                <div class="my-auto">
                    <img alt="Paket Smart WFC" class="-intro-x w-1/2 -mt-16 border border-white rounded-lg"
                        src="{{ asset('assets/logo.png') }}">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                        Temukan Kebutuhan Anda
                        <br>
                        Dengan Mudah.
                    </div>
                    <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">
                        Alur kerja fleksibel, mudah untuk siapapun, maju bersama
                        <br>
                        dan mampu menjangkau relasi baru.
                    </div>
                </div>
            </div>
            <!-- END: Login Info -->
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                @yield('content')
            </div>
            <!-- END: Login Form -->
        </div>
    </div>

    <!-- BEGIN: JS Assets-->
    <script src="{{ asset('assets/cms/js/app.js') }}"></script>
    <!-- END: JS Assets-->
</body>

</html>
