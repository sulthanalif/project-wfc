<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content=" " />
    <meta name="keywords" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Paket Smart WFC</title>

    <link href="{{ asset('assets/logo2.png') }}" rel="shortcut icon">
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ asset('assets/cms/css/app.css') }}" />
    <!-- END: CSS Assets-->

    @stack('custom-styles')
</head>
<!-- END: Head -->

<body class="py-5 md:py-0">
    @include('cms.layouts.mobile-menu')

    @include('cms.layouts.topbar')

    <div class="flex overflow-hidden">
        @include('cms.layouts.sidebar')
        <!-- BEGIN: Content -->
        <div class="content">
            @yield('content')
        </div>
        <!-- END: Content -->
    </div>

    <!-- BEGIN: JS Assets-->
    {{-- <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script> --}}
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=[" your-google-map-api"]&libraries=places"></script> --}}
    <script src="{{ asset('assets/cms/js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filter = document.getElementById("filter");
            const urlParams = new URLSearchParams(window.location.search);

            if (filter) {
                // Inisialisasi placeholder dan title untuk pencarian server
                if (!filter.placeholder || filter.placeholder === "Cari di halaman ini...") {
                    filter.placeholder = "Cari data...";
                }
                filter.title = "Pencarian server (otomatis setelah 2 detik atau tekan Enter)";

                // Inisialisasi nilai search dari URL jika tersedia
                if (urlParams.has('search') && !filter.value) {
                    filter.value = urlParams.get('search');
                }

                let serverSearchTimer = null;

                function triggerServerSearch() {
                    clearTimeout(serverSearchTimer);
                    const params = new URLSearchParams(window.location.search);
                    const trimmedVal = filter.value.trim();
                    const currentSearch = (params.get('search') || '').trim();

                    if (trimmedVal !== currentSearch) {
                        if (trimmedVal) {
                            params.set('search', trimmedVal);
                        } else {
                            params.delete('search');
                        }
                        params.delete('page');
                        window.location.search = params.toString();
                    }
                }

                // Pencarian otomatis ke server dengan jeda 2 detik tanpa klik Enter
                filter.addEventListener("input", () => {
                    clearTimeout(serverSearchTimer);
                    serverSearchTimer = setTimeout(triggerServerSearch, 2000);
                });

                filter.addEventListener("keydown", (e) => {
                    if (e.key === "Enter") {
                        e.preventDefault();
                        triggerServerSearch();
                    } else if (e.key === "Escape") {
                        clearTimeout(serverSearchTimer);
                        filter.value = "";
                        const params = new URLSearchParams(window.location.search);
                        if (params.has('search')) {
                            params.delete('search');
                            params.delete('page');
                            window.location.search = params.toString();
                        }
                    }
                });

                const searchIcon = filter.parentElement ? filter.parentElement.querySelector('[data-lucide="search"]') : null;
                if (searchIcon) {
                    searchIcon.style.cursor = 'pointer';
                    searchIcon.setAttribute('title', 'Cari di database (Tekan Enter atau tunggu 2 detik)');
                    searchIcon.addEventListener('click', triggerServerSearch);
                }
            }

            // Pesan fallback pencarian: Tidak ada data yang cocok di halaman ini.

            document.addEventListener('change', (e) => {
                if (e.target && e.target.id === 'records_per_page') {
                    const urlParams = new URLSearchParams(window.location.search);
                    const paramName = e.target.getAttribute('data-param') || 'perPage';
                    urlParams.set(paramName, e.target.value);
                    urlParams.delete('page');
                    window.location.search = urlParams.toString();
                }
                if (e.target && e.target.id === 'records_per_status') {
                    const urlParams = new URLSearchParams(window.location.search);
                    urlParams.set('status', e.target.value);
                    urlParams.delete('page');
                    window.location.search = urlParams.toString();
                }
            });

            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
            }

        });

        function updateFileName(input) {
            const fileName = input.value.split('\\').pop(); // Extract filename from path
            const fileSpan = document.getElementById('fileName');
            if (fileSpan) {
                fileSpan.textContent = fileName || 'No file chosen'; // Set text to filename or 'No file chosen'
            }
        }
    </script>

    @stack('custom-scripts')
    <!-- END: JS Assets-->
</body>

</html>
