<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart WFC</title>

    <link href="{{ asset('assets/logo2.png') }}" rel="shortcut icon">
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ asset('assets/cms/css/app.css') }}" />
    <!-- END: CSS Assets-->
</head>
<body>
    <div class="bg-white shadow-md rounded-lg p-6 max-w-sm mx-auto">
        <h1 class="text-2xl font-bold mb-4 text-center">{{ __('Selamat!') }}</h1>
        <p class="text-gray-700 text-center mb-4">{{ __('Akun anda telah diaktifkan') }}</p>
        <div class="text-center">
            <button class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                <a href="{{ route('dashboard-agent') }}" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                    Klik Disini
                </a>
            </button>
        </div>
    </div>

    <script src="{{ asset('assets/cms/js/app.js') }}"></script>
</body>
</html>
