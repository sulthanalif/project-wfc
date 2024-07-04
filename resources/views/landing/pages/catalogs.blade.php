@extends('landing.layout', ['title' => 'Katalog Paket'])

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
                            <a href="#detailModal{{ $product->id }}" class="text-primary" data-bs-toggle="modal">
                                <div class="service-box">
                                    <div class="team-box text-start">
                                        <div class="row justify-content-end">
                                            <div class="col-lg-10 col-9">
                                                <div class="team-image ps-3">
                                                    @if ($product->detail->image === 'image.jpg' || $product->detail->image == null)
                                                        <img src="{{ asset('assets/logo2.png') }}" alt="Gambar Produk"
                                                            class="img-fluid">
                                                    @else
                                                        <img src="{{ route('getImage', ['path' => 'product', 'imageName' => $product->detail->image]) }}"
                                                            alt="" class="img-fluid">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="team-info position-absolute">
                                            <p class="h6 fw-bold">{{ $product->name }} <span
                                                    class="f-14 text-muted fw-normal">/
                                                    {{ $product->packageName->name }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div class="modal fade bd-example-modal-lg" id="detailModal{{ $product->id }}"
                                data-keyboard="false" tabindex="-1" aria-hidden="true">
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
                                                @if ($product->detail->image === 'image.jpg' || $product->detail->image == null)
                                                    <img src="{{ asset('assets/logo2.png') }}" alt="Gambar Produk"
                                                        class="img-fluid">
                                                @else
                                                    <img src="{{ route('getImage', ['path' => 'product', 'imageName' => $product->detail->image]) }}"
                                                        alt="" class="img-fluid">
                                                @endif
                                            </div>
                                            <div class="flex flex-row p-0 m-0">
                                                <p>Paket: <span class="text-muted">{{ $product->packageName->name }}</span>
                                                </p>
                                                <p>Harga: <span class="text-muted">Rp.
                                                        {{ number_format($product->price, 0, ',', '.') }}/hari</span>
                                                </p>
                                                <p>Jangka Waktu: <span class="text-muted">{{ $product->days }}
                                                        hari</span></p>
                                                <p>Deskripsi: <span class="text-muted">{!! $product->detail->description !!}</span>
                                                </p>
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
@endsection
