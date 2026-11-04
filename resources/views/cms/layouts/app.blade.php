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

    <style>
        @keyframes tableSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .table-loading-spinner {
            animation: tableSpin 0.75s linear infinite;
        }
        .table-loading-overlay {
            position: absolute;
            inset: 0;
            background-color: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 30;
            min-height: 180px;
            border-radius: 0.5rem;
        }
        .dark .table-loading-overlay {
            background-color: rgba(15, 23, 42, 0.72);
        }
    </style>

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

            // Fungsi global untuk menampilkan loading overlay pada tabel
            function showTableLoading() {
                const tableWrappers = document.querySelectorAll('.table-container, .table-report');
                tableWrappers.forEach(container => {
                    let overlay = container.querySelector('.table-loading-overlay');
                    if (!overlay) {
                        overlay = document.createElement('div');
                        overlay.className = 'table-loading-overlay';
                        overlay.innerHTML = `
                            <div class="flex flex-col items-center justify-center p-5 bg-white/95 dark:bg-darkmode-600/95 rounded-xl shadow-lg border border-slate-200/80 dark:border-darkmode-400">
                                <svg class="table-loading-spinner w-8 h-8 text-primary mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Memuat data...</span>
                            </div>
                        `;
                        const pos = window.getComputedStyle(container).position;
                        if (pos === 'static') {
                            container.style.position = 'relative';
                        }
                        container.appendChild(overlay);
                    }
                    overlay.style.display = 'flex';
                    container.style.opacity = '0.55';
                    container.style.pointerEvents = 'none';
                });
            }

            // Expose agar bisa dipanggil komponen blade jika diperlukan
            window.showTableLoading = showTableLoading;

            if (filter) {
                // Inisialisasi placeholder dan title untuk pencarian server
                if (!filter.placeholder || filter.placeholder === "Cari di halaman ini...") {
                    filter.placeholder = "Cari data...";
                }
                filter.title = "Pencarian server (otomatis setelah 500ms atau tekan Enter)";

                // Inisialisasi nilai search dari URL jika tersedia
                if (urlParams.has('search') && !filter.value) {
                    filter.value = urlParams.get('search');
                }

                let serverSearchTimer = null;
                const searchIcon = filter.parentElement ? filter.parentElement.querySelector('[data-lucide="search"]') : null;

                function setSearchInputSpinning(isSpinning) {
                    if (searchIcon) {
                        if (isSpinning) {
                            searchIcon.classList.add('table-loading-spinner');
                            searchIcon.style.color = '#1e40af';
                        } else {
                            searchIcon.classList.remove('table-loading-spinner');
                            searchIcon.style.color = '';
                        }
                    }
                }

                function triggerServerSearch() {
                    clearTimeout(serverSearchTimer);
                    setSearchInputSpinning(false);

                    const params = new URLSearchParams(window.location.search);
                    const trimmedVal = filter.value.trim();
                    const currentSearch = (params.get('search') || '').trim();

                    if (trimmedVal !== currentSearch) {
                        showTableLoading();
                        if (trimmedVal) {
                            params.set('search', trimmedVal);
                        } else {
                            params.delete('search');
                        }
                        params.delete('page');
                        window.location.search = params.toString();
                    }
                }

                // Pencarian otomatis ke server dengan jeda 500ms tanpa klik Enter
                filter.addEventListener("input", () => {
                    clearTimeout(serverSearchTimer);
                    setSearchInputSpinning(true);
                    serverSearchTimer = setTimeout(triggerServerSearch, 500);
                });

                filter.addEventListener("keydown", (e) => {
                    if (e.key === "Enter") {
                        e.preventDefault();
                        triggerServerSearch();
                    } else if (e.key === "Escape") {
                        clearTimeout(serverSearchTimer);
                        setSearchInputSpinning(false);
                        filter.value = "";
                        const params = new URLSearchParams(window.location.search);
                        if (params.has('search')) {
                            showTableLoading();
                            params.delete('search');
                            params.delete('page');
                            window.location.search = params.toString();
                        }
                    }
                });

                if (searchIcon) {
                    searchIcon.style.cursor = 'pointer';
                    searchIcon.setAttribute('title', 'Cari di database (Tekan Enter atau tunggu 500ms)');
                    searchIcon.addEventListener('click', triggerServerSearch);
                }
            }

            // Pesan fallback pencarian: Tidak ada data yang cocok di halaman ini.

            document.addEventListener('change', (e) => {
                if (e.target && e.target.id === 'records_per_page') {
                    showTableLoading();
                    const urlParams = new URLSearchParams(window.location.search);
                    const paramName = e.target.getAttribute('data-param') || 'perPage';
                    urlParams.set(paramName, e.target.value);
                    urlParams.delete('page');
                    window.location.search = urlParams.toString();
                }
                if (e.target && e.target.id === 'records_per_status') {
                    showTableLoading();
                    const urlParams = new URLSearchParams(window.location.search);
                    urlParams.set('status', e.target.value);
                    urlParams.delete('page');
                    window.location.search = urlParams.toString();
                }
            });

            // Loading saat pagination diklik
            document.addEventListener('click', (e) => {
                const pageLink = e.target.closest('.pagination a');
                if (pageLink && pageLink.href && !pageLink.href.includes('#')) {
                    showTableLoading();
                }
            });

            // Reset loading jika halaman dibuka dari cache browser (tombol Back)
            window.addEventListener('pageshow', () => {
                document.querySelectorAll('.table-loading-overlay').forEach(el => el.remove());
                document.querySelectorAll('.table-container, .table-report').forEach(el => {
                    el.style.opacity = '';
                    el.style.pointerEvents = '';
                });
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
