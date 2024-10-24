@extends('cms.layouts.app', [
    'title' => 'Laporan Rincian Pesanan Per Agent'
])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Laporan Rincian Pesanan Per Agent -->
                <div class="col-span-12 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Laporan Rincian Pesanan Per Agent
                        </h2>
                    </div>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="package" class="report-box__icon text-primary"></i>
                                    </div>
                                    <div class="text-2xl font-bold leading-8 mt-6">{{ $stats['totalProductAll'] }}</div>
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
                                        {{ number_format($stats['totalPriceAll'], 0, ',', '.') }}</div>
                                    <div class="text-base text-slate-500 mt-1">Total Harga</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Laporan Rincian Perpaket -->
            </div>
        </div>
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
            <div class="w-auto relative text-slate-500 border rounded">
                <select id="records_select"
                    onchange="window.location.href = (this.value === 'all' ? '?' + '{{ http_build_query(request()->except('agent', 'page')) }}' : '?agent=' + this.value + '&' + '{{ http_build_query(request()->except('agent', 'page')) }}')"
                    class="form-control box">
                    <option value="all">Semua</option>
                    @foreach ($agentsName as $agent)
                        <option value="{{ $agent->agentProfile->name }}" {{ request()->get('agent') == $agent->agentProfile->name ? 'selected' : '' }} >{{ $agent->agentProfile->name }}</option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('ragentOrder', array_merge(request()->except('page'), ['export' => 1])) }}"
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
                        <th class="text-center whitespace-nowrap">TOTAL PRODUK</th>
                        <th class="text-center whitespace-nowrap">TOTAL HARGA</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$datas)
                        <tr>
                            <td colspan="4" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($datas as $agent)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>

                                <td>
                                    <p class="text-slate-500 flex items-center mr-3"> {{ $agent['agent_name'] }} </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 text-center">{{ $agent['total_product'] }}</p>
                                </td>
                                <td>
                                    <p class="text-slate-500 text-center"> Rp.
                                        {{ number_format($agent['total_price'], 0, ',', '.') }} </p>
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
