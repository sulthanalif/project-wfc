@extends('cms.layouts.app', [
    'title' => 'Laporan Rincian Cicilan',
])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Laporan Rincian Cicilan -->
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Laporan Rincian Cicilan
                        </h2>
                        <a href="{{ route('instalment', ['export' => 1]) }}"
                            class="ml-auto flex items-center btn btn-primary shadow-md"> <i data-lucide="file"
                                class="w-4 h-4 mr-3"></i> Export </a>
                    </div>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="check" class="report-box__icon text-success"></i>
                                        {{-- <div class="ml-auto">
                                            <div class="report-box__indicator bg-danger tooltip cursor-pointer"
                                                title="2% Lower than last month"> 2% <i data-lucide="chevron-down"
                                                    class="w-4 h-4 ml-0.5"></i>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="text-2xl font-bold leading-8 mt-6">Rp. {{ number_format($stats['pay'], 0, ',', '.') }}</div>
                                    <div class="text-base text-slate-500 mt-1">Total Terbayar</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="clock" class="report-box__icon text-warning"></i>
                                        {{-- <div class="ml-auto">
                                            <div class="report-box__indicator bg-danger tooltip cursor-pointer"
                                                title="2% Lower than last month"> 2% <i data-lucide="chevron-down"
                                                    class="w-4 h-4 ml-0.5"></i>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="text-2xl font-bold leading-8 mt-6">Rp. {{ number_format($stats['remaining_pay'], 0, ',', '.') }}</div>
                                    <div class="text-base text-slate-500 mt-1">Total Belum Terbayar</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Laporan Rincian Cicilan -->
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">#</th>
                        <th class="text-center whitespace-nowrap">NAMA AGEN</th>
                        <th class="text-center whitespace-nowrap">NOMER ORDER</th>
                        <th class="text-center whitespace-nowrap">JUMLAH</th>
                        {{-- <th class="text-center whitespace-nowrap">KASIR</th> --}}
                        <th class="text-center whitespace-nowrap">WAKTU</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$payments)
                        <tr>
                            <td colspan="5" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($payments as $payment)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>

                                <td>
                                    <p class="text-center mr-3">
                                        {{ $payment->order->agent->agentProfile->name }} </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 text-center mr-3"> {{ $payment->order->order_number }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 flex text-center"> Rp.
                                        {{ number_format($payment->pay, 0, ',', '.') }} </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 text-center items-center mr-3"> {{ $payment->created_at }}
                                    </p>
                                </td>

                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        {{-- <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            {{ $paginationData->links('cms.layouts.paginate') }}
        </div> --}}
        <!-- END: Pagination -->
    </div>
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
            <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> {{ session('error') }}
            <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> <i
                    data-lucide="x" class="w-4 h-4"></i> </button>
        </div>
    @endif
@endsection
