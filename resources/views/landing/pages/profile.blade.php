@extends('landing.layout', ['title' => 'Profil Perusahaan'])

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
                        <a class="nav-link" href="{{ route('landing-page') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sejarah">Sejarah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#visi-misi">Visi Misi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#agent">Agen</a>
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

    <!-- sejarah section -->
    <section class="section service" id="sejarah">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="title text-center mb-5">
                        <h6 class="mb-0 fw-bold text-primary">Sejarah</h6>
                        <h2 class="f-40">{{ $detailProfile->titleHistory }}</h2>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 ">
                    <p class="text-muted mt-4">
                        {!! $detailProfile->bodyHistory !!}
                    </p>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{ empty($detailProfile->image) ? asset('assets/logo.png') : route('getImage', ['path' => 'landingpage', 'imageName' => $detailProfile->image]) }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->

    <!-- visi misi section -->
    <section class="section service bg-light" id="visi-misi">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="title text-center mb-5">
                        <h6 class="mb-0 fw-bold text-primary">Visi Misi</h6>
                        <h2 class="f-40">{{ $detailProfile->titleVM }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 text-center">
                    <h3 class="fw-bold">Visi Perusahaan</h3>
                    <p class="text-muted fst-italic">{!! $detailProfile->vision !!}</p>
                </div>
                <div class="col-lg-6 text-center">
                    <h3 class="fw-bold">Misi Perusahaan</h3>
                    <ul class="text-muted text-start">
                        @foreach ($detailProfile->mission as $mission )
                        <li>{{ $mission->content }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->

    <!-- agent section -->
    <section class="section team service" id="agent">
        <!-- start container -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="title text-center mb-5">
                        <h6 class="mb-0 fw-bold text-primary">Agent</h6>
                        <h2 class="f-40">Daftar Agent Perusahaan</h2>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-4">
                @foreach ($agentUsers as $agent)
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="service-box">
                            <div class="team-box text-start">
                                <div class="row justify-content-end">
                                    <div class="col-lg-10 col-9">
                                        <div class="team-image">
                                            @if (isset($agent->agentProfile->photo) && $agent->agentProfile->photo == null)
                                                <img src="{{ asset('assets/logo2.png') }}" alt="Gambar Produk"
                                                    class="img-fluid">
                                            @elseif (isset($agent->agentProfile->photo))
                                                <img src="{{ route('getImage', ['path' => 'photos', 'imageName' => $agent->agentProfile->photo]) }}"
                                                    alt="Foto Profil" class="img-fluid">
                                            @else
                                                <img src="{{ asset('assets/logo2.png') }}" alt="Gambar Produk"
                                                    class="img-fluid">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="team-info position-absolute">
                                    <p class="h6 fw-bold">{{ $agent->agentProfile->name ?? '' }} <span
                                            class="f-14 text-muted fw-normal"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- end container -->
    </section>
    <!-- end section -->
@endsection
