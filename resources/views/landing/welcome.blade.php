@extends('landing.layout', ['title' => 'Paket Smart WFC'])

@section('content')
    <!-- START  NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom sticky sticky-light" id="navbar">
        <div class="container-fluid container-lg">

            <!-- LOGO -->
            <a class="navbar-brand logo text-uppercase" href="{{ route('landing-page') }}">
                <img src="{{ asset('assets/logo.png') }}" class="logo-light" alt="" height="50">
                <img src="{{ asset('assets/logo.png') }}" class="logo-dark" alt="" height="50">
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

    <!-- home section -->
    <section class="home-4 bg-soft-primary" id="home">
        <!-- start container -->
        <div class="container">
            <div class="home-content">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        {{-- <img src="{{ asset('assets/landing/images/img1.png') }}" alt="" class="img-fluid"> --}}
                        <img src="{{ asset('assets/logo2.png') }}" class="img-fluid ms-5" style="width: 300px">
                    </div>
                    <div class="col-lg-6 ">
                        <h1 class="display-4 fw-bold">Paket Smart WFC</h1>
                        <h4>Temukan Kebutuhan Anda dengan Mudah.</h4>
                        <p class="text-muted mt-4">Alur kerja fleksibel, mudah untuk siapapun, maju bersama dan mampu
                            menjangkau relasi baru.</p>

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
                                <img src="{{ asset('assets/logo.png') }}" alt="" class="img-fluid">
                            </div>
                            <!-- slider item -->

                            <div class="item text-center">
                                <img src="{{ asset('assets/logo.png') }}" alt="" class="img-fluid">
                            </div>
                            <!-- slider item -->

                            <div class="item text-center">
                                <img src="{{ asset('assets/logo.png') }}" alt="" class="img-fluid">
                            </div>
                            <!-- slider item -->

                            <div class="item text-center">
                                <img src="{{ asset('assets/logo.png') }}" alt="" class="img-fluid">
                            </div>
                            <!-- slider item -->

                            <div class="item text-center">
                                <img src="{{ asset('assets/logo.png') }}" alt="" class="img-fluid">
                            </div>
                            <!-- slider item -->

                            <div class="item text-center">
                                <img src="{{ asset('assets/logo.png') }}" alt="" class="img-fluid">
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
                    <p class="text-muted mt-4">Paket Smart WFC berdiri sejak tahun 2019, dan alhamdulillah ditahun ini
                        total agent kami hampir 100 agent yang terbagi di berbagai kota, seperti di Sumedang, Bandung,
                        Ciamis, Tasikmalaya, Cianjur, Depok...</p>

                    <div class="d-flex mt-4">
                        <a href="{{ route('company-profile') }}" class="btn btn-outline-primary">
                            Baca Selengkapnya <i class="mdi mdi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-3 mt-lg-0">
                    <img src="{{ asset('assets/pemilik.jpg') }}" alt="" class="img-fluid img-thumbnail rounded"
                        style="max-height: 500px">
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
                        <a href="{{ route('catalogs-product') }}" class="btn bg-gradiant">Lihat Semua <i
                                class="mdi mdi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="testi-slider" id="testi-slider">
                        @if ($products->isEmpty())
                            <div class="item">
                                <div class="testi-box position-relative overflow-hidden">
                                    <h4 class="text-center fw-bold p-5">Belum ada produk</h4>
                                </div>
                            </div>
                        @else
                            @foreach ($products as $product)
                                <div class="item">
                                    <div class="testi-box position-relative overflow-hidden">
                                        <div class="row align-items-center">
                                            <div class="col-md-5 text-center px-3">
                                                @if ($product->detail->image === 'image.jpg' || $product->detail->image == null)
                                                    <img src="{{ asset('assets/logo2.png') }}" alt="Gambar Produk"
                                                        class="img-fluid">
                                                @else
                                                    <img src="{{ route('getImage', ['path' => 'product', 'imageName' => $product->detail->image]) }}"
                                                        alt="" class="img-fluid">
                                                @endif
                                            </div>
                                            <div class="col-md-7">
                                                <div class="p-4">
                                                    <div class="">
                                                        <h5 class="fw-bold f-24">{{ $product->name }}</h5>
                                                        <p class="text-muted">
                                                            {{ Str::limit(strip_tags($product->detail->description), 250) }}
                                                        </p>
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <div class="date">
                                                            <p class="text-muted mb-0 f-14">Rp.
                                                                {{ number_format($product->price, 0, ',', '.') }}/hari
                                                                <span>({{ $product->days }} hari)</span>
                                                            </p>
                                                        </div>
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
                        <h2 class="f-40">Highlight Of Collection!</h2>
                        <p class="text-muted">Berikut adalah momen kegiatan yang telah dilakukan.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="swiper-container">
                        {{-- <div class="fream-phone ">
                            <img src="{{ asset('assets/landing/images/testi/phone-fream.png') }}" alt=""
                                class="img-fluid">
                        </div> --}}

                        <div class="swiper-wrapper">
                            <div class="swiper-slide border-radius cust-slide">
                                <img src="{{ asset('assets/landing/images/galeri/1.jpeg') }}" alt="">
                            </div>
                            <div class="swiper-slide border-radius cust-slide">
                                <img src="{{ asset('assets/landing/images/galeri/2.jpeg') }}" alt="">
                            </div>
                            <div class="swiper-slide border-radius cust-slide">
                                <img src="{{ asset('assets/landing/images/galeri/3.jpeg') }}" alt="">
                            </div>
                            <div class="swiper-slide border-radius cust-slide">
                                <img src="{{ asset('assets/landing/images/galeri/4.jpeg') }}" alt="">
                            </div>
                            <div class="swiper-slide border-radius cust-slide">
                                <img src="{{ asset('assets/landing/images/galeri/5.jpeg') }}" alt="">
                            </div>
                            <div class="swiper-slide border-radius cust-slide">
                                <img src="{{ asset('assets/landing/images/galeri/6.jpeg') }}" alt="">
                            </div>
                            <div class="swiper-slide border-radius cust-slide">
                                <img src="{{ asset('assets/landing/images/galeri/7.jpeg') }}" alt="">
                            </div>
                            <div class="swiper-slide border-radius cust-slide">
                                <img src="{{ asset('assets/landing/images/galeri/8.jpeg') }}" alt="">
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
                                        <p class="f-14 mb-0 text-muted">Jl. Cipareuag No. 5, Cihanjuang, Cimanggung,
                                            Sumedang</p>
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
                                        <p class="f-14 mb-0 text-muted">Email: paketsmartwfc@gmail.com</p>
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
                                        <p class="f-14 mb-0 text-muted">Whatsapp: 0822-1879-9050</p>
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
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.4626033254904!2d107.81037477442035!3d-6.954630468087016!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68c521f856593f%3A0xda1dc7320ea63c2b!2sCV.%20Wida%20Nugraha!5e0!3m2!1sen!2sid!4v1712995312675!5m2!1sen!2sid"
                                    width="550" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
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
@endsection
