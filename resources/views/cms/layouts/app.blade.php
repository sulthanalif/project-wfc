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
            const items = document.querySelectorAll("tbody tr");

            if (filter) {
                filter.addEventListener("input", (e) => filterData(e.target.value));
            }

            function filterData(search) {
                let visibleCount = 0;
                const keyword = search.trim().toLowerCase();
                const tbody = document.querySelector("tbody");

                items.forEach((item) => {
                    if (item.id === 'filter-empty-message') return;
                    if (!keyword || item.innerText.toLowerCase().includes(keyword)) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                const existingMsg = document.getElementById('filter-empty-message');
                if (keyword && visibleCount === 0 && tbody) {
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
