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
            <a class="navbar-brand logo text-uppercase" href="{{ url('/') }}">
                <img src="{{ asset('assets/logo.PNG') }}" class="logo-light" alt=""
                    height="50">
                <img src="{{ asset('assets/logo.PNG') }}" class="logo-dark" alt=""
                    height="50">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="mdi mdi-menu"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ms-auto" id="navbar-navlist">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#profil">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#katalog">Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                </ul>
                <div class="ms-auto">
                    <a href="{{ route('login') }}" class="btn bg-gradiant">{{ __('Login') }}</a>
                </div>

            </div>
        </div>
    </nav>
    <!-- END NAVBAR -->

    <!-- home section -->
    <section class="home-4 bg-soft-primary" id="home">
        <!-- start container -->
        <div class="container">
            <div class="home-content">
                <div class="row align-items-center">
                    <div class="col-lg-6 ">
                        <img src="{{ asset('assets/landing/images/img1.png') }}" alt="" class="img-fluid">
                    </div>
                    <div class="col-lg-6 ">
                        <h1 class="display-4 fw-bold">Paket Smart WFC</h1>
                        <h4>Temukan Kebutuhan Anda dengan Mudah.</h4>
                        <p class="text-muted mt-4">Alur kerja fleksibel, mudah untuk siapapun, maju bersama dan mampu menjangkau relasi baru.</p>

                        <div class="d-flex mt-4">
                            <a href="#profil" class="btn btn-outline-primary">
                                Lihat Selengkapnya</a>
                        </div>
                    </div>

                    <div class="col-lg-6">

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="logo-box ">
                        <div class="client-slider" id="client-slider">
                            <div class="item text-center">
                                <img src="{{ asset('assets/logo.PNG') }}" alt=""
                                    class="img-fluid">
                            </div>
                            <!-- slider item -->

                            <div class="item text-center">
                                <img src="{{ asset('assets/logo.PNG') }}" alt=""
                                    class="img-fluid">
                            </div>
                            <!-- slider item -->

                            <div class="item text-center">
                                <img src="{{ asset('assets/logo.PNG') }}" alt=""
                                    class="img-fluid">
                            </div>
                            <!-- slider item -->

                            <div class="item text-center">
                                <img src="{{ asset('assets/logo.PNG') }}" alt=""
                                    class="img-fluid">
                            </div>
                            <!-- slider item -->

                            <div class="item text-center">
                                <img src="{{ asset('assets/logo.PNG') }}" alt=""
                                    class="img-fluid">
                            </div>
                            <!-- slider item -->

                            <div class="item text-center">
                                <img src="{{ asset('assets/logo.PNG') }}" alt=""
                                    class="img-fluid">
                            </div>
                            <!-- slider item -->
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- end container -->
    </section>
    <!-- end home section -->

    <!-- service section -->
    <section class="section service bg-light" id="profil">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 ">
                    <h6 class="mb-0 fw-bold text-primary">Profil</h6>
                    <h1 class="display-5 fw-bold">Paket Smart WFC</h1>
                    <p class="text-muted mt-4">Paket Smart WFC berdiri sejak tahun 2019, dan alhamdulillah ditahun ini total agent kami hampir 100 agent yang terbagi di berbagai kota, seperti di Sumedang, Bandung, Ciamis, Tasikmalaya, Cianjur, Depok...</p>

                    <div class="d-flex mt-4">
                        <a href="{{ route('company-profile') }}" class="btn btn-outline-primary">
                            Baca Selengkapnya <i class="mdi mdi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 ">
                    <img src="{{ asset('assets/landing/images/img2.png') }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->

    <!-- start testimonial -->
    <section class="section testimonial" id="katalog">
        <!-- start container -->
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <div class="title">
                        <p class=" text-primary fw-bold mb-0">Katalog</p>
                        <h3>Apa yang disediakan oleh kami!</h3>
                        <p class="text-muted">Berikut adalah daftar produk-produk yang kami sediakan.</p>
                        <a href="{{ route('catalogs-product') }}" class="btn bg-gradiant">Lihat Semua <i class="mdi mdi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="testi-slider" id="testi-slider">
                        <div class="item">
                            <div class="testi-box position-relative overflow-hidden">
                                <div class="row align-items-center">
                                    <div class="col-md-5 text-center">
                                        <img src="{{ asset('assets/logo2.png') }}" alt="" class="img-fluid">
                                    </div>
                                    <div class="col-md-7">
                                        <div class="p-4">
                                            <div class="">
                                                <h5 class="fw-bold">Paket Smart 2023-2024.</h5>
                                                <p class="text-muted f-14">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam, pariatur in! Adipisci eum nisi eaque officia recusandae quibusdam, inventore deleniti?.
                                                </p>
                                            </div>
                                            <div class="d-flex align-items-center mt-3">
                                                    <div class="date">
                                                        <p class="text-muted mb-0 f-14">28 jan, 2024 <span>10:25
                                                                AM</span></p>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="item">
                            <div class="testi-box position-relative overflow-hidden">
                                <div class="row align-items-center">
                                    <div class="col-md-5 text-center">
                                        <img src="{{ asset('assets/logo2.png') }}" alt="" class="img-fluid">
                                    </div>
                                    <div class="col-md-7">
                                        <div class="p-4">
                                            <div class="">
                                                <h5 class="fw-bold">Paket Smart 2023-2024.</h5>
                                                <p class="text-muted f-14">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam, pariatur in! Adipisci eum nisi eaque officia recusandae quibusdam, inventore deleniti?.
                                                </p>
                                            </div>
                                            <div class="d-flex align-items-center mt-3">
                                                    <div class="date">
                                                        <p class="text-muted mb-0 f-14">28 jan, 2024 <span>10:25
                                                                AM</span></p>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="item">
                            <div class="testi-box position-relative overflow-hidden">
                                <div class="row align-items-center">
                                    <div class="col-md-5 text-center">
                                        <img src="{{ asset('assets/logo2.png') }}" alt="" class="img-fluid">
                                    </div>
                                    <div class="col-md-7">
                                        <div class="p-4">
                                            <div class="">
                                                <h5 class="fw-bold">Paket Smart 2023-2024.</h5>
                                                <p class="text-muted f-14">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam, pariatur in! Adipisci eum nisi eaque officia recusandae quibusdam, inventore deleniti?.
                                                </p>
                                            </div>
                                            <div class="d-flex align-items-center mt-3">
                                                    <div class="date">
                                                        <p class="text-muted mb-0 f-14">28 jan, 2024 <span>10:25
                                                                AM</span></p>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="item">
                            <div class="testi-box position-relative overflow-hidden">
                                <div class="row align-items-center">
                                    <div class="col-md-5 text-center">
                                        <img src="{{ asset('assets/logo2.png') }}" alt="" class="img-fluid">
                                    </div>
                                    <div class="col-md-7">
                                        <div class="p-4">
                                            <div class="">
                                                <h5 class="fw-bold">Paket Smart 2023-2024.</h5>
                                                <p class="text-muted f-14">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam, pariatur in! Adipisci eum nisi eaque officia recusandae quibusdam, inventore deleniti?.
                                                </p>
                                            </div>
                                            <div class="d-flex align-items-center mt-3">
                                                    <div class="date">
                                                        <p class="text-muted mb-0 f-14">28 jan, 2024 <span>10:25
                                                                AM</span></p>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end container -->
    </section>
    <!-- end testimonial -->

    <!-- slider section -->
    <section class="section app-slider bg-light" id="app">
        <!-- start container -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="title text-center mb-5">
                        <h6 class="mb-0 fw-bold text-primary">Galeri</h6>
                        <h2 class="f-40">Show our App Screenshots!</h2>
                        <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor eos <br>
                            inventore omnis aliquid rerum alias molestias.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="swiper-container">
                        <div class="fream-phone ">
                            <img src="{{ asset('assets/landing/images/testi/phone-fream.png') }}" alt=""
                                class="img-fluid">
                        </div>

                        <div class="swiper-wrapper">
                            <div class="swiper-slide border-radius">
                                <img src="{{ asset('assets/landing/images/testi/ss/s-1.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                            <div class="swiper-slide border-radius">
                                <img src="{{ asset('assets/landing/images/testi/ss/s-1.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                            <div class="swiper-slide border-radius">
                                <img src="{{ asset('assets/landing/images/testi/ss/s-1.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                            <div class="swiper-slide border-radius">
                                <img src="{{ asset('assets/landing/images/testi/ss/s-1.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                            <div class="swiper-slide border-radius">
                                <img src="{{ asset('assets/landing/images/testi/ss/s-1.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                            <div class="swiper-slide border-radius">
                                <img src="{{ asset('assets/landing/images/testi/ss/s-1.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                        </div>

                        <!-- navigation buttons -->
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
            <!-- end container -->
        </div>
    </section>
    <!-- end section -->

    <!-- contact section -->
    <section class="section contact overflow-hidden" id="kontak">
        <!-- start container -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="title text-center mb-5">
                        <h6 class="mb-0 fw-bold text-primary">Kontak Kami</h6>
                        <h2 class="f-40">Ayo Hubungi Kami!</h2>
                        <p class="text-muted">Dapatkan informasi lebih lanjut dan bantuan dari tim kami.</p>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5">
                    <div class="contact-box">
                        <div class="row mt-4 gap-2">
                            <div class="card shadow">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-google-maps f-50 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">Lokasi</h5>
                                        <p class="f-14 mb-0 text-muted">Jl. Cipareuag No. 5, Cihanjuang, Cimanggung, Sumedang</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow">
                                <div class="d-flex align-items-center mt-4 mt-lg-0">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-email f-50 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">Email</h5>
                                        <p class="f-14 mb-0 text-muted">Email: FredVWeaver@rhyta.com</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow">
                                <div class="d-flex align-items-center mt-4 mt-lg-0">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-phone f-50 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">Phone</h5>
                                        <p class="f-14 mb-0 text-muted">2276 Lynn Ogden Lane Beaumont</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="col-lg-7">
                    <div class="m-5">
                        <div class="position-relative">
                            <div class="contact-map">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.4626033254904!2d107.81037477442035!3d-6.954630468087016!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68c521f856593f%3A0xda1dc7320ea63c2b!2sCV.%20Wida%20Nugraha!5e0!3m2!1sen!2sid!4v1712995312675!5m2!1sen!2sid" width="550" height="450" style="border:0;" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                            <div class="map-shape"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end container -->
    </section>
    <!-- end section -->

    <!-- footer section -->
    <section class=" section footer bg-dark overflow-hidden">
        <div class="bg-arrow">
            <img src="images/home/arrow-bg.png" alt="" class="">
        </div>
        <!-- container -->
        <div class="container">
            <div class="row ">
                <div class="col-lg-4">
                    <a class="navbar-brand logo f-30 text-white fw-bold" href="#home">
                        <img src="{{ asset('assets/logo.PNG') }}" class="logo-light"
                            alt="" height="40">
                    </a>

                    <div class="footer-icon mt-4">
                        <div class=" d-flex align-items-center">
                            <a href="" class="me-2 avatar-sm text-center" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Facebook">
                                <i class="mdi mdi-facebook f-24 align-middle text-primary"></i>
                            </a>
                            <a href="" class="mx-2 avatar-sm text-center" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="twitter">
                                <i class="mdi mdi-twitter f-24 align-middle text-primary"></i>
                            </a>
                            <a href="" class="mx-2 avatar-sm text-center" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="googleplay">
                                <i class="mdi mdi-google-play f-24 align-middle text-primary"></i>
                            </a>
                            <a href="" class="mx-2 avatar-sm text-center">
                                <i class="mdi mdi-linkedin f-24 align-middle text-primary" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="linkedin"></i>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-md-2 ">
                    <div class="text-start mt-4 mt-lg-0">
                        <h5 class="text-white fw-bold">Menu</h5>
                        <ul class="footer-item list-unstyled footer-link mt-3">
                            <li><a href="#home">Home</a></li>
                            <li><a href="#profil">Profil</a></li>
                            <li><a href="#katalog">Katalog</a></li>
                            <li><a href="#kontak">Kontak</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-2 ">
                    <div class="text-start">
                        <h5 class="text-white fw-bold">Ketentuan</h5>
                        <ul class="footer-item list-unstyled footer-link mt-3">
                            <li><a href="#">Syarat & Kondisi</a></li>
                            <li><a href="#">Kebijakan Privasi</a></li>
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