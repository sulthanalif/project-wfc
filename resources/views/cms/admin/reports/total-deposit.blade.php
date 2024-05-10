@extends('cms.layouts.app', [
    'title' => 'Laporan Total Deposit',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Laporan Total Deposit
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <a href="{{ route('totalDeposit', ['export' => 1]) }}" class="btn btn-primary shadow-md mr-2">Export</a>
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="intro-y col-span-6 flex flex-col items-center px-4 py-6 bg-white shadow-md rounded-md">
              <h3 class="text-lg font-medium text-gray-900">Total Pemasukan</h3>
              <p class="text-4xl font-bold text-teal-600 mt-2">Rp. {{ number_format($stats['totalPriceOrderAll'], 0, ',', '.') }}</p>
            </div>
          
            <div class="intro-y col-span-6 flex flex-col items-center px-4 py-6 bg-white shadow-md rounded-md">
              <h3 class="text-lg font-medium text-gray-900">Total Setoran</h3>
              <p class="text-4xl font-bold text-amber-600 mt-2">Rp. {{ number_format($stats['totalDepositAll'], 0, ',', '.') }}</p>
            </div>
          
            <div class="intro-y col-span-6 flex flex-col items-center px-4 py-6 bg-white shadow-md rounded-md">
              <h3 class="text-lg font-medium text-gray-900">Total Sisa</h3>
              <p class="text-4xl font-bold text-rose-600 mt-2">Rp. {{ number_format($stats['totalRemainingAll'], 0, ',', '.') }}</p>
            </div>
          </div>
          
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">#</th>
                        <th class="whitespace-nowrap">NAMA AGEN</th>
                        <th class="whitespace-nowrap">PEMASUKAN</th>
                        <th class="whitespace-nowrap">SETORAN</th>
                        <th class="whitespace-nowrap">SISA</th>
                        {{-- <th class="text-center whitespace-nowrap">AKSI</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @if (!$paginationData)
                        <tr>
                            <td colspan="5" class="font-medium whitespace-nowrap text-center">Belum Ada Data</td>
                        </tr>
                    @else
                        @foreach ($paginationData['data'] as $agent)
                            <tr class="intro-x">
                                <td>
                                    <p class="font-medium whitespace-nowrap text-center">{{ $loop->iteration }}</p>
                                </td>
                               
                                <td>
                                    <p class="text-slate-500 flex items-center mr-3"> {{ $agent['agent_name'] }} </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 flex items-center mr-3"> Rp. {{ number_format($agent['total_price_order'], 0, ',', '.') }} </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 flex items-center mr-3"> Rp. {{ number_format($agent['total_deposit'], 0, ',', '.') }} </p>
                                </td>
                                <td>
                                    <p class="text-slate-500 flex items-center mr-3"> Rp. {{ number_format($agent['total_remaining_payment'], 0, ',', '.') }} </p>
                                </td>
                                
                                {{-- <td>
                                    <p class="text-slate-500 flex items-center mr-3"> {{ $package->catalogName->name }} </p>
                                </td> --}}
                                
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
