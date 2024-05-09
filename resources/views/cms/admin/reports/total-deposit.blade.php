@extends('cms.layouts.app', [
    'title' => 'Laporan Total Deposit',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Laporan Total Deposit
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            {{-- <a href="{{ route('package.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Paket</a> --}}
            {{-- <div class="hidden md:block mx-auto text-slate-500">Menampilkan {{ $data->paginationData->firstItem() }} hingga
                {{ $paginationData->lastItem() }} dari {{ $paginationData->total() }} data</div> --}}
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search...">
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
                        <th class="whitespace-nowrap">NAMA AGEN</th>
                        <th class="whitespace-nowrap">TOTAL PEMASUKAN</th>
                        <th class="whitespace-nowrap">TOTAL SETORAN</th>
                        <th class="whitespace-nowrap">TOTAL SISA</th>
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
