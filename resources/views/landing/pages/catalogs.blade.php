<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content=" " />
    <meta name="keywords" content="" />

    <title>Paket Smart WFC</title>

    <link href="{{ asset('assets/logo2.PNG') }}" rel="shortcut icon">

    <!-- Bootstrap css -->
    <link rel="stylesheet" href="{{ asset('assets/landing/css/bootstrap.min.css') }}" type="text/css"
        id="bootstrap-style" />

    <!-- Material Icon Css -->
    <link rel="stylesheet" href="{{ asset('assets/landing/css/materialdesignicons.min.css') }}" type="text/css" />

    <!-- Unicon Css -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />

    <!-- Swiper Css -->
    <link rel="stylesheet" href="{{ asset('assets/landing/css/tiny-slider.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/landing/css/swiper.min.css') }}" type="text/css" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('assets/landing/css/style.css') }}" type="text/css" />

    <!-- colors -->
    <link href="{{ asset('assets/landing/css/colors/default.css') }}" rel="stylesheet" type="text/css" id="color-opt" />

    <!-- Styles -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>

<body data-bs-spy="scroll" data-bs-target="#navbar" data-bs-offset="71">
    <!-- light-dark mode button -->
    <a href="javascript: void(0);" id="mode" class="mode-btn text-white rounded-end">
        <i class="uil uil-brightness mode-dark mx-auto"></i>
        <i class="uil uil-moon bx-spin mode-light"></i>
    </a>

    <!-- START  NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom sticky sticky-light" id="navbar">
        <div class="container-fluid container-lg">

            <!-- LOGO -->
            <a class="navbar-brand logo text-uppercase" href="{{ route('landing-page') }}">
                <img src="{{ asset('assets/logo.PNG') }}" class="logo-light" alt="" height="50">
                <img src="{{ asset('assets/logo.PNG') }}" class="logo-dark" alt="" height="50">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="mdi mdi-menu"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="ms-auto">
                    @if (Auth::check())
                        @if (auth()->user()->hasRole('super_admin|admin|finance_admin'))
                            <a href="{{ route('dashboard-admin') }}" class="btn bg-gradiant"><img alt="Admin"
                                    src="{{ asset('assets/cms/images/profile.svg') }}" height="25"></a>
                        @elseif (auth()->user()->hasRole('agent'))
                            <a href="{{ route('dashboard-agent') }}" class="btn bg-gradiant"><img alt="Agent"
                                    src="{{ asset('assets/cms/images/profile.svg') }}" height="25"></a>
                        @else
                            <a href="#" class="btn bg-gradiant"><img alt="NewAgent"
                                    src="{{ asset('assets/cms/images/profile.svg') }}" height="25"></a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn bg-gradiant">{{ __('Login') }}</a>
                    @endif
                </div>

            </div>
        </div>
    </nav>
    <!-- END NAVBAR -->

    <!-- katalog section -->
    <section class="section team service">
        <!-- start container -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="title text-center mb-5">
                        <h6 class="mb-0 fw-bold text-primary">Katalog</h6>
                        <h2 class="f-40">Daftar Katalog Paket Smart WFC</h2>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-4">
                @if ($products->isEmpty())
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="title text-center mb-5">
                                <h2 class="f-30">Belum Ada Katalog Paket</h2>
                            </div>
                        </div>
                    </div>
                @else
                    @foreach ($products as $product)
                        <div class="col-6 col-lg-3 col-md-6 mb-5">
                            <a href="#detailModal" class="text-primary" data-bs-toggle="modal">
                                <div class="service-box">
                                    <div class="team-box text-start">
                                        <div class="row justify-content-end">
                                            <div class="col-lg-10 col-9">
                                                <div class="team-image">
                                                    <img src="{{ asset('storage/images/product/' . $product->detail->image) }}"
                                                        alt="" class="img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="team-info position-absolute">
                                            <p class="h6 fw-bold">{{ $product->name }} <span
                                                    class="f-14 text-muted fw-normal">/
                                                    {{ $product->packagerName->name }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div class="modal fade bd-example-modal-lg" id="detailModal" data-keyboard="false"
                                tabindex="-1" aria-hidden="true">
                                <div
                                    class="modal-dialog modal-dialog-centered modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content hero-modal-0">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $product->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center mb-2">
                                                <img src="{{ asset('storage/images/product/' . $product->detail->image) }}"
                                                    alt="" class="img-fluid">
                                            </div>
                                            <div class="flex flex-row p-0 m-0">
                                                <p>Supplier: <span class="text-muted">{{ $product->supplierName->name }}</span></p>
                                                <p>Paket: <span class="text-muted">{{ $product->packagerName->name }}</span></p>
                                                <p>Harga: <span class="text-muted">Rp.
                                                    {{ number_format($product->price, 0, ',', '.') }}/hari</span></p>
                                                <p>Jangka Waktu: <span class="text-muted">{{ $product->days }} hari</span></p>
                                                <p>Deskripsi: <span class="text-muted">{!! $product->detail->description !!}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <!-- end container -->
    </section>
    <!-- end section -->

    <!-- footer section -->
    <section class="section footer bg-dark overflow-hidden">
        <div class="bg-arrow">
            {{-- <img src="{{ asset('assets/landing/images/home/arrow-bg.png') }}" alt="" class=""> --}}
        </div>
        <!-- container -->
        <div class="container">
            <div class="row gap-3 gap-lg-0">
                <div class="col-lg-4">
                    <a class="navbar-brand logo f-30 text-white fw-bold" href="#home">
                        <img src="{{ asset('assets/logo.PNG') }}" class="logo-light" alt="" height="40">
                    </a>

                    <div class="footer-icon mt-4">
                        <div class=" d-flex align-items-center">
                            <a href="https://wa.me/6282218799050" class="me-2 avatar-sm text-center"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Whatsapp" target="_blank">
                                <i class="mdi mdi-whatsapp f-24 align-middle text-primary"></i>
                            </a>
                            <a href="" class="mx-2 avatar-sm text-center" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Facebook" target="_blank">
                                <i class="mdi mdi-facebook f-24 align-middle text-primary"></i>
                            </a>
                            <a href="https://www.instagram.com/paketsmartwfc/" class="mx-2 avatar-sm text-center"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Instagram" target="_blank">
                                <i class="mdi mdi-instagram f-24 align-middle text-primary"></i>
                            </a>
                            <a href="" class="mx-2 avatar-sm text-center" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Email" target="_blank">
                                <i class="mdi mdi-email f-24 align-middle text-primary"></i>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="text-start">
                        <h5 class="text-white fw-bold">Ketentuan</h5>
                        <ul class="footer-item list-unstyled footer-link mt-3">
                            <li><a href="#syarat" data-bs-toggle="modal">Syarat & Ketentuan</a></li>
                            <div class="modal fade bd-example-modal-lg" id="syarat" data-keyboard="false"
                                tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg">
                                    <div class="modal-content hero-modal-0 bg-transparent">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                        <img src="{{ asset('assets/syarat.jpg') }}" alt="Syarat dan Ketentuan">
                                    </div>
                                </div>
                            </div>
                            <li><a href="#kebijakan" data-bs-toggle="modal">Kebijakan Privasi</a></li>
                            <div class="modal fade bd-example-modal-lg" id="kebijakan" data-keyboard="false"
                                tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg">
                                    <div class="modal-content hero-modal-0 bg-transparent">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                        <img src="{{ asset('assets/kebijakan.jpg') }}" alt="Syarat dan Ketentuan">
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4">
                    <h5 class="text-white">Pendaftaran</h5>
                    <div class="input-group my-4">
                        <input type="text" class="form-control p-3" placeholder="subscribe"
                            aria-label="subscribe" aria-describedby="basic-addon2">
                        <a href="" class="input-group-text bg-primary text-white px-4"
                            id="basic-addon2">Go</a>
                    </div>
                    <p class="mb-0 text-white-50">bagi yang ingin menjadi agen silahkan daftar dengan menekan tombol
                        diatas.
                    </p>
                </div>
            </div>
        </div>
        <!-- end container -->
    </section>

    <section class="bottom-footer py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <p class="mb-0 text-center text-muted">Copyright ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> CV. WIDA NUGRAHA. All Right Reserved
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- end footer -->


    <!--Bootstrap Js-->
    <script src="{{ asset('assets/landing/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Slider Js -->
    <script src="{{ asset('assets/landing/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets/landing/js/swiper.min.js') }}"></script>

    <!-- <script src="js/smooth-scroll.polyfills.min.js"></script> -->

    <!-- counter -->
    <!-- <script src="js/counter.init.js"></script> -->

    <!-- App Js -->
    <script src="{{ asset('assets/landing/js/app.js') }}"></script>

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>

    <script>
        var slider = tns({
            container: '.client-slider',
            loop: true,
            autoplay: true,
            mouseDrag: true,
            controls: false,
            navPosition: "bottom",
            nav: false,
            autoplayTimeout: 5000,
            speed: 900,
            center: false,
            animateIn: "fadeIn",
            animateOut: "fadeOut",
            controlsText: ['&#8592;', '&#8594;'],
            autoplayButtonOutput: false,
            gutter: 30,
            responsive: {

                992: {
                    gutter: 30,
                    items: 4
                },

            }
        });
    </script>
</body>

</html>
