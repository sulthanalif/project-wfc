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
                if (urlParams.has('search') && !filter.value) {
                    filter.value = urlParams.get('search');
                }

                let debounceTimer = null;
                filter.addEventListener("input", (e) => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        filterData(e.target.value);
                    }, 150);
                });

                filter.addEventListener("keydown", (e) => {
                    if (e.key === "Enter") {
                        e.preventDefault();
                        const params = new URLSearchParams(window.location.search);
                        const val = filter.value.trim();
                        if (val) {
                            params.set('search', val);
                        } else {
                            params.delete('search');
                        }
                        params.delete('page');
                        window.location.search = params.toString();
                    } else if (e.key === "Escape") {
                        filter.value = "";
                        filterData("");
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
                    searchIcon.setAttribute('title', 'Cari di database (Tekan Enter)');
                    searchIcon.addEventListener('click', () => {
                        const params = new URLSearchParams(window.location.search);
                        const val = filter.value.trim();
                        if (val) {
                            params.set('search', val);
                        } else {
                            params.delete('search');
                        }
                        params.delete('page');
                        window.location.search = params.toString();
                    });
                }
            }

            function getRowSearchText(row) {
                let text = '';
                row.querySelectorAll('td').forEach((td) => {
                    // Abaikan aksi dan konten modal tersembunyi
                    if (td.classList.contains('table-report__action') || td.querySelector('.modal')) {
                        return;
                    }
                    text += ' ' + (td.innerText || td.textContent || '');
                });
                return text.toLowerCase();
            }

            function filterData(search) {
                let visibleCount = 0;
                const keyword = search.trim().toLowerCase();
                const tbody = document.querySelector(".table-report tbody") || document.querySelector("tbody");
                if (!tbody) return;

                const items = tbody.querySelectorAll("tr");

                items.forEach((item) => {
                    if (item.id === 'filter-empty-message') return;
                    if (item.classList.contains('empty-row') || item.innerText.includes('Belum Ada Data')) {
                        if (keyword) item.classList.add('hidden');
                        else item.classList.remove('hidden');
                        return;
                    }

                    const rowText = getRowSearchText(item);
                    if (!keyword || rowText.includes(keyword)) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                const existingMsg = document.getElementById('filter-empty-message');
                if (keyword && visibleCount === 0) {
                    if (!existingMsg) {
                        const tr = document.createElement('tr');
                        tr.id = 'filter-empty-message';
                        tr.className = 'intro-x';
                        const colCount = tbody.closest('table') ? (tbody.closest('table').querySelectorAll('thead th').length || 6) : 6;
                        tr.innerHTML = `<td colspan="${colCount}" class="text-center py-4 text-slate-500 font-medium">Tidak ada data yang cocok di halaman ini.</td>`;
                        tbody.appendChild(tr);
                    }
                } else if (existingMsg) {
                    existingMsg.remove();
                }
            }

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
