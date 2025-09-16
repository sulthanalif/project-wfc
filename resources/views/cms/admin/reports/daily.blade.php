@extends('cms.layouts.app', [
    'title' => 'Laporan Harian',
])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Laporan Harian -->
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Laporan Harian
                        </h2>
                        <div class="w-auto relative text-slate-500 mt-2 lg:mt-0">
                            <select id="records_per_feature" class="form-control box">
                                <option value="orders" {{ request()->get('feature') == 'orders' ? 'selected' : '' }}>
                                    Pesanan</option>
                                <option value="package" {{ request()->get('feature') == 'package' ? 'selected' : '' }}>Paket
                                </option>
                                <option value="instalment"
                                    {{ request()->get('feature') == 'instalment' ? 'selected' : '' }}>
                                    Setoran</option>
                                <option value="distribution"
                                    {{ request()->get('feature') == 'distribution' ? 'selected' : '' }}>
                                    Distribusi</option>
                            </select>
                        </div>

                        @if (!$feature || $feature == 'orders')
                            <a href="{{ route('daily', ['feature' => 'orders', 'export' => 1]) }}"
                                class="btn btn-primary shadow-md ml-2"><i data-lucide="file" class="w-4 h-4 mr-3"></i>
                                Export</a>
                        @endif

                        @if (!$feature || $feature == 'package')
                            <a href="{{ route('daily', ['feature' => 'package', 'export' => 1]) }}"
                                class="btn btn-primary shadow-md ml-2"><i data-lucide="file" class="w-4 h-4 mr-3"></i>
                                Export</a>
                        @endif
                        
                        @if (!$feature || $feature == 'instalment')
                            <a href="{{ route('daily', ['feature' => 'instalment', 'export' => 1]) }}"
                                class="btn btn-primary shadow-md ml-2"><i data-lucide="file" class="w-4 h-4 mr-3"></i>
                                Export</a>
                        @endif
                    </div>
                    @if (!$feature || $feature == 'orders')
                        <div class="grid grid-cols-12 gap-6 mt-5">
                            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                <div class="report-box zoom-in">
                                    <div class="box p-5">
                                        <div class="flex">
                                            <i data-lucide="package" class="report-box__icon text-primary"></i>
                                        </div>
                                        <div class="text-2xl font-bold leading-8 mt-6">{{ $agentStats['totalProductAll'] }}
                                        </div>
                                        <div class="text-base text-slate-500 mt-1">Total Produk</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                <div class="report-box zoom-in">
                                    <div class="box p-5">
                                        <div class="flex">
                                            <i data-lucide="credit-card" class="report-box__icon text-pending"></i>
                                        </div>
                                        <div class="text-2xl font-bold leading-8 mt-6">Rp.
                                            {{ number_format($agentStats['totalPriceAll'], 0, ',', '.') }}</div>
                                        <div class="text-base text-slate-500 mt-1">Total Harga</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!$feature || $feature == 'package')
                        <div class="grid grid-cols-12 gap-6 mt-5">
                            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                <div class="report-box zoom-in">
                                    <div class="box p-5">
                                        <div class="flex">
                                            <i data-lucide="package" class="report-box__icon text-primary"></i>
                                        </div>
                                        <div class="text-2xl font-bold leading-8 mt-6">
                                            {{ $productStats['totalProductAll'] }}</div>
                                        <div class="text-base text-slate-500 mt-1">Total Produk</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!$feature || $feature == 'instalment')
                        <div class="grid grid-cols-12 gap-6 mt-5">
                            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                <div class="report-box zoom-in">
                                    <div class="box p-5">
                                        <div class="flex">
                                            <i data-lucide="check" class="report-box__icon text-success"></i>
                                        </div>
                                        <div class="text-2xl font-bold leading-8 mt-6">Rp.
                                            {{ number_format($instalmentStats['pay'], 0, ',', '.') }}</div>
                                        <div class="text-base text-slate-500 mt-1">Total Terbayar</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!$feature || $feature == 'distribution')
                        <div class="grid grid-cols-12 gap-6 mt-5">
                            {{-- <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                <div class="report-box zoom-in">
                                    <div class="box p-5">
                                        <div class="flex">
                                            <i data-lucide="package" class="report-box__icon text-primary"></i>
                                        </div>
                                        <div class="text-2xl font-bold leading-8 mt-6">{{ $distributionStats['totalOrderAll'] }}
                                        </div>
                                        <div class="text-base text-slate-500 mt-1">Total Pesanan</div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                <div class="report-box zoom-in">
                                    <div class="box p-5">
                                        <div class="flex">
                                            <i data-lucide="package" class="report-box__icon text-primary"></i>
                                        </div>
                                        <div class="text-2xl font-bold leading-8 mt-6">{{ $distributionStats['totalDistributionAll'] }}
                                        </div>
                                        <div class="text-base text-slate-500 mt-1">Total Distribusi Produk</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- END: Laporan Harian -->
            </div>
        </div>

        @if (!$feature || $feature == 'orders')
            @include('cms.admin.reports.partial.daily-order')
        @endif

        @if (!$feature || $feature == 'package')
            @include('cms.admin.reports.partial.daily-package')
        @endif

        @if (!$feature || $feature == 'instalment')
            @include('cms.admin.reports.partial.daily-instalment')
        @endif

        @if (!$feature || $feature == 'distribution')
            @include('cms.admin.reports.partial.daily-distribution' )
        @endif
    </div>
@endsection

@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('records_per_feature').addEventListener('change', function() {
                const feature = this.value;
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('feature', feature);
                window.location.search = urlParams.toString();
            });
        });
    </script>
@endpush
