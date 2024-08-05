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
                        <a class="nav-link" href="#{{ $header->buttonUrl }}">Profil</a>
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
                        <img src="{{ empty($header->image) ? asset('assets/logo2.png') : route('getImage', ['path' => 'landingpage', 'imageName' => $header->image]) }}"
                            class="img-fluid ms-5" style="width: 300px">
                    </div>
                    <div class="col-lg-6 ">
                        <h1 class="display-4 fw-bold">{{ $header->title }}</h1>
                        <h4>{{ $header->subTitle }}</h4>
                        <p class="text-muted mt-4">{!! Str::limit($header->description, 100, '...') !!}</p>

                        <div class="d-flex mt-4">
                            <a href="#{{ $header->buttonUrl }}" class="btn btn-outline-primary">
                                {{ $header->buttonTitle }}</a>
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
    <section class="section service bg-light" id="{{ $header->buttonUrl }}">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 ">
                    <h6 class="mb-0 fw-bold text-primary">Profil</h6>
                    <h1 class="display-5 fw-bold">{{ $profile->title }}</h1>
                    <p class="text-muted mt-4">
                        {!! Str::limit($profile->description, 200, '...') !!}
                    </p>

                    <div class="d-flex mt-4">
                        <a href="{{ route('company-profile') }}" class="btn btn-outline-primary">
                            {{ $profile->buttonTitle }} <i class="mdi mdi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-3 mt-lg-0">
                    <img src="{{ empty($profile->image) ? asset('assets/pemilik.jpg') : route('getImage', ['path' => 'landingpage', 'imageName' => $profile->image]) }}"
                        alt="" class="img-fluid img-thumbnail rounded" style="max-height: 500px">
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->

    <!-- start product -->
    <section class="section product" id="katalog">
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
                    <div class="product-slider" id="product-slider">
                        @if ($products->isEmpty())
                            <div class="item">
                                <div class="product-box position-relative overflow-hidden">
                                    <h4 class="text-center fw-bold p-5">Belum ada produk</h4>
                                </div>
                            </div>
                        @else
                            @foreach ($products as $product)
                                <div class="item">
                                    <div class="product-box position-relative overflow-hidden">
                                        <div class="row align-items-center">
                                            <div class="col-md-5 text-center px-3">
                                                @if ($product->detail->image === 'image.jpg' || $product->detail->image == null)
                                                    <img src="{{ asset('assets/logo2.png') }}" alt="Gambar Produk"
                                                        class="img-fluid">
                                                @else
                                                    <img src="{{ route('getImage', ['path' => 'product', 'imageName' => $product->detail->image]) }}"
                                                        alt="" class="img-fluid object-fit-contain"
                                                        style="height: 200px">
                                                @endif
                                            </div>
                                            <div class="col-md-7">
                                                <div class="p-4">
                                                    <div class="">
                                                        <div class="border-bottom border-primary pb-1">
                                                            <h5 class="fw-bold f-24 lh-sm">
                                                                {{ $product->name }} <br>
                                                                <span
                                                                    class="text-muted fw-normal f-16">{{ $product->is_safe_point == 1 ? '(Titik Aman)' : '' }}</span>
                                                            </h5>
                                                        </div>
                                                        <p class="text-muted mt-2">
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
    <!-- end product -->

    <!-- slider section -->
    <section class="section app-slider bg-light" id="app">
        <!-- start container -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="title text-center mb-5">
                        <h6 class="mb-0 fw-bold text-primary">Galeri</h6>
                        <h2 class="f-40">{{ $gallery->title }}</h2>
                        <p class="text-muted">{{ $gallery->subTitle }}.</p>
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
                            @if ($gallery->images->isEmpty())
                                <div class="swiper-slide border-radius cust-slide">
                                    <img src="{{ asset('assets/logo2.png') }}" alt="">
                                </div>
                            @else
                                @foreach ($gallery->images as $image)
                                    <div class="swiper-slide border-radius cust-slide">
                                        <img src="{{ route('getImage', ['path' => 'landingpage', 'imageName' => $image->image]) }}"
                                            alt="">
                                    </div>
                                @endforeach
                            @endif
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

    <!-- start testimonial -->
    <section class="section testimonial">
        <!-- start container -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="title text-center mb-5">
                        <h6 class="mb-0 fw-bold text-primary">Review</h6>
                        <h2 class="f-40">{{ $reviewPage->title }}</h2>
                        <p class="text-muted">{{ $reviewPage->subTitle }}</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="testi-slider" id="testi-slider">
                        @if ($reviews->isEmpty())
                            <div class="item">
                                <div class="testi-box position-relative overflow-hidden justify-content-center">
                                    <h4 class="text-center fw-bold p-5">Belum ada ulasan</h4>
                                </div>
                            </div>
                        @else
                            @foreach ($reviews as $review)
                                @if ($review->isPublish())
                                    <div class="item">
                                        <div class="testi-box position-relative overflow-hidden">
                                            <div class="row align-items-center">
                                                <div class="col-md-5 text-center px-1">
                                                    @if ($review->image === 'image.jpg' || $review->image == null)
                                                        <img src="{{ asset('assets/logo2.png') }}" alt="Gambar Produk"
                                                            class="img-fluid">
                                                    @else
                                                        <img src="{{ route('getImage', ['path' => 'landingpage', 'imageName' => $review->image]) }}"
                                                            alt="" class="img-fluid object-fit-contain"
                                                            style="max-height: 200px">
                                                    @endif
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="p-4">
                                                        <div class="border-bottom border-primary pb-1">
                                                            <h5 class="fw-bold f-24 lh-sm">
                                                                {{ $review->name }}<br>
                                                                <span class="text-muted fw-normal f-16">(Dibuat pada
                                                                    {{ \Carbon\Carbon::parse($review->created_at)->format('d-m-Y H:i') }})</span>
                                                            </h5>
                                                        </div>
                                                        <p class="text-muted mt-2">
                                                            {!! $review->body !!}
                                                        </p>
                                                        <div class="d-flex align-items-center mt-3">
                                                            <div class="date">
                                                                @if ($review->rating == 5)
                                                                    <img src="{{ asset('assets/landing/images/testi/rate-5.png') }}"
                                                                        class="img-fluid w-50">
                                                                @elseif ($review->rating == 4)
                                                                    <img src="{{ asset('assets/landing/images/testi/rate-4.png') }}"
                                                                        class="img-fluid w-50">
                                                                @elseif ($review->rating == 3)
                                                                    <img src="{{ asset('assets/landing/images/testi/rate-3.png') }}"
                                                                        class="img-fluid w-50">
                                                                @elseif ($review->rating == 2)
                                                                    <img src="{{ asset('assets/landing/images/testi/rate-2.png') }}"
                                                                        class="img-fluid w-50">
                                                                @elseif ($review->rating == 1)
                                                                    <img src="{{ asset('assets/landing/images/testi/rate-1.png') }}"
                                                                        class="img-fluid w-50">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="item">
                                        <div class="testi-box position-relative overflow-hidden justify-content-center">
                                            <h4 class="text-center fw-bold p-5">Belum ada ulasan</h4>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- end container -->
    </section>

    <!-- end testimonial -->

    <!-- contact section -->
    <section class="section contact overflow-hidden" id="kontak">
        <!-- start container -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="title text-center mb-5">
                        <h6 class="mb-0 fw-bold text-primary">Kontak Kami</h6>
                        <h2 class="f-40">{{ $contact->title }}</h2>
                        <p class="text-muted">{{ $contact->subTitle }}</p>

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
                                        <p class="f-14 mb-0 text-muted">
                                            {!! $contact->address !!}
                                        </p>
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
                                        <p class="f-14 mb-0 text-muted">Email: {{ $contact->email }}</p>
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
                                        @foreach ($contact->numbers as $number)
                                            <p class="f-14 mb-0 text-muted">{{ $number->description }}:
                                                {{ $number->number }}</p>
                                        @endforeach
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
                                <iframe src="{!! $contact->mapUrl !!}" width="550" height="450" style="border:0;"
                                    allowfullscreen="" loading="lazy"
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
