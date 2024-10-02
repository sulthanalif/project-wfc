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
                    </div>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="check" class="report-box__icon text-success"></i>
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
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
            <form action="{{ route('instalment') }}" class="w-full sm:w-auto flex items-center gap-2">
                <div class="w-auto relative text-slate-500 border rounded">
                    <select id="records_select" name="agent">
                        <option value="">Semua</option>
                        @foreach ($agentsName as $agent)
                            <option value="{{ $agent }}" {{ request()->get('agent') == $agent ? 'selected' : '' }} >{{ $agent }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="">
                    <input id="order_date" name="date"
                    type="date" value="{{ request()->get('date') }}" class="form-control w-full">
                </div>

                <button class="btn btn-primary" type="submit"> <i data-lucide="search" class="w-4 h-4 mr-3"></i> Cari</button>
            </form>
            <a href="{{ route('instalment', array_merge(request()->except('page'), ['export' => 1])) }}"
                class="btn btn-primary shadow-md mr-2"> <i data-lucide="file"
                    class="w-4 h-4 mr-3"></i> Export </a>
            <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0 ml-auto">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="filter">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>

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
@endsection
